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

class CompanyInformationController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' =>  '\\App\\Models\\System\\CompanyInformation',
        'caption' =>  "Company Information",
        'xFolder' =>  "System/Pages/CompanyInformation",
        'indexRoute' =>  "/system/company-information",
        'storageName' =>  "companyinfo",
        'isList' => true,
        'isCreate' => false,
        'isView' => true,
        'isEdit' => true,
        'isDelete' => false,
        'isActions' => true,
        'isAttachments' => true,
        'isReports' => false,
        'isCharts' => false,
        'isSearch' => false,
        'isReltionship' => false,
        'relationName' => "",
        'isSelect' => false,
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
        $this->def_constructor();
    }

    public function index(){
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }

    public function store(REQUEST $request){
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'shortName' => 'nullable|string|max:100',
            'phoneNumbers' => 'required|string|max:50',
            'emails' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'location' => 'nullable|string|max:255',
            'about_short' => 'nullable|string|max:1000',
            'about' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'motto' => 'nullable|string|max:500',
            'core_values' => 'nullable|string',
        ]);
        DB::beginTransaction();
        try{
            $this->pKey = $request->uuid;
            if($request->hasFile('logo')){
                $fileName = $this->generateFileName($request->file('logo'));
            }
            $record = [
                'company_name' => $request->name,
                'short_name' => $request->shortName,
                'phone_numbers' => $request->phoneNumbers,
                'emails' => $request->emails,
                'address' => $request->address,
                'location' => $request->location,
                'about_short' => $request->about_short,
                'about' => $request->about,
                'mission' => $request->mission,
                'vision' => $request->vision,
                'motto' => $request->motto,
                'core_values' => $request->core_values,
            ];
            if($request->hasFile('logo')){
                $record['logo'] = $fileName;
            }
            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }
            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);
            if($request->hasFile('logo')){
                $fileData = new \stdClass();
                $fileData->file = $request->file('logo');
                $fileData->fileName = $fileName;
                $fileData->storageName = $this->settings['storageName'].'\\images';
                $fileData->prevFile = $this->prevRecord != null ? $this->prevRecord->logo : null;
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

}
