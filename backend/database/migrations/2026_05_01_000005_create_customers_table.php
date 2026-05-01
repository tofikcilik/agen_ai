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
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('address_rt_rw', 30)->nullable();
            $table->text('address_detail')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('meter_number')->nullable()->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->decimal('tariff_per_m3', 12, 2)->default(3500);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
