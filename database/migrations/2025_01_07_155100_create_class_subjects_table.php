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
        Schema::create('class_subjects', function (Blueprint $table) {
            $table->unsignedInteger('class_id');
            $table->unsignedTinyInteger('subject_id');

            // $table->primary(['class_id', 'tutor_id']);
            $table->foreign('class_id')->references('id')->on('classes')->onDelete('CASCADE');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_subjects', function (Blueprint $table) {
            $table->dropForeign(['class_id']); // Xóa khóa ngoại
            $table->dropForeign(['subject_id']);
        });

        Schema::dropIfExists('class_subjects');
    }
};
