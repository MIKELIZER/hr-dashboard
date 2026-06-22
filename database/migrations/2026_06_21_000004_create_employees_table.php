<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            
            // Foreign Keys with ON DELETE RESTRICT
            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('restrict');
            $table->foreignId('department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('position_id')->constrained('positions')->onDelete('restrict');
            $table->foreignId('schedule_id')->constrained('work_schedules')->onDelete('restrict');
            
            $table->string('employee_code')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('hire_date');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();

            // Indexes
            $table->index(['status', 'department_id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
