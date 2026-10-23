<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $employees = Employee::latest()->get();
        return response()->json([
            'data' => $employees,
            'message' => 'Employee List.'
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = Employee::create($request->validated());
        return response()->json([
            'message' => 'Employee created.',
            'date' => $employee
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee): JsonResponse
    {
        return response()->json(['data' => $employee, 'message' => 'Employee data.']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());  
 
        return response()->json(['message' => 'Employee updated.', 'data' => $employee->refresh()]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee):JsonResponse
    {
        $employee->delete();
        return response()->json(['message' => 'Employee deleted successfully.']);
    }
}
