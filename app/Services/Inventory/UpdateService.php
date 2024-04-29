<?php

namespace App\Services\Inventory;

use App\Models\InventorySupplier;
use App\Http\Resources\Inventory\SupplierResource;

class UpdateService
{
    public function supplier($request){
        $data = InventorySupplier::findOrFail($request->id)->update($request->all());
        $data = InventorySupplier::with('user.profile')->with('municipality.province.region','municipality:code,name,province_code','barangay:code,name')
        ->where('id',$request->id)->first();
        return [
            'data' => new SupplierResource($data),
            'message' => 'Supplier updated successfully!', 
            'info' => "You've successfully updated supplier."
        ];
    }
}
