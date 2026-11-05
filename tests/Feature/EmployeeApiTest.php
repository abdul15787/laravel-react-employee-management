<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
   use refreshDatabase;

   public function test_employee_can_be_listed(): void
   {
    Employee::factory()->count(3)->create();
    $response = $this->getJson('/api/employees');
    $response->assertOk()
    ->assertJsonCount(3, 'data');

   }

   public function test_a_single_employee_can_be_viewed(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson(
            '/api/employees/' . $employee->id
        );

        $response
            ->assertOk()
            ->assertJsonPath('data.id', $employee->id)
            ->assertJsonPath(
                'data.full_name',
                $employee->first_name . ' ' . $employee->last_name
            );
    }

    public function test_nonexistent_employee_returns_not_found(): void
    {
        $this->getJson('/api/employees/999999')
            ->assertNotFound();
    }

    public function test_required_employee_fields_are_validated(): void
    {
        $this->postJson('/api/employees', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'employee_code',
                'first_name',
                'last_name',
                'email',
                'designation',
            ]);
    }

    public function test_an_employee_can_be_created(): void
    {
        $payload = [
            'employee_code' => 'EMP100',
            'first_name' => 'Rahul',
            'last_name' => 'Sharma',
            'email' => 'rahul100@example.com',
            'designation' => 'Software Developer',
            'salary' => 65000,
            'joining_date' => '2026-10-01',
        ];

        $response = $this->postJson('/api/employees', $payload);

        $response
            ->assertCreated()
            ->assertJsonPath('data.employee_code', 'EMP100');

        $this->assertDatabaseHas('employees', [
            'employee_code' => 'EMP100',
            'email' => 'rahul100@example.com',
        ]);
    }

    public function test_duplicate_employee_email_is_rejected(): void
    {
        $employee = Employee::factory()->create();

        $payload = [
            'employee_code' => 'EMP101',
            'first_name' => 'Amit',
            'last_name' => 'Kumar',
            'email' => $employee->email,
            'designation' => 'Developer',
        ];

        $this->postJson('/api/employees', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_duplicate_employee_code_is_rejected(): void
    {
        $employee = Employee::factory()->create();

        $payload = [
            'employee_code' => $employee->employee_code,
            'first_name' => 'Amit',
            'last_name' => 'Kumar',
            'email' => 'amit.unique@example.com',
            'designation' => 'Developer',
        ];

        $this->postJson('/api/employees', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['employee_code']);
    }

    public function test_an_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();

        $response = $this->patchJson(
            '/api/employees/' . $employee->id,
            ['designation' => 'Senior Developer']
        );

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.designation',
                'Senior Developer'
            );

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'designation' => 'Senior Developer',
        ]);
    }

    public function test_an_employee_can_be_deleted(): void
    {
        $employee = Employee::factory()->create();

        $this->deleteJson('/api/employees/' . $employee->id)
            ->assertOk();

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }
}
