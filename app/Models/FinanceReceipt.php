<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceReceipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'op_id',
        'orseries_id',
        'payor_id',
        'created_by',
        'laboratory_id'
    ];
}
