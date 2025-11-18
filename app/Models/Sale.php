<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    // PERBAIKAN: Gunakan fillable secara eksplisit untuk menjamin Mass Assignment
    protected $fillable = [
        'code',
        'date',
        'customer_name',
        'subtotal',
        'discount', // Diskon
        'tax',      // Pajak
        'total',
        'notes',
    ];

    protected $dates = ['date'];

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
