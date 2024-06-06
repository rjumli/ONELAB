<?php

namespace App\Http\Requests;

use App\Rules\NotZeroPeso;
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
                'orseries' => 'required',
                'cheque_number' => 'sometimes|required_if:orseries,!=,null|unique:finance_receipts,number,NULL,'.$this->id.',orseries_id,'.$this->orseries_id,
                'cheque_cheque_at' => 'sometimes|required',
                'cheque_bank' => 'sometimes|required',
                'cheque_amount' => ['sometimes','required', new NotZeroPeso],
            ];
        }else if($this->option == 'series'){
            return [
                'name' => 'required|string',
                'start' => 'required|integer',
                'next' => 'required|integer',
                'end' => 'required|integer',
            ];
        }else{
            return [];
        }
    }

    public function messages()
    {
        if($this->type === 'Cheque'){
            $message = [
                'cheque_number.unique' => 'already exist',
                'cheque_number.required_if' => 'required',
                'cheque_cheque_at.required' => 'required',
                'cheque_bank.required' => 'required',
                'cheque_amount.required' => 'required',
            ];
        }else{
            $message = [];
        }
        return $message;
    }
}
