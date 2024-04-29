<?php

namespace App\Services\Inventory;

use App\Models\InventoryItem;
use App\Models\InventoryStock;
use App\Models\InventorySupplier;
use App\Models\Laboratory;
use App\Models\ListDropdown;
use App\Http\Resources\DefaultResource;
use App\Http\Resources\Inventory\ItemResource;
use App\Http\Resources\Inventory\SupplierResource;

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
            'data' => new SupplierResource($data),
            'message' => 'Supplier creation was successful!', 
            'info' => "You've successfully created the new supplier."
        ];
    }

    public function item($request){
        $data = InventoryItem::create(array_merge($request->all(),[
            'code' => $this->generateCode($request),
            'reorder' => 1,
            'img' => 'avatar.jpg'
        ]));
        return [
            'data' => new ItemResource($data),
            'message' => 'Item creation was successful!', 
            'info' => "You've successfully created the new item."
        ];
    }

    public function stock($request){
        $data = InventoryStock::create(array_merge($request->all(),[
            'code' => date('Ymdhis'),
            'item_id' => 1
        ]));
        return [
            'data' => new DefaultResource($data),
            'message' => 'Stock was added successful!', 
            'info' => "You've successfully added the new stock."
        ];
    }

    public function generateCode($request){
        $laboratory_type = 9;
        $lab = Laboratory::where('id',$this->laboratory)->first();
        $lab_type = ListDropdown::select('others')->where('id',$laboratory_type)->first();
        $c = InventoryItem::where('laboratory_id',$this->laboratory)->where('laboratory_type',$laboratory_type)->count();
        $code = $lab->code.'INV-'.$lab_type['others'].'-'.str_pad(($c+1), 5, '0', STR_PAD_LEFT);  
        return $code;
    }
}
