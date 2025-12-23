<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class StaffController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' => '\\App\\Models\\Staff',
        'caption' => "Staff",
        'xFolder' => "Admin/Pages/Staff",
        'indexRoute' => "/admin/staff",
        'storageName' => "staff",
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
        // Normalize empty strings to null for optional fields
        $data = $request->all();
        
        // Convert empty strings, whitespace-only strings, and string "null" to null for optional fields
        $optionalFields = [
            'facebook_link', 'x_link', 'linkedin_link', 'youtube_link', 'whatsapp_link',
            'publish_time', 'sequence', 'title', 'phone_no', 'experience', 
            'education', 'achievements', 'quote'
        ];
        
        foreach ($optionalFields as $field) {
            if (isset($data[$field])) {
                $value = $data[$field];
                // Convert empty string, whitespace-only, or string "null" to actual null
                if ($value === '' || (is_string($value) && trim($value) === '') || $value === 'null') {
                    $data[$field] = null;
                }
            }
        }
        
        // Handle sequence - convert empty string to null, but allow 0
        if (isset($data['sequence'])) {
            $seqValue = $data['sequence'];
            if ($seqValue === '' || (is_string($seqValue) && trim($seqValue) === '') || $seqValue === 'null') {
                $data['sequence'] = null;
            }
        }
        
        // Set publish_time to null if status is not "Scheduled" (3)
        if (isset($data['status']) && $data['status'] != 3) {
            $data['publish_time'] = null;
        }
        
        // Remove cover_image from data if it's not a file (empty string or null)
        if (isset($data['cover_image']) && !$request->hasFile('cover_image')) {
            unset($data['cover_image']);
        }
        
        // Replace request data with normalized data
        $request->replace($data);

        // Validate using the modified request
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'title' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone_no' => 'nullable|string|max:255',
            'facebook_link' => 'nullable|url|max:255',
            'x_link' => 'nullable|url|max:255',
            'linkedin_link' => 'nullable|url|max:255',
            'youtube_link' => 'nullable|url|max:255',
            'whatsapp_link' => 'nullable|url|max:255',
            'status' => 'required|integer|in:1,2,3',
            'publish_time' => 'nullable|required_if:status,=,3|date',
            'sequence' => 'nullable|integer',
            'expertise' => 'nullable|array',
            'experience' => 'nullable|string',
            'education' => 'nullable|string',
            'achievements' => 'nullable|string',
            'quote' => 'nullable|string',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'name' => $request->name,
                'title' => $request->title,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'facebook_link' => $request->facebook_link,
                'x_link' => $request->x_link,
                'linkedin_link' => $request->linkedin_link,
                'youtube_link' => $request->youtube_link,
                'whatsapp_link' => $request->whatsapp_link,
                'status' => $request->status,
                'publish_time' => $request->status == 3 ? $request->publish_time : null,
                'sequence' => $request->sequence,
                'expertise' => $request->expertise ?? [],
                'experience' => $request->experience,
                'education' => $request->education,
                'achievements' => $request->achievements,
                'quote' => $request->quote,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $staff = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

            if ($request->hasFile('cover_image')) {
                $fileName = $this->generateFileName($request->file('cover_image'));
                $fileData = [
                    'file' => $request->file('cover_image'),
                    'fileName' => $fileName,
                    'storageName' => $this->settings['storageName'],
                    'prevFile' => $staff->cover_image
                ];
                
                if ($this->uploadFile($fileData)) {
                    $staff->update(['cover_image' => $fileName]);
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
        $this->viewData['fileName'] = 'staff-report.pdf';
        return $this->viewData;
    }
}

