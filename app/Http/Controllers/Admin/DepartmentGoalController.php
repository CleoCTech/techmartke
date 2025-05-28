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
use App\Models\DepartmentGoal;
use App\Models\Department;
use App\Models\FiscalPeriod;

class DepartmentGoalController extends Controller
{
    use LayoutTrait, UploadFileTrait;

    public $settings = [
        'model' => '\App\Models\DepartmentGoal',
        'caption' => 'Department Goal',
        'xFolder' => 'Admin/Pages/DepartmentGoal',
        'indexRoute' => '/admin/department-goal',
        'storageName' => 'department_goal',
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
        'relationName' => 'department,fiscalPeriod',
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
        $this->loadFormData();
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'department_id' => 'required|exists:departments,id',
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'target_date' => 'required|date',
            'status' => 'required|in:1,2,3', // 1=PENDING, 2=COMPLETED, 3=IN PROGRESS
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'department_id' => $request->department_id,
                'fiscal_period_id' => $request->fiscal_period_id,
                'title' => $request->title,
                'description' => $request->description,
                'target_date' => $request->target_date,
                'status' => $request->status,
                'remarks' => $request->remarks,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $this->defaultModel::updateOrCreate(['uuid' => $this->pKey], $record);

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
        $this->loadFormData();
        return Inertia::render($this->settings['xFolder'] . '/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        $this->loadFormData();
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $record = $this->defaultModel::where('uuid', $uuid)->first();
        $record->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }

    private function loadFormData()
    {
        $this->viewData['setup']['departments'] = Department::all();
        $this->viewData['setup']['fiscal_periods'] = FiscalPeriod::where('status', 'active')->get();
    }
}
