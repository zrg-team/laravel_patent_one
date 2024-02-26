<?php

namespace App\Models;

use App\Models\Traits\FilterQueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    use FilterQueryBuilder;

    protected $table = 'employees';

    protected $fillable = [
        'name',
        'badge_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];

    public function attendanceRecords(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AttendanceRecord::class, 'employee_id');
    }

    public function schedules(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Schedule::class, 'employee_id');
    }

    public function supervisor(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Supervisor::class, 'employee_id');
    }
}
