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
        Schema::create('tutors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->char('gender', 1)->comment('M: male, F: female')->nullable();
            $table->date('birthday')->nullable();
            $table->string('address');
            $table->string('major');
            $table->string('school');
            $table->unsignedTinyInteger('level_id')->nullable();
            $table->unsignedTinyInteger('tuition_id')->nullable();
            $table->text('experiences')->nullable();
            $table->string('avatar')->nullable();
            $table->string('degree')->nullable();
            $table->tinyInteger('profile_status')->comment('0: pending, 1: ok, -1: fail');
            $table->string('profile_reason')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('level_id')->references('id')->on('levels')->onDelete('SET NULL');
            $table->foreign('tuition_id')->references('id')->on('tuitions')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutors', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Xóa khóa ngoại
            $table->dropForeign(['level_id']);
            $table->dropForeign(['tuition_id']);
        });

        Schema::dropIfExists('tutors');
    }
};
