<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\DepartmentRequestNotification;
use App\Models\DepartmentRequest;
use App\Models\Department;
use App\Models\DepartmentHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class DepartmentRequestController extends Controller
{
    use LayoutTrait, UploadFileTrait;

    public $settings = [
        'model' => '\App\Models\DepartmentRequest',
        'caption' => 'Department Request',
        'xFolder' => 'Admin/Pages/DepartmentRequest',
        'indexRoute' => '/admin/department-request',
        'storageName' => 'department_request',
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
        'relationName' => 'fromDepartment,toDepartment',
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
            'from_department_id' => 'required|exists:departments,id',
            'to_department_id' => 'required|exists:departments,id',
            'type' => 'required|in:resource,collaboration,budget,approval,information',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'source' => 'required|in:crud,modal',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'from_department_id' => $request->from_department_id,
                'to_department_id' => $request->to_department_id,
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $departmentRequest =$this->defaultModel::updateOrCreate(['uuid' => $this->pKey], $record);
            // ✅ Send Email Notification to Department Head
            $departmentHead = DepartmentHead::where('department_id', $request->to_department_id)->first();
            
            if ($departmentHead && $departmentHead->user) {
                Mail::to($departmentHead->user->email)->send(new DepartmentRequestNotification($departmentRequest));
            }
            DB::commit();
            
            if ($request->source == 'crud') {
                $response = $this->notification('success');
                return response()->json($response, 200);
            }
            if ($request->source == 'modal') {
                return response()->json([
                    'message' => 'Department request created successfully',
                    'request' => $departmentRequest
                ], 201);
            }
           
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

    public function getDepartments()
    {
        $departments = Department::select('id', 'name')->get();
        return response()->json($departments);
    }

    private function loadFormData()
    {
        $this->viewData['setup']['departments'] = Department::all();
    }
}
