<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ServicesController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' =>  '\\App\\Models\\System\\Service',
        'caption' =>  "Service",
        'xFolder' =>  "Admin/Pages/Service",
        'indexRoute' =>  "/admin/service",
        'storageName' =>  "service",
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
        'isReltionship' => false,
        'relationName' => "",
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
        $this->isReltionship = $this->settings['isReltionship'];
        $this->relationName = $this->settings['relationName'];
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }
    public function index(){
        $user = Auth::user();

        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

        }
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }
    public function create(){
        $this->def_create();
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }
    public function store(REQUEST $request){
        $this->validate($request, [
            'title' => 'required',
            'presiding_minister' => 'required',
            'service_date' => 'required',
            'summary' => 'required',
            'content' => 'nullable',
            'svg' => 'nullable',
            'cover_image' => 'nullable',
            'sequence' => 'nullable',
            'status' => 'required',
            'publish_time' => 'nullable|required_if:status,=,3',
        ]);
        DB::beginTransaction();
        try{
            if($request->hasFile('cover_image')){
                $fileName = $this->generateFileName($request->file('cover_image'));
            }
            $this->pKey = $request->uuid == 'null'? null:$request->uuid;
            $record = [
                'user_id' =>  Auth::user()->id,
                'title' => $request->title,
                'summary' => $request->summary,
                'presiding_minister' => $request->presiding_minister,
                'service_date' => $request->service_date,
                'content' => $request->content,
                'svg' => $request->svg,
                'cover_image' => $request->cover_image,
                'status' => $request->status,
                'sequence' => $request->sequence,
                'publish_time' => $request->publish_time,
            ];
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
