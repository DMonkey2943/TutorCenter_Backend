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
        Schema::create('approve', function (Blueprint $table) {
            $table->unsignedInteger('class_id');
            $table->unsignedInteger('tutor_id');
            $table->tinyInteger('status');
            $table->timestamps();

            $table->primary(['class_id', 'tutor_id']);
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('CASCADE');
            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('approve', function (Blueprint $table) {
            $table->dropForeign(['class_id']); // Xóa khóa ngoại
            $table->dropForeign(['tutor_id']);
        });

        Schema::dropIfExists('approve');
    }
};
