<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'month', 'year', 'base_salary', 
        'allowance', 'deduction', 'total_salary', 'notes', 'status'
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    protected static function booted()
    {
        static::saving(function ($salary) {
            $salary->total_salary = $salary->base_salary + $salary->allowance - $salary->deduction;
        });
    }
}
