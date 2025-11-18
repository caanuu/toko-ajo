<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Ambil data produk beserta nama kategorinya (Eager Loading) biar ringan
        $products = Product::with('category')->latest()->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        // Kita butuh data kategori untuk ditampilkan di Pilihan (Select Option)
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'sku'         => 'required|unique:products',
            'name'        => 'required|max:30',
            'sell_price'  => 'required|numeric',
            'stock'       => 'required|numeric'
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')
                         ->with('success', 'Produk berhasil ditambahkan');
    }
}
