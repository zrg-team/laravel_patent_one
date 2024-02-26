
<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EmployeeCheckInErrorRequest;
use App\Http\Resources\SuccessResource;
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

    public function reportCheckInError(EmployeeCheckInErrorRequest $request)
    {
        try {
            $checkInErrorResult = $this->employeeService->handleCheckInError($request->employee_id, $request->message);
            return new SuccessResource("Your issue has been reported and will be addressed by your supervisor.");
        } catch (EmployeeCheckInException $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        } catch (\Exception $e) {
            return response()->json(['message' => 'An unexpected error has occurred on the server.'], 500);
        }
    }
}
