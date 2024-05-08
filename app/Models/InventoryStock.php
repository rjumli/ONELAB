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
        'quantity',
        'number',
        'date', //warranty or expiration
        'price',
        'supplier_id',
        'item_id',
        'bought_at'
    ];

    public function item()
    {
        return $this->belongsTo('App\Models\InventoryItem', 'item_id', 'id');
    }

    public function supplier()
    {
        return $this->belongsTo('App\Models\InventorySupplier', 'supplier_id', 'id');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = trim(str_replace(',','',$value),'₱');
    }

    public function getPriceAttribute($value)
    {
        return '₱'.number_format($value,2,'.',',');
    }
}
