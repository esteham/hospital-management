<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->cascadeOnDelete();
            $table->enum('day_of_week',['sat','sun','mon','tue','wed','thu','fri']);
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedInteger('slot_minutes');
            $table->unsignedInteger('max_patients_per_day');
            $table->unsignedInteger('fee');
            $table->timestamps();
            $table->unique(['doctor_id','day_of_week']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
