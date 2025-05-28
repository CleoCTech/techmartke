<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\System\Attachment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;

class FeeStructure extends Controller
{

    // use LayoutTrait;
    // use UploadFileTrait;
    
    // public $settings = [
    //     'model' =>  '\\App\\Models\\Fee',
    //     'caption' =>  "Fee",
    //     'xFolder' =>  "Admin/Pages/Fee",
    //     'indexRoute' =>  "/admin/fees",
    //     'storageName' =>  "fees",
    //     'isList' => true,
    //     'isCreate' => true,
    //     'isView' => true,
    //     'isEdit' => true,
    //     'isDelete' => true,
    //     'isActions' => true,
    //     'isAttachments' => true,
    //     'isReports' => false,
    //     'isCharts' => false,
    //     'isSearch' => true,
    //     'isSelect' => true,
    //     'isMoreActions' => false,
    //     'xMoreActions' => null,
    //     'isExport' => false,
    //     'orderBy' => ['column' => 'created_at', 'order' => 'DESC'],
    // ];

    // public function __construct(){
    //     $this->middleware('gen-auth');
    //     $this->middleware('admin-auth');
    //     $this->defaultModel = $this->settings['model'];
    //     $this->setup['statuses'] = $this->defaultModel::options('status');
    //     $this->setup['tableName'] = $this->defaultModel::getTableName();
    //     $this->def_constructor();
    // }

    public function index(){
        $fees = Fee::all(); 
        // $this->def_index();
        return Inertia::render('Admin/Pages/FeeStructure/Index', [
            'fees' => $fees,
        ]);
    }
    public function uploadView(){

        $fees = Fee::all(); 
        return Inertia::render('Admin/Pages/FeeStructure/Upload', [
            'fees' => $fees
        ]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'grade' => 'required|string',
            'term1' => 'required|numeric',
            'term2' => 'required|numeric',
            'term3' => 'required|numeric',
            'total' => 'nullable|numeric',
            'category' => 'required|in:full_boarding,flex_boarding,day_school'
        ]);

        Fee::create($data); // Save to the database

        return redirect()->back()->with('message', 'Fee created successfully');
    }

    // Update an existing fee
    public function update(Request $request, Fee $fee)
    {
        $data = $request->validate([
            'term1' => 'required|numeric',
            'term2' => 'required|numeric',
            'term3' => 'required|numeric',
            'total' => 'nullable|numeric',
            'total' => 'required|numeric'
        ]);

        $fee->update($data);

        return redirect()->back()->with('message', 'Fee updated successfully');
    }
}
