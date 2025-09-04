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
use App\Models\FeeStructure;
use App\Models\TrainingModule;
use App\Models\CourseType;

class FeeStructureController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\FeeStructure',
        'caption' => "Fee Structure",
        'xFolder' => "Admin/Pages/FeeStructure",
        'indexRoute' => "/admin/fee-structure",
        'storageName' => "fee_structures",
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
        'relationName' => 'trainingModule,courseType',
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
        $this->setup['statuses'] = $this->defaultModel::options('is_active');
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
        
        // Add training modules and course types for dropdowns
        $this->viewData['trainingModules'] = TrainingModule::active()->orderBy('sort_order')->get();
        $this->viewData['courseTypes'] = CourseType::active()->orderBy('sort_order')->get();
        
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        // Cast is_active to boolean
        $request->merge([
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $validated = $request->validate([
            'uuid' => 'nullable|string',
            'training_module_id' => 'required|exists:training_modules,id',
            'course_type_id' => 'required|exists:course_types,id',
            'fee' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);

        // Check for unique combination
        $existingFeeStructure = FeeStructure::where('training_module_id', $request->training_module_id)
            ->where('course_type_id', $request->course_type_id)
            ->where('uuid', '!=', $request->uuid)
            ->first();

        if ($existingFeeStructure) {
            return response()->json([
                'message' => 'A fee structure already exists for this training module and course type combination.',
                'type' => 'error'
            ], 422);
        }

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            
            $record = [
                'training_module_id' => $request->training_module_id,
                'course_type_id' => $request->course_type_id,
                'fee' => $request->fee,
                'duration' => $request->duration,
                'description' => $request->description,
                'is_active' => $request->is_active,
            ];

            $feeStructure = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

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
        
        // Add training modules and course types for dropdowns
        $this->viewData['trainingModules'] = TrainingModule::active()->orderBy('sort_order')->get();
        $this->viewData['courseTypes'] = CourseType::active()->orderBy('sort_order')->get();
        
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $record = $this->defaultModel::where('uuid', $uuid)->first();
        $record->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }

    public function report($name)
    {
        $this->def_report($name);
        return Inertia::render($this->settings['xFolder'] . '/Report', $this->viewData);
    }
}
