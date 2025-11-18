<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Agar kita bisa langsung simpan data tanpa mendefinisikan satu per satu (Mass Assignment)
    protected $guarded = [];

    // Relasi: Satu Kategori bisa punya banyak Produk
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
