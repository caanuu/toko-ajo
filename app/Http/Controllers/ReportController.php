<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // 1. KARTU STOK
    public function stockCard(Request $request)
    {
        $products = Product::all(); // Untuk dropdown filter
        $movements = collect();     // Koleksi kosong awal
        $selectedProduct = null;

        // Jika user sudah memilih produk dan klik "Filter"
        if ($request->has('product_id') && $request->product_id != '') {
            $selectedProduct = Product::find($request->product_id);

            $movements = StockMovement::where('product_id', $request->product_id)
                ->orderBy('moved_at', 'asc') // Urutkan dari tanggal lama ke baru
                ->orderBy('id', 'asc')       // Jika tanggal sama, urutkan ID
                ->get();
        }

        return view('inventory.stock-card', compact('products', 'movements', 'selectedProduct'));
    }

    // 2. STOK MINIMUM (Bonus sekalian dibuatkan)
    public function minStock()
    {
        // Ambil produk yang stoknya <= 5
        $lowStockProducts = Product::with('category')->where('stock', '<=', 5)->get();

        return view('inventory.minimum-stock', compact('lowStockProducts'));
    }
}
