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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->string('mobile');
            $table->string('address');
            $table->string('photo')->nullable();
            $table->string('id_proof')->nullable();
            $table->json('courses');
            $table->string('purpose');
            $table->time('timeslot_start');
            $table->time('timeslot_end');
            $table->date('joining_date');
            $table->unsignedBigInteger('seat_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('seat_id')->references('id')->on('seats')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
