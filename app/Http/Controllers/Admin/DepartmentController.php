<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\Department;
use App\Models\DepartmentGoal;

class DepartmentController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\Department',
        'caption' => "Department",
        'xFolder' => "Admin/Pages/Department",
        'indexRoute' => "/admin/departments",
        'storageName' => "department",
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
        'isReltionship' => false,
        'relationName' => "",
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
        $this->def_index();
        return Inertia::render($this->settings['xFolder'] . '/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'name' => $request->name,
                'description' => $request->description,
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
        return Inertia::render($this->settings['xFolder'] . '/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $department = $this->defaultModel::where('uuid', $uuid)->first();
        $department->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }

    public function list()
    {
        $departments = Department::all();
        return response()->json($departments);
    }

    public function overview($id)
    {
        try {
            $department = Department::findOrFail($id);

            $statistics = [
                'activeMembersCount' => 100,
                'activeGoalsCount' => 100,
                'pendingRequestsCount' => 100,
                'goalsProgress' => 100
            ];

            return response()->json([
                'department' => $department,
                'statistics' => $statistics,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch department overview'], 500);
        }
    }

    public function summary()
{
    try {
        $totalDepartments = Department::count();
        $activeDepartments = Department::where('status', 'active')->count();
        // $totalMembers = DepartmentMember::count();
        $activeGoals = DepartmentGoal::where('status', 'active')->count();

        // $overallProgress = Department::avg('goals_progress');

        return response()->json([
            'totalDepartments' => $totalDepartments,
            'activeDepartments' => $activeDepartments,
            // 'totalMembers' => $totalMembers,
            'activeGoals' => $activeGoals,
            'overallProgress' => 100
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to fetch department summary'], 500);
    }
}

    // public function show($id)
    // {
    //     $department = Department::findOrFail($id);
        
    //     return Inertia::render('Admin/Departments/DepartmentShow', [
    //         'departmentId' => $id,
    //         'departmentName' => $department->name,
    //     ]);
    // }
}