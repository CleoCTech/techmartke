<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Notifications\ResetPasswordNotification;

class UsersContoller extends Controller
{
    use LayoutTrait;
    use UploadFileTrait;

    public $settings = [
        'model' =>  '\\App\\Models\\User',
        'caption' =>  "Users",
        'xFolder' =>  "Admin/Pages/Users",
        'indexRoute' =>  "/system/user",
        'storageName' =>  "users",
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
        'isReltionship' => true,
        'relationName' => "roles,permissions",
        'paginateRelation' => false,
        'isSelect' => true,
        'isMoreActions' => false,
        'xMoreActions' => null,
        'isExport' => false,
        'orderBy' => ['column' => 'created_at', 'order' => 'DESC'],
        'ListPart' => [
            
        ]
    ];
    public function __construct(){
        // $this->middleware('gen-auth');
        // $this->middleware('admin-auth');
        $this->defaultModel = $this->settings['model'];
        $this->isReltionship = $this->settings['isReltionship'];
        $this->relationName = $this->settings['relationName'];
        $this->paginateRelation = $this->settings['paginateRelation'];
        $this->setup['statuses'] = $this->defaultModel::options('status');
        $this->setup['usertypes'] = $this->defaultModel::options('user_category');
        $this->setup['tableName'] = $this->defaultModel::getTableName();
        $this->def_constructor();

    }
    public function index(){
        $this->def_index();
        return Inertia::render($this->settings['xFolder'].'/Index',$this->viewData);
    }
    
    public function create(){
        $roles = Role::all();
        $permissions = Permission::all();
        $this->def_create();
        $this->viewData['roles'] = $roles;
        $this->viewData['permissions'] = $permissions;
        return Inertia::render($this->settings['xFolder'].'/CreateEdit',$this->viewData);
    }

    public function store(REQUEST $request){
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            // 'role' => 'required',
            // 'roles' => 'array',
            // 'permissions' => 'array',
            'roles' => 'present|array', // Ensure roles is present and an array (can be empty)
            'roles.*' => 'exists:roles,id', // Ensure each role ID exists in the roles table
            'permissions' => 'present|array', // Ensure permissions is present and an array (can be empty)
            'permissions.*' => 'exists:permissions,id', // Ensure each permission ID exists in the permissions table
        ]);

        ini_set('max_execution_time', 300); //300 seconds = 5 minutes
        DB::beginTransaction();
        try{
            $this->pKey = $request->uuid;
            $record = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if($this->pKey == null){
                $record['created_by'] = Auth::user()->email;
            }else{
                $record['updated_by'] = Auth::user()->email;
            }
            $user = $this->defaultModel::updateOrCreate(["uuid" => $this->pKey], $record);
            // info($user);
            // $user->addRole($request->role);
             // Sync roles and permissions
            
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permissions);
             // Attach roles and permissions
            // $user->roles()->sync($request->roles);
            // $user->permissions()->sync($request->permissions);
            // $token = app('auth.password.broker')->createToken($user);

            // $user->notify(new ResetPasswordNotification($token));
            
            if($this->isCommit){
                DB::Commit();
                $response = $this->notification('success');
                // info($response);
                return response()->json($response,200);
            }else{
                DB::rollback();
            }
        }catch(\Exception $e){
            DB::rollback();
            Log::error($e);
            $response = $this->notification('error',$e->getMessage());
            // $response = $this->notificationAll('error', $e->getMessage());
            return response()->json($response,500);
        }
    }
    public function show($uuid){
        $this->def_show($uuid);
        $user = $this->defaultModel::where('uuid',$uuid)->first();
        $userRoles = $user->roles;
        // info($userRoles);
        $role = '';
        foreach ($userRoles as $key => $role) {
            $role = $role->name;
        }
        $roles = Role::all();
        $permissions = Permission::all();
        // info($role);
        $this->viewData['roles'] = $roles;
        $this->viewData['role'] = $role;
        $this->viewData['permissions'] = $permissions;

        return Inertia::render($this->settings['xFolder'].'/Show',$this->viewData);
    }
    public function edit($uuid){
        $this->def_edit($uuid);
        if($this->viewData['cardData']['status'] == 3){
            $this->viewData['cardData']['publish_time2'] = $this->viewData['cardData']['publish_time']->format('Y-m-d\TH:i');
        }
        $user = $this->defaultModel::where('uuid',$uuid)->first();
        $userRoles = $user->roles;
        // info($userRoles);
        $role = '';
        foreach ($userRoles as $key => $role) {
            $role = $role->name;
        }
        $roles = Role::all();
        $permissions = Permission::all();
        // info($role);
        $this->viewData['roles'] = $roles;
        $this->viewData['role'] = $role;
        $this->viewData['permissions'] = $permissions;
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
