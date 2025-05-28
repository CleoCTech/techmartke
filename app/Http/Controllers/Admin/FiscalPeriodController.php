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
use App\Models\FiscalPeriod;
use App\Models\FiscalYear;
use Illuminate\Validation\Rule;

class FiscalPeriodController extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' => '\\App\\Models\\FiscalPeriod',
        'caption' => "Fiscal Period",
        'xFolder' => "Admin/Pages/FiscalPeriod",
        'indexRoute' => "/admin/fiscal-period",
        'storageName' => "fiscal_period",
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
        'relationName' => "fiscalYear",
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
        // $this->setup['statuses'] = $this->defaultModel::options('status');
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
        $fiscalYears = FiscalYear::all();
        $this->viewData['fiscalYears'] = $fiscalYears;
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'period_name' => 'required|string|max:255',
            'start_date' => [
            'required',
            'date',
            Rule::unique('fiscal_periods')->where(function ($query) use ($request) {
                    return $query->where('fiscal_year_id', $request->fiscal_year_id)
                                ->whereBetween('start_date', [$request->start_date, $request->end_date])
                                ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                }),
            ],
            'end_date' => 'required|date|after:start_date',
        ]);

        DB::beginTransaction();
        try {
            $this->pKey = $request->uuid == 'null' ? null : $request->uuid;
            $record = [
                'fiscal_year_id' => $request->fiscal_year_id,
                'period_name' => $request->period_name,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ];

            if ($this->pKey == null) {
                $record['created_by'] = Auth::user()->email;
            } else {
                $record['updated_by'] = Auth::user()->email;
            }

            $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);

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
        return Inertia::render($this->settings['xFolder'] . '/CreateEdit', $this->viewData);
    }

    public function destroy($uuid)
    {
        $fiscalPeriod = $this->defaultModel::where('uuid', $uuid)->first();
        $fiscalPeriod->delete();
        $response = $this->notification('deleteSuccess');
        return response()->json($response, 200);
    }
    public function activate(FiscalPeriod $fiscalPeriod)
    {
        $this->authorize('activate', $fiscalPeriod);
        $fiscalPeriod->update(['is_current' => true]);
        $response ='Fiscal period activated successfully';
        return response()->json($response, 200);
        return back()->with('success', 'Fiscal period activated successfully!');
    }
}