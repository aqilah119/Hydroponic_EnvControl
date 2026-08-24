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
    Schema::table('sensor_logs', function (Blueprint $table) {
        $table->boolean('add_water')->nullable();
        $table->boolean('ph_reducer')->nullable();
        $table->boolean('ex_fan')->nullable();
        $table->boolean('nutrient_adder')->nullable();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sensor_logs', function (Blueprint $table) {
            //
        });
    }
};
