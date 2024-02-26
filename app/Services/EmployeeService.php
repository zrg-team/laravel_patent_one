
<?php

namespace App\Services;

use App\Models\Employee;
use App\Models\Schedule;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Exception;

class EmployeeService
{
    public function checkInEmployee(string $badge_id)
    {
        $employee = Employee::where('badge_id', $badge_id)->first();

        if (!$employee) {
            throw new Exception('Employee identity cannot be verified.');
        }

        $today = Carbon::today();
        $schedule = Schedule::where('employee_id', $employee->id)
                            ->where('work_date', $today)
                            ->first();

        if (!$schedule) {
            return 'Employee is not scheduled for the current day.';
        }

        $attendanceRecord = AttendanceRecord::where('employee_id', $employee->id)
                                            ->where('date', $today)
                                            ->first();

        if ($attendanceRecord) {
            return 'Employee has already checked in.';
        }

        AttendanceRecord::create([
            'employee_id' => $employee->id,
            'check_in_time' => Carbon::now(),
            'date' => $today,
            'status' => 'present'
        ]);

        return 'Check-in successful.';
    }
}
