<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    /**
     * Menampilkan daftar penjualan (Laporan Penjualan).
     */
    public function index(Request $request)
    {
        $query = Sale::query();
        $detailQuery = SaleDetail::query();

        // 1. Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
            $detailQuery->whereHas('sale', function ($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        }

        $sales = $query->latest()->get();

        // 2. Hitung Ringkasan (Summary Cards) - **PERBAIKAN SINTAKS DI SINI**
        $summary = [
            'count' => $sales->count(),
            'subtotal' => $sales->sum('subtotal'),
            'discount' => $sales->sum('discount'),
            'tax' => $sales->sum('tax'), // Tambahkan tax agar lengkap
            'total' => $sales->sum('total'),
        ];

        // 3. Hitung Top 10 Produk Terjual
        $topProducts = $detailQuery->select(
            'product_id',
            DB::raw('sum(qty) as total_qty'),
            DB::raw('sum(subtotal) as total_value')
        )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->with('product')
            ->get();

        return view('sales.index', compact('sales', 'summary', 'topProducts'));
    }

    /**
     * Menampilkan form input penjualan.
     */
    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $lastSale = Sale::latest()->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $generatedCode = 'SO-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        // Ambil nilai default diskon dan pajak dari tabel settings
        $settings = DB::table('settings')->pluck('value', 'key');
        $defaultDiscount = $settings['default_discount'] ?? 0;
        $defaultTax = $settings['default_tax'] ?? 0;

        return view('sales.create', compact('products', 'generatedCode', 'defaultDiscount', 'defaultTax'));
    }

    /**
     * Menyimpan transaksi penjualan dan memperbarui stok.
     */
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.qty' => 'required|numeric|min:1',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'code' => $request->code,
                'date' => $request->date,
                'customer_name' => $request->customer_name ?? 'Umum',
                'subtotal' => 0,
                'discount' => $request->discount ?? 0,
                'tax' => $request->tax ?? 0,
                'total' => 0,
                'notes' => $request->notes,
            ]);

            $calculatedSubtotal = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup!");
                }

                $price = $product->sell_price;
                $subtotalItem = $item['qty'] * $price;
                $calculatedSubtotal += $subtotalItem;

                SaleDetail::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $price,
                    'subtotal' => $subtotalItem,
                ]);

                $oldStock = $product->stock;
                $product->stock -= $item['qty'];
                $product->save();

                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'movement_type' => 'OUT',
                    'qty' => $item['qty'],
                    'before_qty' => $oldStock,
                    'after_qty' => $product->stock,
                    'unit_cost' => $price,
                    'ref_type' => Sale::class,
                    'ref_id' => $sale->id,
                    'moved_at' => $request->date,
                ]);
            }

            $finalTotal = $calculatedSubtotal - ($request->discount ?? 0) + ($request->tax ?? 0);

            $sale->update([
                'subtotal' => $calculatedSubtotal,
                'total' => $finalTotal
            ]);

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menyimpan penjualan: ' . $e->getMessage())->withInput();
        }
    }
}
