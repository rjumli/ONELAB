<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FinanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->option == 'op'){
            return [
                'customer_id' => 'sometimes|required',
                'collection_id' => 'sometimes|required',
                'payment_id' => 'sometimes|required',
                'selected' => 'required|array|min:1',
                'selected.*' => 'required|distinct|min:1'
            ];
        }else if($this->option == 'receipt'){
            return [
                'deposit_id' => 'required|integer',
                'orseries' => 'required'
                // 'name' => 'sometimes|required|string|unique:inventory_items,name,NULL,'.$this->id.',laboratory_id,'.$this->laboratory_id.',laboratory_type,'.$this->laboratory_type.',unit,'.$this->unit.',unit_id,'.$this->unit_id,
            ];
        }else if($this->option == 'series'){
            return [
                'name' => 'required|string',
                'start' => 'required|integer',
                'next' => 'required|integer',
                'end' => 'required|integer',
            ];
        }
    }
}
