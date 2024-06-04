<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'is_credit',
        'receipt_id',
        'wallet_id'
    ];


    public function wallet()
    {
        return $this->belongsTo('App\Models\Wallet', 'wallet_id', 'id');
    }

    public function receipt()
    {
        return $this->belongsTo('App\Models\FinancialReceipt', 'receipt_id', 'id');
    }
}
