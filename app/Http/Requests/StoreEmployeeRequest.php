<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
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
        'username' => 'required|string|max:255|min:3',
        'dob' => 'required|date',
        'country' => 'required|exists:countries,id', 
        'state' => 'required|exists:states,id', 
        'city' => 'required|exists:cities,id', 
        'income' => 'required|numeric',
        'gender' => 'required|in:male,female,other',
        'address' => 'required|string|max:255|min:3',
        'phone' => 'required|string|max:15|min:4',
        ];
    }
}
