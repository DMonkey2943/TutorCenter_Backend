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
        Schema::create('class_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('class_id');
            $table->string('day');
            $table->time('start', precision: 0)->nullable();
            $table->time('end', precision: 0)->nullable();

            $table->foreign('class_id')->references('id')->on('classes')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_times', function (Blueprint $table) {
            $table->dropForeign(['class_id']); // Xóa khóa ngoại
        });

        Schema::dropIfExists('class_times');
    }
};
