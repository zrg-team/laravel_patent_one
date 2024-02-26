
<?php

use App\Http\Controllers\Api\HealthCheckController;
use App\Http\Controllers\Api\EmployeeCheckInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::any('/health-check', [HealthCheckController::class, 'index'])->name('health-check');
Route::post('/api/attendance/check-in/error', [EmployeeCheckInController::class, 'reportCheckInError'])
    ->middleware('auth:api')->name('attendance.check-in.error');
