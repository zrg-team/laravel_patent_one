<?php

namespace App\Models;

use App\Models\Traits\FilterQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    use FilterQueryBuilder;

    protected $table = 'schedules';

    protected $fillable = [
        'work_date',
        'start_time',
        'end_time',
        'employee_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'work_date' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function attendanceRecord(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(AttendanceRecord::class, 'schedule_id');
    }
}
