<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DistrictRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'Id' => 'required|string|max:255',
            'Name' => 'required|string|max:255',
            'CityId' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'Id.required' => 'İlçe Kodu bilgisi zorunludur.',
            'Name.required' => 'İlçe Adı bilgisi zorunludur.',
            'CityId.required' => 'İl bilgisi zorunludur.'
        ];
    }
}
