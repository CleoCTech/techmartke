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
use App\Models\DepartmentHead;
use App\Models\DepartmentActivity;
use App\Models\DepartmentGoal;
use App\Models\User;
use App\Models\Department;
use App\Models\FiscalPeriod;
use App\Models\Designation;
use Illuminate\Validation\Rule;

class DepartmentHeadController extends Controller
{
    use LayoutTrait, UploadFileTrait;

    public $settings = [
        'model' => '\App\Models\DepartmentHead',
        'caption' => 'Department Head',
        'xFolder' => 'Admin/Pages/DepartmentHead',
        'indexRoute' => '/admin/department-head',
        'storageName' => 'department_head',
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
        'relationName' => 'user,department,fiscalPeriod,designation',
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

        // $user = Auth::user();
        // Apply the policy authorization via middleware
        $this->middleware(function ($request, $next) {
            $this->authorize('access', DepartmentHead::class);
            return $next($request);
        });

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
        // $this->validate($request, [
        //     'user_id' => [
        //         'required',
        //         'exists:users,id',
        //         Rule::unique('department_heads')->where(function ($query) use ($request) {
        //             return $query->where('fiscal_period_id', $request->fiscal_period_id);
        //         }),
        //     ],
        //     'department_id' => [
        //         'required',
        //         'exists:departments,id',
        //         Rule::unique('department_heads')->where(function ($query) use ($request) {
        //             return $query->where('fiscal_period_id', $request->fiscal_period_id);
        //         }),
        //     ],
        //     'designation_id' => 'required|exists:designations,id',
        //     'fiscal_period_id' => 'required|exists:fiscal_periods,id',
        // ]);
        $this->validate($request, [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'department_id' => [
                'required',
                'exists:departments,id',
                Rule::unique('department_heads')->where(function ($query) use ($request) {
                    return $query->where('fiscal_period_id', $request->fiscal_period_id);
                }),
            ],
            'designation_id' => 'required|exists:designations,id',
            'fiscal_period_id' => 'required|exists:fiscal_periods,id',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'user_id' => $request->user_id,
                'department_id' => $request->department_id,
                'designation_id' => $request->designation_id,
                'fiscal_period_id' => $request->fiscal_period_id,
            ];

            // if ($this->pKey == null) {
            //     $record['created_by'] = Auth::user()->email;
            // } else {
            //     $record['updated_by'] = Auth::user()->email;
            // }

            $this->defaultModel::updateOrCreate(['uuid' => $this->pKey], $record);

            // Assign Permissions to the User
            $user = User::find($request->user_id);
            $departmentHead = DepartmentHead::where('user_id',  $request->user_id)
                ->where('department_id', $request->department_id)
                ->where('fiscal_period_id', $request->fiscal_period_id)
                ->first();

            if($departmentHead) {
                $designationSlug = $departmentHead->designation->slug;
                if( $designationSlug === 'department_head') {
                    if ($user) {
                        $permissions = ['managedepartment'];
                        $user->syncPermissions($permissions);
                    }
                }
            }

            DB::commit();
            // $response = $this->notification('success');
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
        $this->viewData['setup']['users'] = User::all();
        $this->viewData['setup']['departments'] = Department::all();
        $this->viewData['setup']['designations'] = Designation::all();
        $this->viewData['setup']['fiscal_periods'] = FiscalPeriod::where('status', 'active')->get();
    }
}
