<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $guarded = [];
    protected $dates = ['date'];

    public function details()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
