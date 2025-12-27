<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'phone' => [
                'required',
                'string',
                // يسمح بإعادة استخدام الرقم إذا كان الحساب غير مفعل
                Rule::unique('users', 'phone')->where(function ($query) {
                    $query->where('account_status', 'Active');
                }),
            ],
            'password' => 'required|string|min:6',
            'BirthDate' => 'required|date',
            'personalPhoto' => 'required|image',
            'idPhotoFront' => 'required|image',
            'idPhotoBack' => 'required|image',
        ];
    }
}