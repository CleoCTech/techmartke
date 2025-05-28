<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DepartmentRequestStatusNotification;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\DepartmentActivity;
use App\Models\DepartmentRequest;
use App\Models\Offering;
use App\Models\System\Service;
use App\Models\Testmony;
use App\Services\UserRoleService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class GeneralController extends Controller
{

    public function __construct()
    {
        $this->middleware('gen-auth')->only('dashboard');
        $this->middleware('admin-auth')->only('dashboard');
    }
    public function dashboard()
    {
        // info('here');
        return Inertia::render('Admin/Pages/Dashboard');
    }
    public function deptDashboard()
    {
        // info('here');
        return Inertia::render('Admin/Pages/Dashboard');
    }
    public function branchDashboard()
    {
        // info('here');
        return Inertia::render('Admin/Pages/BranchDashboard');
    }
    public function guestDashboard()
    {
        // info('here');
        return Inertia::render('Admin/Pages/Dashboard');
    }

    public function getRecentReports()
    {
        $limit = 2; // Number of recent items to fetch for each category

        $user = Auth::user();
        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {
            $services = Service::latest()->where('branch_id', $user->branch_id)->take($limit)->get()->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'date' =>  Carbon::parse($service->service_date)->format('M d, Y'),
                    'status' => $service->status,
                ];
            });

            $attendances = Attendance::with(['service'])->latest()->where('branch_id', $user->branch_id)->take($limit)->get()->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'title' => 'Attendance: ' . $attendance->total_attendance,
                    'date' =>  Carbon::parse($attendance->service->service_date)->format('M d, Y'),
                    'status' => 'Completed',
                ];
            });

            $offerings = Offering::with(['service'])->latest()->where('branch_id', $user->branch_id)->take($limit)->get()->map(function ($offering) {
                return [
                    'id' => $offering->id,
                    'title' => 'Offering: ' . ($offering->total_offerings + $offering->total_tithes + $offering->cash_offerings + $offering->online_offerings),
                    'date' =>  Carbon::parse($offering->service->service_date)->format('M d, Y'),
                    'status' => 'Completed',
                ];
            });

            $testimonies = Testmony::latest()->where('branch_id', $user->branch_id)->take($limit)->get()->map(function ($testimony) {
                return [
                    'id' => $testimony->id,
                    'title' => substr($testimony->testimony, 0, 30) . '...',
                    'date' =>  Carbon::parse($testimony->service->service_date)->format('M d, Y'),
                    'status' => $testimony->status,
                ];
            });
            $departmentActivities = [];
            // $departmentActivities = DepartmentActivity::latest()->take($limit)->get()->map(function ($activity) {
            //     return [
            //         'id' => $activity->id,
            //         'title' => $activity->title,
            //         'date' => Carbon::parse($activity->activity_date)->format('M d, Y'),
            //         'status' => $activity->status,
            //     ];
            // });
        } else {
            $services = Service::latest()->take($limit)->get()->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'date' =>  Carbon::parse($service->service_date)->format('M d, Y'),
                    'status' => $service->status,
                ];
            });

            $attendances = Attendance::with(['service'])->latest()->take($limit)->get()->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'title' => 'Attendance: ' . $attendance->total_attendance,
                    'date' =>  Carbon::parse($attendance->service->service_date)->format('M d, Y'),
                    'status' => 'Completed',
                ];
            });

            $offerings = Offering::with(['service'])->latest()->take($limit)->get()->map(function ($offering) {
                return [
                    'id' => $offering->id,
                    'title' => 'Offering: ' . ($offering->total_offerings + $offering->total_tithes + $offering->cash_offerings + $offering->online_offerings),
                    'date' =>  Carbon::parse($offering->service->service_date)->format('M d, Y'),
                    'status' => 'Completed',
                ];
            });

            $testimonies = Testmony::latest()->take($limit)->get()->map(function ($testimony) {
                return [
                    'id' => $testimony->id,
                    'title' => substr($testimony->testimony, 0, 30) . '...',
                    'date' =>  Carbon::parse($testimony->service->service_date)->format('M d, Y'),
                    'status' => $testimony->status,
                ];
            });

            $departmentActivities = DepartmentActivity::latest()->take($limit)->get()->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'title' => $activity->title,
                    'date' => Carbon::parse($activity->activity_date)->format('M d, Y'),
                    'status' => $activity->status,
                ];
            });
        }
        return response()->json([
            [
                'title' => 'Services',
                'items' => $services
            ],
            [
                'title' => 'Attendance',
                'items' => $attendances
            ],
            [
                'title' => 'Offerings',
                'items' => $offerings
            ],
            [
                'title' => 'Testimonies',
                'items' => $testimonies
            ],
            [
                'title' => 'Department Activities',
                'items' => $departmentActivities
            ]
        ]);
    }

    public function getFinancialOverview()
    {
        try {
            $currentYear = now()->year;
            $currentMonth = now()->month;

            $user = Auth::user();

            if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {
                // Get total offerings for current month
                $monthlyTotals = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->where('branch_id', $user->branch_id)
                    ->selectRaw('
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->first();

                // Get monthly trends for the current year
                $monthlyTrends = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->where('branch_id', $user->branch_id)
                    ->selectRaw('
            MONTH(created_at) as month,
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                // Calculate year-to-date totals
                $yearToDate = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->where('branch_id', $user->branch_id)
                    ->selectRaw('
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->first();

                // Get recent transactions
                $recentTransactions = Offering::with(['service', 'branch'])
                    ->where('branch_id', $user->branch_id)
                    ->latest()
                    ->take(5)
                    ->get();
                // ->map(function ($offering) {
                //     return [
                //         'service_id' => $offering->service_id,
                //         'total_offerings' => $offering->total_offerings,
                //         'created_at' => $offering->created_at->format('M d, Y'),
                //         'status' => $offering->status
                //     ];
                // });

                // $recentTransactions = DB::table('offerings')
                //     ->select('service_id', 'total_offerings', 'created_at', 'status')
                //     ->orderBy('created_at', 'desc')
                //     ->limit(5)
                //     ->get();

            } else {
                // Get total offerings for current month
                $monthlyTotals = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->whereMonth('created_at', $currentMonth)
                    ->selectRaw('
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->first();

                // Get monthly trends for the current year
                $monthlyTrends = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->selectRaw('
            MONTH(created_at) as month,
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();

                // Calculate year-to-date totals
                $yearToDate = DB::table('offerings')
                    ->whereYear('created_at', $currentYear)
                    ->selectRaw('
            SUM(total_offerings) as total_offerings,
            SUM(total_tithes) as total_tithes,
            SUM(cash_offerings) as cash_offerings,
            SUM(online_offerings) as online_offerings
        ')
                    ->first();

                // Get recent transactions
                $recentTransactions = Offering::with(['service', 'branch'])
                    ->latest()
                    ->take(5)
                    ->get();
                // ->map(function ($offering) {
                //     return [
                //         'service_id' => $offering->service_id,
                //         'total_offerings' => $offering->total_offerings,
                //         'created_at' => $offering->created_at->format('M d, Y'),
                //         'status' => $offering->status
                //     ];
                // });

                // $recentTransactions = DB::table('offerings')
                //     ->select('service_id', 'total_offerings', 'created_at', 'status')
                //     ->orderBy('created_at', 'desc')
                //     ->limit(5)
                //     ->get();
            }


            return response()->json([
                'currentMonth' => $monthlyTotals,
                'yearToDate' => $yearToDate,
                'monthlyTrends' => $monthlyTrends,
                'recentTransactions' => $recentTransactions
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch financial data'], 500);
        }
    }

    public function getPendingRequests()
    {
        try {
            $pendingRequests = DB::table('department_requests')
                ->join('departments as from_dept', 'department_requests.from_department_id', '=', 'from_dept.id')
                ->join('departments as to_dept', 'department_requests.to_department_id', '=', 'to_dept.id')
                ->select(
                    'department_requests.*',
                    'from_dept.name as from_department_name',
                    'to_dept.name as to_department_name'
                )
                ->where('department_requests.status', 1)
                ->orderBy('department_requests.created_at', 'desc')
                ->get();

            // Get request type statistics
            $typeStats = DB::table('department_requests')
                ->where('status', 1)
                ->select('type', DB::raw('count(*) as count'))
                ->groupBy('type')
                ->get();

            // Get department statistics
            $departmentStats = DB::table('department_requests')
                ->where('status', 1)
                ->select(
                    'to_department_id',
                    DB::raw('count(*) as count')
                )
                ->groupBy('to_department_id')
                ->get();

            return response()->json([
                'requests' => $pendingRequests,
                'typeStats' => $typeStats,
                'departmentStats' => $departmentStats
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch pending requests'], 500);
        }
    }

    public function updateRequestStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:approved,rejected',
                'remarks' => 'nullable|string'
            ]);
         
            $statusCode = $validated['status'] === 'approved' ? 2 : 3;

            $departmentRequest = DepartmentRequest::findOrFail($id);
            $departmentRequest->update([
                'status' => $statusCode,
                'remarks' => $validated['remarks'],
                'updated_at' => now(),
                'updated_by' => Auth::user()->email,
            ]);
                // Send email notification to the user who made the request
            Mail::to($departmentRequest->created_by)->send(new DepartmentRequestStatusNotification($departmentRequest, $statusCode, $validated['remarks']));

            return response()->json(['message' => 'Request status updated successfully']);
        } catch (\Exception $e) {
            info(sprintf('Error: %s', $e->getMessage()));
            return response()->json(['error' => 'Failed to update request status'], 500);
        }
    }


    public function overview($id)
    {
        try {
            $department = Department::with(['heads.user', 'activities', 'goals'])
                ->findOrFail($id);

            $statistics = [
                'activeMembersCount' => $department->active_members_count,
                'activeGoalsCount' => $department->active_goals_count,
                'pendingRequestsCount' => $department->pending_requests_count,
                'goalsProgress' => $department->goals_progress
            ];

            $heads = $department->heads()
                ->with('user')
                ->active()
                ->get();

            $recentActivities = $department->activities()
                ->latest()
                ->take(5)
                ->get();

            $activeGoals = $department->goals()
                ->active()
                ->take(5)
                ->get();

            return response()->json([
                'department' => $department,
                'statistics' => $statistics,
                'heads' => $heads,
                'recentActivities' => $recentActivities,
                'activeGoals' => $activeGoals
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch department overview'], 500);
        }
    }
}
