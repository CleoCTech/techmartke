<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Video;
use App\Models\Branch;
use App\Services\UserRoleService;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    use LayoutTrait, UploadFileTrait;

    public $settings = [
        'model' => '\App\Models\Video',
        'caption' => 'Videos',
        'xFolder' => 'Admin/Pages/Videos',
        'indexRoute' => '/admin/video-gallery',
        'storageName' => 'thumbnails',
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
        'relationName' => 'branch',
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
        $user = Auth::user();

        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

            $this->extraConditions = [
                // ['column' => 'status', 'operator' => '=', 'value' => 'active'], // Example: Only active records
                ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user’s branch
            ];
        }
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimes:mp4,mov,avi|max:102400', // Max 100MB
            'thumbnail' => 'nullable|image|max:1024', // Max 1MB
            'is_global' => 'boolean',
            'branch_id' => 'nullable|exists:branches,id',
        ]);

        DB::beginTransaction();
        try {
            $videoPath = $request->file('video')->store('videos', 'public');
            $thumbnailPath = $request->hasFile('thumbnail') ? $request->file('thumbnail')->store('thumbnails', 'public') : null;

            $video = Video::create([
                'uuid' => (string) Str::uuid(),
                'branch_id' => $request->is_global ? null : $request->branch_id,
                'is_global' => $request->is_global,
                'title' => $request->title,
                'description' => $request->description,
                'video_path' => $videoPath,
                'thumbnail_path' => $thumbnailPath,
                'status' => 'processing', // Simulating processing step for large uploads
                'created_by' => Auth::user()->email,
            ]);

            DB::commit();
            return response()->json(['message' => 'Video uploaded successfully, processing...'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e);
            return response()->json(['error' => 'Failed to upload video.'], 500);
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
        $video = Video::where('uuid', $uuid)->first();
        if ($video) {
            $video->delete();
            return response()->json(['message' => 'Video deleted successfully.'], 200);
        }
        return response()->json(['error' => 'Video not found.'], 404);
    }

    private function loadFormData()
    {
        $this->viewData['setup']['branches'] = Branch::all();
    }

    public function uploadChunk(Request $request)
    {
        $request->validate([
            'file' => 'required',
            'fileName' => 'required|string',
            'fileId' => 'required',
            'chunkIndex' => 'required|integer',
            'totalChunks' => 'required|integer',
        ]);

        try {
            $chunk = $request->file('file');
            $fileName = $request->fileName;
            $fileId = $request->fileId;
            
            // Store chunk in temporary directory
            $chunkPath = "chunks/{$fileId}";
            Storage::makeDirectory($chunkPath);
            
            $chunk->storeAs($chunkPath, "chunk_{$request->chunkIndex}");

            return response()->json(['message' => 'Chunk uploaded successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function mergeChunks(Request $request)
    {
        $request->validate([
            'fileName' => 'required|string',
            'fileId' => 'required',
        ]);

        try {
            $fileId = $request->fileId;
            $fileName = $request->fileName;
            $chunkPath = "chunks/{$fileId}";
            
            // Generate unique filename
            $extension = pathinfo($fileName, PATHINFO_EXTENSION);
            $uniqueFileName = Str::uuid() . '.' . $extension;
            $finalPath = "videos/{$uniqueFileName}";

            // Create empty file
            Storage::put($finalPath, '');
            
            // Get all chunks and sort them
            $chunks = collect(Storage::files($chunkPath))
                ->sort(function($a, $b) {
                    $aIndex = (int) str_replace('chunk_', '', basename($a));
                    $bIndex = (int) str_replace('chunk_', '', basename($b));
                    return $aIndex - $bIndex;
                });

            // Merge chunks
            foreach ($chunks as $chunk) {
                $chunkContent = Storage::get($chunk);
                Storage::append($finalPath, $chunkContent);
            }

            // Clean up chunks
            Storage::deleteDirectory($chunkPath);

            return response()->json([
                'message' => 'File merged successfully',
                'path' => $uniqueFileName
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
