<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'designation',
        'salary',
        'joining_date'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'joining_date' => 'date',
    ];
}
