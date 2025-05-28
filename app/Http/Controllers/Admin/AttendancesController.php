<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\System\Service;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use App\Services\UserRoleService;

class AttendancesController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\Attendance',
        'caption' => "Attendance",
        'xFolder' => "Admin/Pages/Attendance",
        'indexRoute' => "/admin/attendance",
        'storageName' => "attendance",
        'isList' => true,
        'isCreate' => true,
        'isView' => true,
        'isEdit' => true,
        'isDelete' => true,
        'isActions' => true,
        'isAttachments' => false,
        'isReports' => false,
        'isCharts' => false,
        'isSearch' => true,
        'isReltionship' => true,
        'relationName' => "service",
        'isSelect' => true,
        'isMoreActions' => false,
        'xMoreActions' => null,
        'isExport' => false,
        'orderBy' => ['column' => 'created_at', 'order' => 'DESC'],
    ];

    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
        $this->defaultModel = $this->settings['model'];
        $this->isReltionship = $this->settings['isReltionship'];
        $this->relationName = $this->settings['relationName'];
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }

    public function index()
    {
        $user = Auth::user();

        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

            $this->extraConditions = [
                // ['column' => 'status', 'operator' => '=', 'value' => 'active'], // Example: Only active records
                ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user’s branch
            ];
        }


        $this->def_index();
        return Inertia::render($this->settings['xFolder'] . '/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        // $services = Service::all();
        $services = Service::where('branch_id', auth()->user()->branch_id)->get();

        // Add services to viewData
        $this->viewData['services'] = $services;
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'service_id' => 'required|exists:services,id',
            'total_attendance' => 'required|integer',
            'adults' => 'nullable|integer',
            'children' => 'nullable|integer',
            'visitors' => 'nullable|integer',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'service_id' => $request->service_id,
                'branch_id' => Auth::user()->branch_id,
                'total_attendance' => $request->total_attendance,
                'adults' => $request->adults,
                'children' => $request->children,
                'visitors' => $request->visitors,
                'remarks' => $request->remarks,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            DB::commit();
            $response = $this->notification('success');
            return response()->json($response, 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            $response = $this->notification('error');
            return response()->json($response, 500);
        }
    }

    public function show($uuid)
    {
        $this->def_show($uuid);
        $services = Service::all();

        // Add services to viewData
        $this->viewData['services'] = $services;
        return Inertia::render($this->settings['xFolder'] . '/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        $services = Service::all();

        // Add services to viewData
        $this->viewData['services'] = $services;
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $record = $this->defaultModel::where('uuid', $uuid)->first();
        $record->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }

    public function getTrends(Request $request)
    {
        $timeRange = $request->input('timeRange', 'month');

        // Calculate the start date based on time range
        $startDate = match ($timeRange) {
            'week' => Carbon::now()->subWeek(),
            'month' => Carbon::now()->subMonth(),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subMonth()
        };
        $user = Auth::user();
        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {
            // Get attendance records grouped by date
            $records = Attendance::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_attendance) as total'),
                DB::raw('SUM(adults) as adults'),
                DB::raw('SUM(children) as children'),
                DB::raw('SUM(visitors) as visitors')
            ])
                ->where('created_at', '>=', $startDate)
                ->where('branch_id', $user->branch_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Prepare data for chart
            $dates = $records->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            });
        } else {
            // Get attendance records grouped by date
            $records = Attendance::select([
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_attendance) as total'),
                DB::raw('SUM(adults) as adults'),
                DB::raw('SUM(children) as children'),
                DB::raw('SUM(visitors) as visitors')
            ])
                ->where('created_at', '>=', $startDate)
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Prepare data for chart
            $dates = $records->pluck('date')->map(function ($date) {
                return Carbon::parse($date)->format('M d');
            });
        }



        return response()->json([
            'dates' => $dates,
            'totals' => $records->pluck('total'),
            'adults' => $records->pluck('adults'),
            'children' => $records->pluck('children'),
            'visitors' => $records->pluck('visitors')
        ]);
    }
}
