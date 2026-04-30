<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bills', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('meter_reading_id')->constrained()->cascadeOnDelete();
            $table->date('billing_month');
            $table->unsignedInteger('usage_m3');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['unpaid', 'partial', 'paid'])->default('unpaid');
            $table->date('due_date');
            $table->timestamps();
            $table->unique(['customer_id', 'billing_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
