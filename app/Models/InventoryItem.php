<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'img',
        'reorder',
        'unit',
        'unit_id',
        'category_id',
        'laboratory_id',
        'laboratory_type',
        'is_equipment'
    ];

    public function laboratory()
    {
        return $this->belongsTo('App\Models\Laboratory', 'laboratory_id', 'id');
    }

    public function laboratory_type()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'laboratory_type', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'category_id', 'id');
    }

    public function unittype()
    {
        return $this->belongsTo('App\Models\ListDropdown', 'unit_id', 'id');
    }
}
