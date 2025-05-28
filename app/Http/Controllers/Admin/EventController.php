<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Event;
use App\Services\UserRoleService;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class EventController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;
    
    public $settings = [
        'model' =>  '\\App\\Models\\Event',
        'caption' =>  "Events",
        'xFolder' =>  "Admin/Pages/Events",
        'indexRoute' =>  "/admin/event",
        'storageName' =>  "event",
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
        $this->setup['event_types'] = $this->defaultModel::options('event_type');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();
    }
    public function index(){
        $user = Auth::user();

        if (!UserRoleService::hasRole(['administrator', 'superadmin'])) {

            $this->extraConditions = [
                // ['column' => 'status', 'operator' => '=', 'value' => 'active'], // Example: Only active records
                ['column' => 'branch_id', 'operator' => '=', 'value' => $user->branch_id] // Restrict to user’s branch
            ];
        }
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }
    public function create(){
        $this->def_create();
        $branches = Branch::all();

        $this->viewData['branches'] = $branches;
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }
    public function store(REQUEST $request){
        //  Log::info($request->all());
       // Log::info('Uploaded file size: ' . $request->file('cover_image')->getSize());
        $this->validate($request, [
            'title' => 'required',
            'event_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'description' => 'required',
            // 'cover_image' => 'nullable|file|mimes:jpg,jpeg,png|max:10024',
            'status' => 'required',
            'publish_time' => 'nullable|required_if:status,=,3',
        ]);
        DB::beginTransaction();
        try{
            $this->pKey = $request->uuid == 'null'? null:$request->uuid;
            if($request->hasFile('cover_image')){
                
                $fileName = $this->generateFileName($request->file('cover_image'));
            } else {
               
            }
            $startDate = Carbon::createFromFormat('Y-m-d', $request->start_date)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d', $request->end_date)->format('Y-m-d');
            
            if($request->status == 3){
                $publishTime = Carbon::createFromFormat('Y-m-d\TH:i', $request->publish_time);
                // $startDate = $publishTime->format('Y-m-d');
                $record = [
                    'publish_time' => $publishTime,
                ];
            }
            $record = [
                'title' => $request->title,
                'description' => $request->description,
                'content' => $request->content,
                'date' => $startDate,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => $request->status,
                'location' => $request->location,
                'event_type' => $request->event_type,
                'is_for_all_branches' => $request->is_for_all_branches,
                
                'sequence' => $request->sequence,
            ];
            if ($request->branch_id != 'null' && $request->is_for_all_branches == false) {
                $branch_id = $request->branch_id;
                $record['branch_id'] = $branch_id;
            }
            $record['is_for_all_branches'] = filter_var($request->is_for_all_branches, FILTER_VALIDATE_BOOLEAN);

            if($request->hasFile('cover_image')){
                $record['cover_image'] = $fileName;
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

        $this->viewData['branches'] = $branches;
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

    public function calendar(Request $request)
    {
        $year = $request->query('year', Carbon::now()->year);
        $month = $request->query('month', Carbon::now()->month);

        // Get the start and end dates for the month
        $startDate = Carbon::create($year, $month)->startOfMonth();
        $endDate = Carbon::create($year, $month)->endOfMonth();

        // Add buffer days for previous and next month's visible dates
        $startDate->subDays(7);
        $endDate->addDays(7);

        $events = Event::whereBetween('date', [$startDate, $endDate])
            ->where('status', 2)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get([
                'id',
                'uuid',
                'title',
                'slug',
                'description',
                'date',
                'start_time',
                'end_time',
                'location',
                'online_link',
                'cover_image'
            ]);

        return response()->json($events);
    }
}
