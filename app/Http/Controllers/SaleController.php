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
    public function index(Request $request)
    {
        // Logika Filter Laporan Penjualan sesuai BAB IV Gambar 4.11
        $query = Sale::query();

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $sales = $query->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('stock', '>', 0)->get();
        $lastSale = Sale::latest()->first();
        $nextId = $lastSale ? $lastSale->id + 1 : 1;
        $generatedCode = 'TRX-' . date('ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('sales.create', compact('products', 'generatedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.qty' => 'required|numeric|min:1',
        ]);

        try {
            DB::beginTransaction();

            $sale = Sale::create([
                'code' => $request->code,
                'date' => $request->date,
                'customer_name' => $request->customer_name ?? 'Umum',
                'subtotal' => 0,
                'total' => 0,
                'notes' => $request->notes,
            ]);

            $grandTotal = 0;

            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak cukup!");
                }

                $price = $product->sell_price;
                $subtotalItem = $item['qty'] * $price;
                $grandTotal += $subtotalItem;

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

            $sale->update(['subtotal' => $grandTotal, 'total' => $grandTotal]);

            DB::commit();
            return redirect()->route('sales.index')->with('success', 'Penjualan berhasil!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
