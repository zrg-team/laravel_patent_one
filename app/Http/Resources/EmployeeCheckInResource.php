
<?php

namespace App\Http\Resources;

use App\Models\AttendanceRecord;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCheckInResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attendanceRecord = $this->resource['attendance_record'] ?? null;
        return [
            'status' => $this->resource['status'],
            'message' => $this->resource['message'],
            'attendance_record' => $attendanceRecord ? [
                'employee_id' => $attendanceRecord->employee_id,
                'check_in_time' => $attendanceRecord->check_in_time->toIso8601String(),
                'date' => $attendanceRecord->date->toDateString(),
                'status' => $attendanceRecord->status,
            ] : null,
        ];
    }
}
