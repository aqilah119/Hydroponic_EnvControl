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
    Schema::table('staff', function (Blueprint $table) {

        $table->string('gender')->nullable();

        $table->string('assigned_section')->nullable();

        $table->string('status')
              ->default('Active');

        $table->string('phone_number')
              ->nullable();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('staff', function (Blueprint $table) {

        $table->dropColumn([
            'gender',
            'assigned_section',
            'status',
            'phone_number'
        ]);

    });
}
};
