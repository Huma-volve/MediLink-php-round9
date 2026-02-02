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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')
                ->nullable()
                ->constrained('appointments')
                ->onDelete('cascade');
            $table->foreignId('patient_id')
                ->constrained('patients')
                ->onDelete('cascade');
            $table->unsignedBigInteger('patient_insurance_id')->nullable();
            $table->foreign('patient_insurance_id')
                ->references('id')
                ->on('patient_insurances')
                ->onDelete('cascade');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', ['credit_card', 'debit_card', 'bank_transfer', 'cash', 'insurance', 'wallet', 'stripe'])->nullable();
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'refunded', 'cancelled', 'partial'])->default('pending');
            $table->string('transaction_id')->nullable()->unique();
            $table->string('currency')->default('EGP');
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
