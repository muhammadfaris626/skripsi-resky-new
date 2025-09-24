<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('date');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('total_amount');
            $table->string('payment_method');
            $table->timestamps();
            $table->index('invoice_number');
            $table->index('date');
            $table->index('employee_id');
            $table->index('total_amount');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
