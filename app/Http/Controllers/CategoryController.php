<?php

namespace App\Http\Controllers;

use App\Models\Category; // Pastikan Model di-import
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Ambil semua data
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        // Validasi Input
        $request->validate([
            'name' => 'required|max:20',
            'description' => 'nullable'
        ]);

        // Simpan ke Database
        Category::create($request->all());

        return redirect()->route('categories.index')
                         ->with('success', 'Kategori berhasil ditambahkan!');
    }

    // (Method edit, update, destroy bisa menyusul nanti)
}
