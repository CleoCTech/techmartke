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
use App\Models\System\Course;

class CourseController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' =>  '\\App\\Models\\System\\Course',
        'caption' =>  "Course",
        'xFolder' =>  "System/Pages/Course",
        'indexRoute' =>  "/system/courses",
        'storageName' =>  "courses",
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
        // Assuming Course model has an options method for status
        $this->setup['statuses'] = $this->defaultModel::options('status');
        // Assuming Course model has a getTableName method
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }

    public function index(){
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }

    public function create(){
        $this->def_create();
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }

    public function store(REQUEST $request){
        // Add validation based on your course schema
        $this->validate($request, [
            'title' => 'required',
            'duration' => 'nullable',
            'level' => 'nullable',
            'description' => 'nullable',
            'projects' => 'nullable|integer',
            'students' => 'nullable|integer',
            'technologies' => 'nullable',
            'status' => 'required',
            // 'image' => 'nullable',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:6024',
        ]);

        // info($request->all());
        // return;
        DB::beginTransaction();
        try{
            $fileName = null;
            if($request->hasFile('image')){
                $fileName = $this->generateFileName($request->file('image'));
            }
            
            $this->pKey = $request->uuid == 'null'? null:$request->uuid;

            $record = [
                'title' => $request->title,
                'duration' => $request->duration,
                'level' => $request->level,
                'description' => $request->description,
                'projects' => $request->projects,
                'students' => $request->students,
                'technologies' => $request->technologies ? explode(',', $request->technologies) : null,
                'status' => $request->status,
            ];

            if($request->hasFile('image')){
                $record['image'] = $fileName;
            }

            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }

            $course = Course::updateOrCreate(['uuid' => $this->pKey], $record);

            if($request->hasFile('image')){
                $fileData = ['file' => $request->file('image'),'fileName' => $fileName, 'storageName' => $this->settings['storageName'].'\\cover_images','prevFile' => $this->prevRecord != null? $this->prevRecord->image:null];
                 if(!$this->uploadFile($fileData)){
                    $this->isCommit = false;
                 }
               // Using uploadFileWithWatermark if needed, adjust accordingly
               // if(!$this->uploadFileWithWatermark($fileData)){
               //     $this->isCommit = false;
               // }
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
         // Adjust publish_time handling if you have a similar field
        // if(isset($this->viewData['cardData']['publish_time']) && $this->viewData['cardData']['status'] == 3){
        //     $this->viewData['cardData']['publish_time2'] = $this->viewData['cardData']['publish_time']->format('Y-m-d\TH:i');
        // }
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }

     public function destroy($uuid){
        $course = Course::where('uuid',$uuid)->first();
        // Consider deleting the associated image file here
        // if($course && $course->image) {
        //    $storageName = $this->settings['storageName'].'\\cover_images';
        //    $fileData = ['fileName' => $course->image, 'storageName' => $storageName];
        //    $this->deleteFile($fileData);
        // }
        $course->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response,200);
    }

    // Implement report method if needed
    // public function report($name){
    //     $this->defReport();
    //     $this->viewData['name'] = $name;
    //     $this->viewData['fileName'] = 'courses-report.pdf';
    //     // Adjust dataItems based on your course report needs
    //     // $this->viewData['dataItems'][0]['relationships'] = '...';
    //     // $this->viewData['dataItems'][1] = ['model' => '...', 'columns' => '...', 'caption' => '...',];
    //     return $this->viewData;
    // }
} 