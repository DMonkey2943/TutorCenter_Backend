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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tutor_id')->nullable();
            $table->unsignedInteger('class_id')->nullable();
            $table->string('content')->nullable();
            $table->tinyInteger('status')->comment('0: pending, 1: ok, -1: fail');
            $table->string('response')->nullable();
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('SET NULL');
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']); // Xóa khóa ngoại
            $table->dropForeign(['class_id']);
        });

        Schema::dropIfExists('reports');
    }
};
