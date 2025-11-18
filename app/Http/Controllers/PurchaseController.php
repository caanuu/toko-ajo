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
        // Logika Filter Laporan sesuai BAB IV Gambar 4.10
        $query = Purchase::with('supplier');

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $purchases = $query->latest()->get();

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        $products = Product::all();

        // Generate Kode Otomatis (PO-TahunBulan-Urutan)
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
        return view('purchases.show', compact('purchase'));
    }
}
