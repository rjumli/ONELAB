<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'total', 
        'available',
        'deduction',
        'customer_id'
    ];

    public function transactions()
    {
        return $this->hasMany('App\Models\WalletTransaction', 'wallet_id');
    }

    public function customer()
    {
        return $this->belongsTo('App\Models\Customer', 'customer_id', 'id');
    }
}
