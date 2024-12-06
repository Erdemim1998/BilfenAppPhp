<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DocumentRequest extends FormRequest
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
            'Id' => 'integer',
            'Name' => 'required|string|max:255',
            'FilePath' => 'required|string|max:255',
            'Status' => 'string|max:255',
            'UserId' => 'integer'
        ];
    }

    public function messages(): array
    {
        return [
            'Name.required' => 'Evrak adı bilgisi zorunludur.',
            'FilePath.required' => 'Dosya seçmek zorunludur.'
        ];
    }
}
