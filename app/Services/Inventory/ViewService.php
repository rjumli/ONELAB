<?php

namespace App\Services\Inventory;

use App\Models\InventoryItem;
use App\Models\InventorySupplier;
use App\Http\Resources\Inventory\ItemResource;
use App\Http\Resources\Inventory\SupplierResource;

class ViewService
{
    public $laboratory;

    public function __construct()
    {
        $this->laboratory = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_id : null;
        $this->type = (\Auth::user()->userrole) ? \Auth::user()->userrole->laboratory_type : null;
    }

    public function suppliers($request){
        $data = SupplierResource::collection(
            InventorySupplier::query()
            ->with('user.profile')
            ->with('municipality.province.region','municipality:code,name,province_code','barangay:code,name')
            ->where('laboratory_id',$this->laboratory)
            ->where('is_active',$request->is_active)
            ->get()
        );
        return $data;
    }

    public function items($request){
        $data = ItemResource::collection(
            InventoryItem::query()
            ->with('laboratory_type','laboratory','category','unittype')
            ->where('laboratory_id',$this->laboratory)
            ->where('laboratory_type',$this->type)
            ->when($request->keyword, function ($query, $keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->paginate($request->count)
        );
        return $data;
    }

    public function supplier_lists(){
        $data = InventorySupplier::where('laboratory_id',$this->laboratory)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }
}
