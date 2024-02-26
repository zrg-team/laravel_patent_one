<?php

namespace App\Services;

use App\Exceptions\EmployeeCheckInException;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\AttendanceRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Exception;

class EmployeeService
{
    public function checkInEmployee(string $badge_id)
    {
        $employee = Employee::where('badge_id', $badge_id)->first();

        if (!$employee) {
            // Using EmployeeCheckInException for consistency with new code
            throw new EmployeeCheckInException('Employee identity cannot be verified.', 400);
        }

        $today = Carbon::today();
        $schedule = Schedule::where('employee_id', $employee->id)
                            ->where('work_date', $today)
                            ->first();

        if (!$schedule) {
            // Using EmployeeCheckInException for consistency with new code
            throw new EmployeeCheckInException('Employee is not scheduled for the current day.', 403);
        }

        $attendanceRecord = AttendanceRecord::where('employee_id', $employee->id)
                                            ->where('date', $today)
                                            ->first();

        if ($attendanceRecord) {
            // Using EmployeeCheckInException for consistency with new code
            throw new EmployeeCheckInException('Employee has already checked in.', 403);
        }

        AttendanceRecord::create([
            'employee_id' => $employee->id,
            'check_in_time' => Carbon::now(),
            'date' => $today,
            'status' => 'present'
        ]);

        // Returning array for consistency with new code
        return [
            'status' => 200,
            'message' => 'Check-in successful.',
            'attendance_record' => [
                'employee_id' => $employee->id,
                'check_in_time' => Carbon::now()->toIso8601String(),
                'date' => $today->toDateString(),
                'status' => 'present'
            ]
        ];
    }

    public function reportCheckInError(int $employee_id, string $message): array
    {
        if (trim($message) === '') {
            throw new Exception("A message is required to report the issue.");
        }

        $employee = Employee::find($employee_id);

        if (!$employee) {
            throw new NotFoundHttpException("Employee not found.");
        }

        $supervisor = $employee->supervisor()->first();

        if (!$supervisor) {
            throw new NotFoundHttpException("Supervisor not found.");
        }

        // Log the error message along with the employee and supervisor details
        Log::error("Check-in error reported by Employee ID: {$employee_id}, Message: {$message}, Supervisor: {$supervisor->name}");

        return ['status' => 200, 'message' => "Your issue has been reported and will be addressed by your supervisor."];
    }

    public function handleCheckInError(string $badge_id): array
    {
        try {
            $employee = Employee::where('badge_id', $badge_id)->first();

            if (!$employee) {
                throw new NotFoundHttpException("Employee not found.");
            }

            $supervisor = $employee->supervisor()->first();

            if (!$supervisor) {
                throw new NotFoundHttpException("Supervisor not found.");
            }

            // Log the incident for further investigation
            Log::error("Check-in error for employee ID: {$employee->id}, Badge ID: {$badge_id}");

            // Return supervisor's contact information and instructions
            return [
                'supervisor_contact' => $supervisor->name,
                'message' => 'Please contact your supervisor for assistance.'
            ];
        } catch (Exception $e) {
            Log::error("An error occurred during check-in: " . $e->getMessage());
            throw $e;
        }
    }
}
