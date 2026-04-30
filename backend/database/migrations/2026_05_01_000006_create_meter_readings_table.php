<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('recorded_by')->constrained('users');
            $table->date('reading_month');
            $table->unsignedInteger('previous_value');
            $table->unsignedInteger('current_value');
            $table->unsignedInteger('usage_m3');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['customer_id', 'reading_month']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
