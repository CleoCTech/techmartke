<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Gallery;
use App\Models\Video;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class VideosController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' =>  '\\App\\Models\\Video',
        'caption' =>  "Videos Gallery",
        'xFolder' =>  "Admin/Pages/Videos",
        'indexRoute' =>  "/admin/video-gallery",
        'storageName' =>  "videos",
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
    public function __construct(){
        $this->middleware('gen-auth');
        $this->middleware('admin-auth');
        $this->defaultModel = $this->settings['model'];
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }
    public function index(){
        $user = Auth::user();

        // if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

        //     $this->extraConditions = [
        //         // ['column' => 'status', 'operator' => '=', 'value' => 'active'], // Example: Only active records
        //         ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user's branch
        //     ];
        // }
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }
    public function create(){
        $this->def_create();
        // $branches = Branch::all();
        $maxSequence = Video::max('sequence');
        $this->viewData['maxSequence'] = $maxSequence;
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }
    public function store(REQUEST $request){
        $this->validate($request, [
            'title' => 'required',
            'description' => 'nullable',
            'thumbnail_url' => 'required',
            'sequence' => 'required',
            'video_url' => 'required',
            'status' => 'required',
            'publish_time' => 'nullable|required_if:status,=,3',
            'duration' => 'nullable|string',
            'category' => 'nullable|string',
            'views' => 'nullable|integer',
        ]);
        info($request->all());
        DB::beginTransaction();
        try{
            $this->pKey = $request->uuid == 'null'? null:$request->uuid;
            if($request->hasFile('thumbnail_url')){
                $fileName = $this->generateFileName($request->file('thumbnail_url'));
                info($fileName);
            }
            if($request->hasFile('cover_image')){
                $fileName = $this->generateFileName($request->file('cover_image'));
            }
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'video_path' => $request->video_url,
                'status' => $request->status,
                'sequence' => $request->sequence,
                'duration' => $request->duration,
                'category' => $request->category,
                'views' => $request->views,
                // 'publish_time' => $request->publish_time,
            ];
            // if ($request->branch_id == 'null') {
            // } else {
            //     $branch_id = $request->branch_id;
            //     $record['branch_id'] = $branch_id;
            // }
            //$record['is_global'] = filter_var($request->is_global, FILTER_VALIDATE_BOOLEAN);
            // Save only the filename from thumbnail_url to cover_image
            if ($request->thumbnail_url) {
                $coverImage = basename(parse_url($request->thumbnail_url, PHP_URL_PATH));
                $record['cover_image'] = $coverImage;
            }
            // if($request->status != 3){
            //     $record['publish_time'] = null;
            // }
            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }
            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);
          
            if($request->hasFile('thumbnail_url')){
                $fileData = ['file' => $request->file('thumbnail_url'),'fileName' => $fileName, 'storageName' => $this->settings['storageName'].'\\cover_images','prevFile' => $this->prevRecord != null? $this->prevRecord['thumbnail_url']:null];
                if(!$this->uploadFile($fileData)){
                    $this->isCommit = false;
                }
            }
            if($request->hasFile('cover_image')){
                $fileData = ['file' => $request->file('cover_image'),'fileName' => $fileName, 'storageName' => $this->settings['storageName'].'\\cover_images','prevFile' => $this->prevRecord != null? $this->prevRecord['cover_image']:null];
                if(!$this->uploadFile($fileData)){
                    $this->isCommit = false;
                }
            }
            if($this->isCommit){
                DB::Commit();
                $response = $this->notification('success');
                return response()->json($response,200);
            }else{
                DB::rollback();
                $response = $this->notification('error');
                return response()->json($response,500);
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::error($e);
            $response = $this->notification('error');
            return response()->json($response,500);
        }
    }
    public function show($uuid){
        $this->def_show($uuid);
        return Inertia::render($this->settings['xFolder'].'/Show',$this->viewData);
    }
    public function edit($uuid){
        $this->def_edit($uuid);
        $branches = Branch::all();
        $maxSequence = Video::max('sequence');
        // $this->viewData['branches'] = $branches;
        $this->viewData['maxSequence'] = $maxSequence;
        if($this->viewData['cardData']['status'] == 3){
            $this->viewData['cardData']['publish_time2'] = $this->viewData['cardData']['publish_time']->format('Y-m-d\TH:i');
        }
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }
    public function destroy($uuid){
        $user = $this->defaultModel::where('uuid',$uuid)->first();
        $user->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response,200);
    }
    public function report($name){
        $this->defReport();
        $this->viewData['name'] = $name;
        $this->viewData['fileName'] = 'destinations-report.pdf';
        $this->viewData['dataItems'][0]['relationships'] = 'solutions';
        $this->viewData['dataItems'][1] = [
            'model' => 'App\\Models\\Solution',
            'columns' => $this->defaultModel::getTableColumns('solutions'),
            'caption' => 'Solutions Dataset',
        ];
        return $this->viewData;
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

            // Create empty file in the public disk
            Storage::disk('public')->put($finalPath, '');
            
            // Get all chunks and sort them
            $chunks = collect(Storage::files($chunkPath))
                ->sort(function($a, $b) {
                    $aIndex = (int) str_replace('chunk_', '', basename($a));
                    $bIndex = (int) str_replace('chunk_', '', basename($b));
                    return $aIndex - $bIndex;
                });

            // Merge chunks (binary-safe) into storage/app/public/videos/...
            $finalFilePath = storage_path('app/public/' . $finalPath);
            $finalFile = fopen($finalFilePath, 'ab');
            foreach ($chunks as $chunk) {
                $chunkContent = Storage::get($chunk);
                fwrite($finalFile, $chunkContent);
            }
            fclose($finalFile);

             // Extract thumbnail
             $thumbnailPath = $this->extractThumbnail($finalPath);

             // Extract duration
             $duration = $this->getVideoDuration($finalPath);

             return response()->json([
                 'message' => 'File merged successfully',
                 'path' => $uniqueFileName,
                 'thumbnail' => $thumbnailPath,
                 'duration' => $duration
             ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    private function extractThumbnail($videoPath)
    {
        $thumbnailFileName = Str::uuid() . '.jpg';
        $thumbnailRelativePath = "thumbnails/{$thumbnailFileName}";

        // Full path to the video in storage/app/public/videos/...
        $fullVideoPath = storage_path('app/public/' . $videoPath);

        // Full path to the thumbnail in public/thumbnails/...
        $fullThumbnailPath = public_path($thumbnailRelativePath);

        // Ensure the thumbnails directory exists in public
        if (!file_exists(public_path('thumbnails'))) {
            mkdir(public_path('thumbnails'), 0777, true);
        }

        // Check if ffmpeg is available
        $ffmpegAvailable = $this->isFFmpegAvailable();

        if (!$ffmpegAvailable) {
            Log::warning("FFmpeg is not available. Using default thumbnail.");
            return $this->useDefaultThumbnail($thumbnailRelativePath);
        }

        // Extract thumbnail at 1 second mark
        $process = new Process([
            'ffmpeg',
            '-i', $fullVideoPath,
            '-ss', '00:00:01.000',
            '-vframes', '1',
            $fullThumbnailPath
        ]);

        try {
            $process->run();

            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                Log::error("Error extracting thumbnail: " . $errorOutput);
                throw new \RuntimeException("Failed to extract thumbnail: " . $errorOutput);
            }

            // Return the public URL (e.g., /thumbnails/uuid.jpg)
            return url('thumbnails/' . $thumbnailFileName);
        } catch (\Exception $e) {
            Log::error("Exception while extracting thumbnail: " . $e->getMessage());
            throw $e;
        }
    }

    private function isFFmpegAvailable()
    {
        $process = new Process(['ffmpeg', '-version']);
        try {
            $process->run();
            return $process->isSuccessful();
        } catch (\Exception $e) {
            return false;
        }
    }

    private function useDefaultThumbnail($thumbnailPath)
    {
        $defaultThumbnailPath = 'thumbnails/default-video-thumbnail.jpg';
        
        // Check if the default thumbnail exists in the public disk
        if (!Storage::disk('public')->exists($defaultThumbnailPath)) {
            Log::warning("Default thumbnail image not found.");
            return null;
        }
        
        // Copy the default thumbnail to the specified path
        Storage::disk('public')->copy($defaultThumbnailPath, $thumbnailPath);
        
        // Return the public URL
        return $thumbnailPath;
    }

    private function getVideoDuration($videoPath)
    {
        $fullVideoPath = storage_path('app/public/' . $videoPath);
        $process = new \Symfony\Component\Process\Process([
            'ffprobe', '-v', 'error', '-show_entries',
            'format=duration', '-of', 'default=noprint_wrappers=1:nokey=1', $fullVideoPath
        ]);
        $process->run();
        if ($process->isSuccessful()) {
            $seconds = floatval(trim($process->getOutput()));
            $minutes = floor($seconds / 60);
            $remainingSeconds = round($seconds % 60);
            return sprintf('%d:%02d', $minutes, $remainingSeconds); // e.g., 5:30
        }
        return null;
    }
}
