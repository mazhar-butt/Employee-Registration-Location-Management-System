<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255|min:3',
            'last_name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:7|max:255',
        ];
    }

    public function attributes()
    {
        // Use translations for field names based on the current locale
        return [
            'first_name' => trans('validation.attributes.first_name'),
            'last_name' => trans('validation.attributes.last_name'),
            'email' => trans('validation.attributes.email'),
            'username' => trans('validation.attributes.username'),
            'password' => trans('validation.attributes.password'),
        ];
    }
}