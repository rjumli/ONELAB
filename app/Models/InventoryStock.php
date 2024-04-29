<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'brand',
        'unit',
        'number',
        'warranty',
        'price',
        'supplier_id',
        'item_id',
        'bought_at'
    ];
}
