<?php

namespace App\Services\Inventory;

use App\Models\InventorySupplier;

class SaveService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
    }
    
    public function supplier($request){
        $data = InventorySupplier::create(array_merge($request->all(),['laboratory_id' => $this->laboratory, 'user_id' => \Auth::user()->id]));
        return [
            'data' => $data,
            'message' => 'Supplier creation was successful!', 
            'info' => "You've successfully created the new supplier."
        ];
    }
}
