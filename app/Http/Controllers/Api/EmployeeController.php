<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employee::latest()->get();       
        return EmployeeResource::collection($employees);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        $employee = Employee::create($request->validated());
        return (new EmployeeResource($employee))
            ->additional(['message' => 'Employee created successfully.'])
            ->response()
            ->setStatusCode(201);       
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return new EmployeeResource($employee);        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated()); 
        return (new EmployeeResource($employee))
            ->additional(['message' => 'Employee updated successfully.']);
        
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
