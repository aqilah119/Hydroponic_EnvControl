<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plants', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('category')->default('leafy');

            // 🌱 parameter ranges
            $table->float('ph_min')->nullable();
            $table->float('ph_max')->nullable();

            $table->float('temp_min')->nullable();
            $table->float('temp_max')->nullable();

            $table->float('tds_min')->nullable();
            $table->float('tds_max')->nullable();

            $table->float('water_min')->nullable();

            // 🔥 status
            $table->enum('status', ['active', 'pending'])->default('pending');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plants');
    }
};