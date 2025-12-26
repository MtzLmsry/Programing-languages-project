<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'FirstName' => 'required|string|max:255',
            'LastName' => 'required|string|max:255',
            'phone' => 'required|string|digits:10|unique:users,phone',
            'password' => 'required|string|min:8',
            'BirthDate' => 'required|date',
            'personalPhoto' => 'required|image|mimes:jpeg,png,jpg',
            'idPhotoFront' => 'required|image|mimes:jpeg,png,jpg',
            'idPhotoBack' => 'required|image|mimes:jpeg,png,jpg',
        ];
    }
}
