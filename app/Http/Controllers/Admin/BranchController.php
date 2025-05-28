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
use App\Models\Branch;

class BranchController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\Branch', // Model class
        'caption' => "Branch", // Display name
        'xFolder' => "Admin/Pages/Branch", // Folder for Inertia views
        'indexRoute' => "/admin/branch", // Route for the index page
        'storageName' => "branch", // Storage folder name (if applicable)
        'isList' => true, // Enable listing
        'isCreate' => true, // Enable creation
        'isView' => true, // Enable viewing
        'isEdit' => true, // Enable editing
        'isDelete' => true, // Enable deletion
        'isActions' => true, // Enable actions column
        'isAttachments' => false, // Disable attachments (if not needed)
        'isReports' => false, // Disable reports (if not needed)
        'isCharts' => false, // Disable charts (if not needed)
        'isSearch' => true, // Enable search
        'isReltionship' => false, // Disable relationships (if not needed)
        'relationName' => "", // No relationship name
        'isSelect' => true, // Enable select dropdowns (if needed)
        'isMoreActions' => false, // Disable more actions (if not needed)
        'xMoreActions' => null, // No more actions
        'isExport' => false, // Disable export (if not needed)
        'orderBy' => ['column' => 'created_at', 'order' => 'DESC'], // Default order
    ];

    public function __construct()
    {
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
        $this->defaultModel = $this->settings['model'];
        $this->isReltionship = $this->settings['isReltionship'];
        $this->relationName = $this->settings['relationName'];
        $this->setup['statuses'] = $this->defaultModel::options('status'); // Status options
        $this->setup['tableName'] = $this->defaultModel::getTableName(); // Table name
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
            'location' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'contact_phone' => 'nullable|string|max:20',
            // 'contact_email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'name' => $request->name,
                'location' => $request->location,
                'contact_person' => $request->contact_person,
                'contact_phone' => $request->contact_phone,
                'contact_email' => $request->contact_email,
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
        $branch = $this->defaultModel::where('uuid', $uuid)->first();
        $branch->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }
}