<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseDetail;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Purchase::with('supplier');
        $detailQuery = PurchaseDetail::query();

        // Filter Tanggal
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);

            // Filter untuk detail (Top 10) juga harus ikut tanggal
            $detailQuery->whereHas('purchase', function ($q) use ($request) {
                $q->whereBetween('date', [$request->start_date, $request->end_date]);
            });
        }

        $purchases = $query->latest()->get();

        // Hitung Ringkasan (Card Atas)
        $summary = [
            'count' => $purchases->count(),
            'subtotal' => $purchases->sum('subtotal'),
            'discount' => $purchases->sum('discount'),
            'total' => $purchases->sum('total'),
        ];

        // Hitung Top 10 Produk Dibeli
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

        return view('purchases.index', compact('purchases', 'summary', 'topProducts'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        $lastPurchase = Purchase::latest()->first();
        $nextId = $lastPurchase ? $lastPurchase->id + 1 : 1;
        $generatedCode = 'PO-' . date('ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('purchases.create', compact('suppliers', 'products', 'generatedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'supplier_id' => 'required',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.qty' => 'required|numeric|min:1',
            'products.*.cost' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::create([
                'code' => $request->code,
                'date' => $request->date,
                'supplier_id' => $request->supplier_id,
                'subtotal' => 0,
                'total' => 0,
                'notes' => $request->notes,
            ]);

            $grandTotal = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotalItem = $item['qty'] * $item['cost'];
                $grandTotal += $subtotalItem;

                PurchaseDetail::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'unit_cost' => $item['cost'],
                    'subtotal' => $subtotalItem,
                ]);

                // Update Stok (Bertambah)
                $oldStock = $product->stock;
                $product->stock += $item['qty'];
                $product->save();

                // Catat Kartu Stok (IN)
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'movement_type' => 'IN',
                    'qty' => $item['qty'],
                    'before_qty' => $oldStock,
                    'after_qty' => $product->stock,
                    'unit_cost' => $item['cost'],
                    'ref_type' => Purchase::class,
                    'ref_id' => $purchase->id,
                    'moved_at' => $request->date,
                ]);
            }

            $purchase->update(['subtotal' => $grandTotal, 'total' => $grandTotal]);

            DB::commit();
            return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Purchase $purchase)
    {
        $purchase->load('details.product');
        return view('purchases.show', compact('purchase'));
    }
}
