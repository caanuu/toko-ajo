<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    // PERBAIKAN: Gunakan fillable secara eksplisit (seperti Sale)
    protected $fillable = [
        'code',
        'date',
        'supplier_id',
        'subtotal',
        'discount', // Diskon
        'tax',      // Pajak
        'total',
        'notes',
    ];

    protected $dates = ['date']; // Agar format tanggal otomatis

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class);
    }
}
