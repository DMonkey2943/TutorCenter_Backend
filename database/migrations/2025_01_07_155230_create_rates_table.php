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
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('tutor_id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('stars');
            $table->string('comment')->nullable();
            $table->timestamps();

            $table->foreign('tutor_id')->references('id')->on('tutors')->onDelete('CASCADE');
            $table->foreign('parent_id')->references('id')->on('parents')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rates', function (Blueprint $table) {
            $table->dropForeign(['tutor_id']); // Xóa khóa ngoại
            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('rates');
    }
};
