<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'available' => '₱'.number_format($this->available,2,'.',','),
            'total' => '₱'.number_format($this->total,2,'.',','),
            'deduction' => '₱'.number_format($this->deduction,2,'.',','),
            'transactions' => TransactionResource::collection($this->transactions),
            'updated_at' => $this->updated_at
        ];
    }
}
