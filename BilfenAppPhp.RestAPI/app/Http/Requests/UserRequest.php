<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'UserName' => 'required|string|max:255',
            'Email' => 'required|email|max:255',
            'Password' => 'required|string',
            'RoleId' => 'required|exists:roles,Id',
            'ImagePath' => 'nullable|string',
            'TCKN' => 'required|string|min:11|max:11',
            'MotherName' => 'required|string|max:255',
            'FatherName' => 'required|string|max:255',
            'BirthDate' => 'required|string|max:255',
            'Gender' => 'required|string|max:255',
            'CivilStatus' => 'required|string|max:255',
            'EmploymentDate' => 'required|string|max:255',
            'MilitaryStatus' => 'nullable|string',
            'PostponementDate' => 'required_if:MilitaryStatus,P|nullable|date',
            'CountryId' => 'required|string|max:255',
            'CityId' => 'required|string|max:255',
            'DistrictId' => 'required|string|max:255',
            'Address' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'FirstName.required' => 'Adı bilgisi zorunludur.',
            'LastName.required' => 'Soyadı bilgisi zorunludur.',
            'UserName.required' => 'Kullanıcı Adı bilgisi zorunludur.',
            'Email.required' => 'Email bilgisi zorunludur.',
            'Password.required' => 'Parola bilgisi zorunludur.',
            'RoleId.required' => 'Rol bilgisi zorunludur.',
            'RoleId.exists' => 'Olmayan rol bilgisi kaydedilemedi.',
            'TCKN.required' => 'TC Kimlik Numarası bilgisi zorunludur. 11 haneli olmalıdır.',
            'TCKN.min' => 'TC Kimlik Numarası bilgisi 11 haneli olmalıdır.',
            'MotherName.required' => 'Anne Adı bilgisi zorunludur.',
            'FatherName.required' => 'Baba Adı bilgisi zorunludur.',
            'BirthDate.required' => 'Doğum Tarihi bilgisi zorunludur.',
            'Gender.required' => 'Cinsiyet bilgisi zorunludur.',
            'CivilStatus.required' => 'Medeni Hal bilgisi zorunludur.',
            'EmploymentDate.required' => 'İşe Giriş Tarihi bilgisi zorunludur.',
            'PostponementDate.required_if' => 'Askerlik durumu "Tecilli" olduğunda, Tecil Tarihi bilgisi zorunludur.',
            'CountryId.required' => 'Ülke bilgisi zorunludur.',
            'CityId.required' => 'İl bilgisi zorunludur.',
            'DistrictId.required' => 'İlçe bilgisi zorunludur.',
            'Address.required' => 'Adres bilgisi zorunludur.',
        ];
    }
}
