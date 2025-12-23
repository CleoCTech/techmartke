<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class GalleryController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' =>  '\\App\\Models\\Gallery',
        'caption' =>  "Gallery",
        'xFolder' =>  "Admin/Pages/Gallery",
        'indexRoute' =>  "/admin/gallery",
        'storageName' =>  "gallery",
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
        //         ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user’s branch
        //     ];
        // }
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }
    public function create(){
        $this->def_create();
        $maxSequence = Gallery::max('sequence');
        $this->viewData['maxSequence'] = $maxSequence;
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }
    public function store(REQUEST $request){
        // Log::info($request->all());
        // Log::info('Uploaded file size: ' . $request->file('cover_image')->getSize());
        $this->validate($request, [
            'title' => 'required',
            'category' => 'required',
            'cover_image' => 'required|file|mimes:jpg,jpeg,png|max:5024',
            'status' => 'required',
            'publish_time' => 'nullable|required_if:status,=,3',
        ]);
        DB::beginTransaction();
        try{
            $this->pKey = $request->uuid == 'null'? null:$request->uuid;
            if($request->hasFile('cover_image')){
                $fileName = $this->generateFileName($request->file('cover_image'));
            }
            $record = [
                'title' => $request->title,
                'category' => $request->category,
                // 'cover_image' => $request->cover_image,
                // 'is_global' => $request->is_global,
                // 'branch_id' => $request->branch_id,
                'status' => $request->status,
                'sequence' => $request->sequence,
                'publish_time' => $request->publish_time,
            ];
            $record['is_global'] = filter_var($request->is_global, FILTER_VALIDATE_BOOLEAN);
            if($request->hasFile('cover_image')){
                $record['cover_image'] = $fileName;
            }
            if($request->status != 3){
                $record['publish_time'] = null;
            }
            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }
            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);
          
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
        // $branches = Branch::all();

        // $this->viewData['branches'] = $branches;
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
}
