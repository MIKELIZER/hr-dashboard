<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'start_time', 'end_time', 'late_tolerance'];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'schedule_id');
    }
}
