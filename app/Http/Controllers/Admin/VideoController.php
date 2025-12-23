<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class VideoController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' => '\\App\\Models\\Video',
        'caption' => "Video Gallery",
        'xFolder' => "Admin/Pages/Videos",
        'indexRoute' => "/admin/video-gallery",
        'storageName' => "videos",
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
        'orderBy' => ['column' => 'sequence', 'order' => 'ASC'],
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
            'video_url' => 'nullable|url|max:500',
            'thumbnail_url' => 'nullable|url|max:500',
            'category' => 'nullable|string|max:255',
            'duration' => 'nullable|string|max:50',
            'views' => 'nullable|integer|min:0',
            'status' => 'required|integer|in:1,2,3',
            'publish_time' => 'nullable|date',
            'sequence' => 'nullable|integer',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'video_url' => $request->video_url,
                'thumbnail_url' => $request->thumbnail_url,
                'category' => $request->category,
                'duration' => $request->duration,
                'views' => $request->views ?? 0,
                'status' => $request->status,
                'publish_time' => $request->publish_time,
                'sequence' => $request->sequence,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $video = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            if ($request->hasFile('cover_image')) {
                $fileName = $this->generateFileName($request->file('cover_image'));
                $fileData = [
                    'file' => $request->file('cover_image'),
                    'fileName' => $fileName,
                    'storageName' => $this->settings['storageName'],
                    'prevFile' => $video->cover_image
                ];
                
                if ($this->uploadFile($fileData)) {
                    $video->update(['cover_image' => $fileName]);
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

    public function report($name)
    {
        $this->defReport();
        $this->viewData['name'] = $name;
        $this->viewData['fileName'] = 'videos-report.pdf';
        return $this->viewData;
    }
}



