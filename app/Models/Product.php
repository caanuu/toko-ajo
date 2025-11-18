<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    // Relasi ke Kategori (Produk milik 1 Kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi lain (nanti kita tambahkan saat fitur stok)
}
