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
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parent_id');
            $table->enum('num_of_students', ['1', '2', '3', '4', '5']);
            $table->enum('num_of_sessions', ['1', '2', '3', '4', '5', '6', '7']);
            // $table->text('time');
            $table->unsignedTinyInteger('grade_id');
            $table->unsignedInteger('address_id');
            $table->char('gender_tutor', 1)->nullable()->comment('M: male, F: female');
            $table->unsignedTinyInteger('level_id')->nullable();
            $table->string('tuition');
            $table->text('request')->nullable();
            $table->tinyInteger('status')->comment('0: pending, 1: ok, -1: fail');
            $table->unsignedInteger('tutor_id')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', precision: 0);

            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('CASCADE');
            $table->foreign('grade_id')->references('id')->on('grades')->onDelete('CASCADE');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('SET NULL');
            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('SET NULL');
            $table->foreign('parent_id')->references('id')->on('parents')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropForeign(['address_id']); // Xóa khóa ngoại
            $table->dropForeign(['grade_id']);
            $table->dropForeign(['level_id']);
            $table->dropForeign(['tutor_id']);
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('classes');
    }
};
