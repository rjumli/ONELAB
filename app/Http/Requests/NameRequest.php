<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        if($this->option == 'add'){
            return [
                'name' => 'sometimes|required|string|unique:list_names,name,NULL,'.$this->id.',laboratory_type,'.$this->laboratory_type,
            ];
        }
        return [];
    }
}
