<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApartmentRequest extends FormRequest
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
            'title' => 'required|string',
            'price' => 'required|numeric',
            'rooms' => 'required|integer',
            'floor_number' => 'required|integer',
            'apartment_type' => 'required|in:one_room,multipul_rooms',
            'area' => 'required|integer',
            'description' => 'required|string',
            'city_id' => 'required|exists:cities,id',
            'governorate_id' => 'required|exists:governorates,id',
            'is_internet_available' => 'boolean',
            'is_air_conditioned' => 'boolean',
            'is_cleaning_available' => 'boolean',
            'is_electricity_available' => 'boolean',
            'is_furnished' => 'boolean',
            'images' => 'required|array|min:4',
            'images.*' => 'image|mimes:jpeg,png,jpg'
        ];
    }
}
