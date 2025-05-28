<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AwardController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' => '\\App\\Models\\Award',
        'caption' => "Award",
        'xFolder' => "Admin/Pages/Award",
        'indexRoute' => "/admin/award",
        'storageName' => "award",
        'isList' => true,
        'isCreate' => true,
        'isView' => true,
        'isEdit' => true,
        'isDelete' => true,
        'isActions' => true,
        'isAttachments' => true,
        'isReports' => false,
        'isCharts' => false,
        'isSearch' => true,
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
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }

    public function index()
    {
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index', $this->viewData);
    }

    public function create()
    {
        $this->def_create();
        return Inertia::render($this->settings['xFolder'].'/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_received' => 'required|date',
            'awarding_body' => 'required|string|max:255',
            'status' => 'required|integer|in:1,2,3',
            'publish_time' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'date_received' => $request->date_received,
                'awarding_body' => $request->awarding_body,
                'status' => $request->status,
                'publish_time' => $request->publish_time,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $award = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            if ($request->hasFile('image')) {
                $fileName = $this->generateFileName($request->file('image'));
                $fileData = [
                    'file' => $request->file('image'),
                    'fileName' => $fileName,
                    'storageName' => $this->settings['storageName'],
                    'prevFile' => $award->image
                ];
                
                if ($this->uploadFile($fileData)) {
                    $award->update(['image' => $fileName]);
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
        return Inertia::render($this->settings['xFolder'].'/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        return Inertia::render($this->settings['xFolder'].'/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $this->def_destroy($uuid);
        return response()->json($this->notification('success'), 200);
    }
} 