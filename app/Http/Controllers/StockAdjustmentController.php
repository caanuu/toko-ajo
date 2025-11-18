<?php

namespace App\Http\Controllers;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentDetail;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    public function index()
    {
        $adjustments = StockAdjustment::latest()->get();
        return view('adjustments.index', compact('adjustments'));
    }

    public function create()
    {
        $products = Product::all();

        // Generate Kode ADJ-YYMM-XXXX
        $lastAdj = StockAdjustment::latest()->first();
        $nextId = $lastAdj ? $lastAdj->id + 1 : 1;
        $generatedCode = 'ADJ-' . date('ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('adjustments.create', compact('products', 'generatedCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'reason' => 'required|string',
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.qty_change' => 'required|numeric|not_in:0', // Tidak boleh 0
        ]);

        try {
            DB::beginTransaction();

            // 1. Simpan Header
            $adjustment = StockAdjustment::create([
                'code' => $request->code,
                'date' => $request->date,
                'reason' => $request->reason,
                'notes' => $request->notes,
            ]);

            // 2. Loop Detail Barang
            foreach ($request->products as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Simpan Detail
                StockAdjustmentDetail::create([
                    'stock_adjustment_id' => $adjustment->id,
                    'product_id' => $item['product_id'],
                    'qty_change' => $item['qty_change'], // Bisa minus (hilang) atau plus (ditemukan)
                    'unit_cost' => 0, // Biasanya 0 kalau adjustment, atau ambil dari avg cost
                ]);

                // Update Stok Produk
                $oldStock = $product->stock;
                $product->stock += $item['qty_change']; // Tambah dengan nilai (bisa negatif)
                $product->save();

                // Catat ke Kartu Stok (Movement)
                StockMovement::create([
                    'product_id' => $item['product_id'],
                    'movement_type' => 'ADJ', // Tipe Adjustment
                    'qty' => abs($item['qty_change']), // Disimpan positif untuk jumlah gerak
                    'before_qty' => $oldStock,
                    'after_qty' => $product->stock,
                    'unit_cost' => 0,
                    'ref_type' => StockAdjustment::class,
                    'ref_id' => $adjustment->id,
                    'moved_at' => $request->date,
                    'notes' => $item['qty_change'] > 0 ? 'Penambahan Stok' : 'Pengurangan Stok',
                ]);
            }

            DB::commit();
            return redirect()->route('adjustments.index')->with('success', 'Stok berhasil disesuaikan!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(StockAdjustment $adjustment)
    {
         // Eager load details dengan produk agar tidak n+1 problem
        $adjustment->load('details.product');
        return view('adjustments.show', compact('adjustment'));
    }
}
