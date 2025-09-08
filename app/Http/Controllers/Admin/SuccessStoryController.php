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
use Illuminate\Support\Str;

class SuccessStoryController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\SuccessStory',
        'caption' => "Success Story",
        'xFolder' => "Admin/Pages/SuccessStory",
        'indexRoute' => "/admin/success-story",
        'storageName' => "success_stories",
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
        'relationName' => [],
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
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
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
        $validated = $request->validate([
            'uuid' => 'nullable|string',
            'title' => 'required|string|max:255',
            'student_name' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'graduation_year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'description' => 'required|string',
            'achievement' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5048',
            'sequence' => 'nullable|integer',
            'status' => 'required|integer',
            'publish_time' => 'nullable|required_if:status,=,3',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'title' => $request->title,
                'student_name' => $request->student_name,
                'course' => $request->course,
                'graduation_year' => $request->graduation_year,
                'description' => $request->description,
                'achievement' => $request->achievement,
                'sequence' => $request->sequence,
                'status' => $request->status,
                'publish_time' => $request->status == 3 ? $request->publish_time : null,
            ];

            if ($this->pKey == null) {
               // $record['created_by'] = Auth::user()->email;
            } else {
               //$record['updated_by'] = Auth::user()->email;
            }

            $successStory = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            if ($request->hasFile('cover_image')) {
                $fileName = $this->generateFileName($request->file('cover_image'));
                $fileData = ['file' => $request->file('cover_image'),'fileName' => $fileName, 'storageName' => $this->settings['storageName'].'\\cover_images','prevFile' => $this->prevRecord != null? $this->prevRecord['cover_image']:null];

                
                if ($this->uploadFile($fileData)) {
                    $successStory->update(['cover_image' => $fileName]);
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
        return Inertia::render($this->settings['xFolder'] . '/Show', $this->viewData);
    }

    public function edit($uuid)
    {
        $this->def_edit($uuid);
        if ($this->viewData['cardData']['status'] == 3) {
            $this->viewData['cardData']['publish_time2'] = $this->viewData['cardData']['publish_time']->format('Y-m-d\TH:i');
        }
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $record = $this->defaultModel::where('uuid', $uuid)->first();
        if ($record->cover_image) {
            $this->deleteFile($record->cover_image, $this->settings['storageName']);
        }
        $record->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }
}
