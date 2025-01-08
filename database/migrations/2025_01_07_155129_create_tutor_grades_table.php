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
        Schema::create('tutor_grades', function (Blueprint $table) {
            $table->unsignedInteger('tutor_id');
            $table->unsignedTinyInteger('grade_id');

            // $table->primary(['class_id', 'tutor_id']);
            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('CASCADE');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_grades', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']); // Xóa khóa ngoại
            $table->dropForeign(['grade_id']);
        });

        Schema::dropIfExists('tutor_grades');
    }
};
