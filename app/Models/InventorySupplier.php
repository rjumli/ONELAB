<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySupplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'contact_no',
        'address',
        'barangay_code',
        'municipality_code',
        'is_active',
        'user_id',
        'laboratory_id'
    ];

}
