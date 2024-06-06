<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'amount' => '₱'.number_format($this->amount,2,'.',','),
            'balance' => '₱'.number_format($this->balance,2,'.',','),
            'transacable' => $this->transacable,
            'is_credit' => $this->is_credit,
            'created_at' => $this->created_at
        ];
    }
}
