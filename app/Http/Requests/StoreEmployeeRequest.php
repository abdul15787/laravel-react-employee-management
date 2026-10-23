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
           'employee_code' => ['required', 'string', 'max:50', 'unique:employees,employee_code'],
           'first_name' => ['required', 'string', 'max:100'],
           'last_name' => ['required', 'string', 'max:100'],
           'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
           'phone' => ['nullable', 'string', 'max:20'],
           'designation' => ['required', 'string', 'max:100'],
           'salary' => ['nullable', 'numeric', 'min:0'],
           'joining_date' => ['nullable', 'date']
        ];
    }
}
