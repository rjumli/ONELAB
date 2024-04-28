<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if($this->option == 'supplier'){
            return [
                'name' => 'sometimes|required|string|unique:inventory_suppliers,name,NULL,'.$this->id.',laboratory_id,'.$this->laboratory_id,
                'email' => 'sometimes|required|email|max:150',
                'contact_no' => 'sometimes|required|numeric|digits:11',
                'address' => 'sometimes|required|string|max:200',
                'region_code' => 'sometimes|required',
                'province_code' => 'sometimes|required',
                'municipality_code' => 'sometimes|required',
                'barangay_code' => 'sometimes|nullable',
            ];
        }
        return [];
    }
}
