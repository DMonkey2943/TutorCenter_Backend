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
        Schema::table('tutors', function (Blueprint $table) {
            $table->string('address')->nullable()->change();
            $table->string('major')->nullable()->change();
            $table->string('school')->nullable()->change();
            $table->tinyInteger('profile_status')->nullable()->comment('0: pending, 1: ok, -1: fail')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->string('address')->nullable(false)->change();
            $table->string('major')->nullable(false)->change();
            $table->string('school')->nullable(false)->change();
            $table->tinyInteger('profile_status')->nullable(false)->comment('0: pending, 1: ok, -1: fail')->change();
        });
    }
};
