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
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->time('timeslot_1_start')->nullable();
            $table->time('timeslot_1_end')->nullable();
            $table->time('timeslot_2_start')->nullable();
            $table->time('timeslot_2_end')->nullable();
            $table->time('timeslot_3_start')->nullable();
            $table->time('timeslot_3_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'timeslot_1_start', 'timeslot_1_end',
                'timeslot_2_start', 'timeslot_2_end',
                'timeslot_3_start', 'timeslot_3_end'
            ]);
        });
    }
};
