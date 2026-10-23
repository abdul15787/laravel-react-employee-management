<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
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
        $employee = $this->route('employees');
        $employeeId = $employee instanceof Employee ? $employee->id : $employee;
        return [
            'employee_code' => [
                'sometimes',
                'required',
                'string',
                'max:50',
                Rule::unique('employees', 'employee_code')->ignore($employeeId),
            ],
            'first_name' => [
                'sometimes', 'required', 'string', 'max:100',
            ],
            'last_name' => [
                'sometimes', 'required', 'string', 'max:100',
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('employees', 'email')->ignore($employeeId),
            ],
            'phone' => ['sometimes', 'nullable', 'string', 'max:20'],
            'designation' => ['sometimes', 'required', 'string', 'max:100'],
            'salary' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'joining_date' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
