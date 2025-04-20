<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCountryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'country' => 'required|string|max:255|min:2',
            'user_id' => 'required|integer|exists:users,id'
        ];
    }

    public function messages()
    {
        return [
            'country.required' => 'Please select a country',
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'Invalid user ID'
        ];
    }
}