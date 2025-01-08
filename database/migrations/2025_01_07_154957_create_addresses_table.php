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
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('detail');
            $table->unsignedTinyInteger('ward_id')->nullable();

            $table->foreign('ward_id')->references('id')->on('wards')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropForeign(['ward_id']); // Xóa khóa ngoại
        });

        Schema::dropIfExists('addresses');
    }
};
