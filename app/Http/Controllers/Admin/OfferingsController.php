<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\System\Service;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OfferingsController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\Offering',
        'caption' => "Offering",
        'xFolder' => "Admin/Pages/Offering",
        'indexRoute' => "/admin/offering",
        'storageName' => "offering",
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
        $this->def_index();
        return Inertia::render($this->settings['xFolder'] . '/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        $services = Service::all();

        // Add services to viewData
        $this->viewData['services'] = $services;
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'service_id' => 'required|exists:services,id',
            'total_offerings' => 'required|numeric',
            'total_tithes' => 'required|numeric',
            'cash_offerings' => 'nullable|numeric',
            'online_offerings' => 'nullable|numeric',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'service_id' => $request->service_id,
                'branch_id' => Auth::user()->branch_id,
                'total_offerings' => $request->total_offerings,
                'total_tithes' => $request->total_tithes,
                'cash_offerings' => $request->cash_offerings,
                'online_offerings' => $request->online_offerings,
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
        $user = Auth::user();

        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

            $this->extraConditions = [
                // ['column' => 'status', 'operator' => '=', 'value' => 'active'], // Example: Only active records
                ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user’s branch
            ];
        }
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
}