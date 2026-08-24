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
    Schema::create('sensor_logs', function (Blueprint $table) {
        $table->id();
        $table->timestamp('timestamp');
        $table->float('DHT_temp')->nullable();
        $table->float('tds')->nullable();
        $table->float('water_level')->nullable();
        $table->float('ph')->nullable();
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('sensor_logs');
}
};
