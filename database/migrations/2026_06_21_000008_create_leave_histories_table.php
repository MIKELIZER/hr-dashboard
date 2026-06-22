<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leave_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_balance_id')->constrained('leave_balances')->onDelete('cascade');
            $table->foreignId('leave_request_id')->nullable()->constrained('leave_requests')->onDelete('set null');
            
            $table->string('type'); // add, deduct
            $table->integer('amount');
            $table->text('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leave_histories');
    }
};
