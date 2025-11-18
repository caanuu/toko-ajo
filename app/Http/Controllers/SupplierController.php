<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'    => 'required|max:40',
            'contact' => 'required|max:50', // Bisa No HP atau Email
            'address' => 'nullable'
        ]);

        Supplier::create($request->all());

        return redirect()->route('suppliers.index')
                         ->with('success', 'Supplier berhasil ditambahkan!');
    }

    // Fitur Edit & Hapus (Opsional untuk tahap awal, tapi disiapkan methodnya)
    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate(['name' => 'required|max:40']);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success', 'Data berhasil diupdate');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier dihapus');
    }
}
