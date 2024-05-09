<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $name = $this->item->name.' '.$this->item->unit.''.$this->item->unittype->others;
        $category = $this->item->category->name;

        if($category == 'Equipment'){
            $warranty = ($this->date >= now()) ? true : false;
            $outofstock = false;
            $expired = false;
        }else{
            $expired = ($this->date <= now()) ? true : false;
            $outofstock = ($this->quantity == 0) ? true : false;
            $warranty = false;
        }
        return [
            'id' => $this->id,
            'name' => $name,
            'code' => $this->item->code,
            'img' => ($this->item->img === 'avatar.jpg') ? '/images/avatars/'.$this->item->img : '/storage/'.$this->item->img,
            'supplier' => $this->supplier->name,
            'quantity' => $this->quantity,
            'qnty' => 1,
            'category' => $category,
            'brand' => $this->brand,
            'number' => $this->number,
            'code' => $this->code,
            'date' => $this->date,
            'price' => $this->price,
            'bought' => $this->bought_at,
            'date' => $this->date,  
            'expired' => $expired,
            'warranty' => $warranty,
            'outofstock' => $outofstock
        ];
    }
}
