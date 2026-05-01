<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('village_id')->constrained()->cascadeOnDelete();
            $table->string('customer_number')->unique();
            $table->unsignedInteger('customer_sequence');
            $table->string('name');
            $table->string('phone', 30)->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->text('address');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('meter_number')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('tariff_per_m3', 12, 2)->default(3500);
            $table->timestamps();

            $table->unique(['village_id', 'customer_sequence']);
            $table->index(['village_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
