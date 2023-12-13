<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HouseRequest extends FormRequest
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
        return [
            'search' => 'string|nullable',
            'page' => 'integer|nullable',
            'showBigData' => 'in:true,false',
            'priceFrom' => 'integer|nullable|min:0',
            'priceTo' => 'integer|nullable|min:0',
            'bedrooms' => 'integer|nullable|min:0',
            'bathrooms' => 'integer|nullable|min:0',
            'storeys' => 'integer|nullable|min:0',
            'garages' => 'integer|nullable|min:0',
        ];
    }
}
