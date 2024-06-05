<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListDropdown extends Model
{
    use HasFactory;

    public function ops()
    {
        return $this->hasMany('App\Models\FinanceOp', 'payment_id');
    } 
}
