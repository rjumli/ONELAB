<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuotationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'mode' => $this->mode,
            'total' => $this->total,
            'subtotal' => $this->subtotal,
            'discount' => $this->discount,
            'discounted' => $this->discounted,
            'laboratory' => $this->laboratory,
            'type' => $this->type,
            'purpose' => $this->purpose,
            'status' => $this->status,
            'customer' => new CustomerViewResource($this->customer),
            'conforme' => $this->conforme->name, 
            'conforme_no' => $this->conforme->contact_no,
            'conforme_id' => $this->conforme->id, 
            'received' => $this->createdby->profile->firstname.' '.$this->createdby->profile->lastname,
            'samples' => $this->samples,
            'due_at' => $this->due_at,
            'updated_at' => $this->updated_at,
            'created_at' => $this->created_at,
        ];
    }
}
