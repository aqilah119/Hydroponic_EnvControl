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

            // tambah column user_id
            $table->unsignedBigInteger('user_id')->nullable()->after('name');

            // foreign key
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {

            // buang foreign key dulu
            $table->dropForeign(['user_id']);

            // buang column
            $table->dropColumn('user_id');
        });
    }
};