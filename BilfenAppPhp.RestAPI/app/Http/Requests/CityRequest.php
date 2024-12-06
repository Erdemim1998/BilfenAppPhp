<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
            'CountryId' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'Id.required' => 'İl Kodu bilgisi zorunludur.',
            'Name.required' => 'İl Adı bilgisi zorunludur.',
            'CountryId.required' => 'Ülke bilgisi zorunludur.'
        ];
    }
}
