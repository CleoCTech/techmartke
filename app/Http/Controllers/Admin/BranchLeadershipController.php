<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Models\ChurchBranchLeadership;
use App\Models\Designation;
use App\Models\FiscalPeriod;
use App\Models\User;

class BranchLeadershipController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\ChurchBranchLeadership',
        'caption' => "Branch Leadership",
        'xFolder' => "Admin/Pages/BranchLeadership",
        'indexRoute' => "/admin/branch-leadership",
        'storageName' => "branch_leadership",
        'isList' => true,
        'isCreate' => true,
        'isView' => true,
        'isEdit' => true,
        'isDelete' => false,
        'isActions' => true,
        'isAttachments' => false,
        'isReports' => false,
        'isCharts' => false,
        'isSearch' => true,
        'isReltionship' => true,
        'relationName' => "user,branch,designation,fiscalPeriod",
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
        // $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['statuses'] = [
            ['id' => 'active', 'caption' => 'Active'],
            ['id' => 'inactive', 'caption' => 'Inactive']
        ];
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
            'user_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'designation_id' => 'required|exists:designations,id',
            'fiscal_period_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'user_id' => $request->user_id,
                'branch_id' => $request->branch_id,
                'designation_id' => $request->designation_id,
                'fiscal_period_id' => $request->fiscal_period_id,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            //get current fiscal period
            // $fiscalPeriod = FiscalPeriod::find($request->fiscal_period_id);
            // Assign Permissions to the User
            $user = User::find($request->user_id);
            $leadership =ChurchBranchLeadership::where('user_id', $request->user_id)
            ->where('fiscal_period_id', $request->fiscal_period_id)
            ->with(['designation' => function ($query) {
                $query->whereNull('deleted_at'); // Ignore deleted designations
            }])
            ->first();
            
            if ($leadership && $leadership->designation) {
                $designationSlug = $leadership->designation->slug;

                if ($designationSlug === 'senior_pastor' || $designationSlug === 'church_administrator' || $designationSlug === 'church_secretary' || $designationSlug === 'church_treasurer' || $designationSlug === 'department_head' || $designationSlug === 'event_organizer') {
                    // Allow access to specific features
                    if ($user) {
                        $permissions = ['managechurchtestimonies', 'managechurchoffering', 'managechurchattendance', 'managechurchservice', 'manageevents', 'managegallery'];
                        $user->syncPermissions($permissions);
                    }
                }
            }
            

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
        $branchLeadership = $this->defaultModel::where('uuid', $uuid)->first();
        $branchLeadership->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }
    private function loadFormData()
    {
        $this->viewData['setup']['branches'] = Branch::all();
        $this->viewData['setup']['users'] = User::all();
        $this->viewData['setup']['designations'] = Designation::all();
        $this->viewData['setup']['fiscal_periods'] = FiscalPeriod::where('status', 'active')->get();
    }
}