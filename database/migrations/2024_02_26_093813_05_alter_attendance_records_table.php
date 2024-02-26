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
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->foreign('employee_id', 'fk_attendance_records_employees_id_employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('schedule_id', 'fk_attendance_records_schedules_id_schedule_id')->references('id')->on('schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_records', function (Blueprint $table) {
            $table->dropForeign('fk_attendance_records_employees_id_employee_id');
            $table->dropForeign('fk_attendance_records_schedules_id_schedule_id');
        });
    }
};
