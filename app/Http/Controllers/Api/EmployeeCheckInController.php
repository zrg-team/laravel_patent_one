
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\EmployeeService;
use App\Http\Resources\EmployeeCheckInResource;
use App\Exceptions\EmployeeCheckInException;

class EmployeeCheckInController extends Controller
{
    protected EmployeeService $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    public function checkIn(Request $request)
    {
        try {
            $badgeId = $request->input('badge_id');
            $checkInResult = $this->employeeService->checkInEmployee($badgeId);
            return new EmployeeCheckInResource($checkInResult);
        } catch (EmployeeCheckInException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }
    }
}
