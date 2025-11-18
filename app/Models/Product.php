<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // PERBAIKAN: Gunakan $fillable untuk mengizinkan Mass Assignment
    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'sell_price',
        'stock',
    ];

    // Relasi ke Kategori (Produk milik 1 Kategori)
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi lain (nanti kita tambahkan saat fitur stok)
}
