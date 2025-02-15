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
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_classroom_id');
            $table->unsignedBigInteger('scoreable_id');
            $table->string('scoreable_type');
            $table->integer('point');
            $table->timestamps();

            $table->foreign('student_classroom_id')->references('id')->on('student_classroom')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
