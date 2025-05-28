<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AttachmentsController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' =>  '\\App\\Models\\System\\Attachment',
        'caption' =>  "Attachment",
        'xFolder' =>  null,
        'indexRoute' =>  "/system/attachment",
        'storageName' =>  "adminAttachments",
        'isList' => true,
        'isCreate' => false,
        'isView' => false,
        'isEdit' => false,
        'isDelete' => true,
        'isActions' => true,
        'isAttachments' => true,
        'isReports' => false,
        'isCharts' => true,
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
        $this->def_constructor();
    }
    public function index($tableName,$recordId){
        $this->def_index();
        $attachments = $this->defaultModel::where('table_id',$tableName)->where('record_id',$recordId)->get();
        return $attachments;
    }
    public function indexAll($tableID){
        $this->def_index();
        $attachments = $this->defaultModel::where('table_id',$tableID)->get();
        return $attachments;
    }
    public function feeStructure(){
        $this->def_index();
        // $this->setup['tableName'] = 'fees';
        $attachments = $this->defaultModel::where('table_id','fees')->get();
        $this->viewData['setup']['tableName'] = 'fees';
        return Inertia::render('Admin/Pages/FeeStructure/Attachments',$this->viewData);
        return $attachments;
    }
    public function create(){
        //
    }
    public function store(REQUEST $request){
        $this->validate($request, [
            'description' => 'required',
            'attachment' => 'required',
            'tableName' => 'required',
            'recordId' => 'nullable',
            // 'storageName' => 'required',
        ]);
        DB::beginTransaction();
        try{
            $record = [];
            if($request->hasFile('attachment')){
                $fileName = $this->generateFileName($request->file('attachment'));
                $record['attachment'] = $fileName;
            }
            $record = [
                'filename' => $fileName,
                'description' => $request->description,
                'table_id' => $request->tableName,
                'record_id' => $request->recordId,
                // 'storageName' => $request->storageName,
                'storageName' => 'attachments',
            ];
            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }
            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);
            if($request->hasFile('attachment')){
                $fileData = ['file' => $request->file('attachment'),'fileName' => $fileName, 'storageName' => $this->settings['storageName'].'\\attachments','prevFile' => null];
                // Log::info($fileData);
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
        $cardData = $this->defaultModel::where('uuid',$uuid)->first();
        if($cardData == null){
            return redirect('/dashboard')->with('error',"Not Found");
        }
        $file = public_path('storage/attachments/admin/') . $cardData->filename;

        if (!file_exists($file)) {
            return redirect('/dashboard')->with('error', "File does not exist");
        }
        
        return response()->file($file);
    }
    public function destroy($uuid){
        $cardData = $this->defaultModel::where('created_by',Auth::user()->email)->where('uuid',$uuid)->first();
        $data = ['storageName' => $this->settings['storageName'].'\\'.$cardData->storageName,'fileName' => $cardData->filename];
        // $data = ['storageName' => $cardData->storageName,'fileName' => $cardData->filename];
        $this->deleteFile($data);
        $cardData->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response,200);
    }
}