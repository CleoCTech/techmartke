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
use App\Models\Designation;

class DesignationController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\Designation',
        'caption' => "Designation",
        'xFolder' => "Admin/Pages/Designation",
        'indexRoute' => "/admin/designations",
        'storageName' => "designation",
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
            'name' => 'required|string|unique:designations,name',
            'description' => 'nullable|string',
            'type' => 'required|in:spiritual,administrative,operational,general',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'name' => $request->name,
                'type' => $request->type,
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
        $designation = $this->defaultModel::where('uuid', $uuid)->first();
        $designation->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }
}