<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    public function rootView(Request $request)
    {
        
        if(request()->is('admin/*') or request()->is('admin'))
        {
            return 'admin';
        }
        if(request()->is('system/*') or request()->is('system'))
        {
            return 'admin';
        }
        if(request()->is('login') or request()->is('register'))
        {
            return 'admin';
        }
        if(request()->is('home') or request()->is('/'))
        {
            return 'app';
        }
        if(request()->is('forgot-password') )
        {
            // return 'blank';
            // dd('password.request');
            return 'admin';
        }
        // dd(request());
        return parent::rootView($request);
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        $role = 'default';
        if ($request->user()) {
            $roles = $request->user()->roles;
            foreach ($roles as $role) {
                $role = $role->display_name;
                // info($role);
            }
        }
        return array_merge(parent::share($request), [
            'csrf_token' => csrf_token(),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'info' => fn () => $request->session()->get('info'),
                'warning' => fn () => $request->session()->get('warning'),
            ],
            // 'auth.user' => fn () => $request->user() ? $request->user()->only('id', 'name', 'email','profile_category','user_category') : null,
            // 'auth.role' => fn () => $request->user() ? $role : 'default',
            'auth' => [
                'user' => fn () => $request->user() ? [
                    'id' => $request->user()->id,
                    'email' => $request->user()->email,
                    'name' => $request->user()->name,
                    'profile_category' => $request->user()->profile_category,
                    'user_category' => $request->user()->user_category,
                    'roles' => $request->user()->roles->pluck('name'),
                    // Fix: Fetch department heads correctly with relationship and get designation names
                    'departmentHead' => $request->user()->departmentHeads()->with('designation')->get()->pluck('designation.name'),

                    // Fix: Fetch branch leadership correctly with relationship and get designation names
                    'branch' => $request->user()->churchBranchLeaderships()->with('branch')->get()->pluck('branch.name'),
                    'permissions' => $request->user()->permissions->pluck('name'),
                ] : null,
                'role' =>  fn () => $request->user() ? $role : 'default',
            ],
            //custom
            'defaultErrors' => config('app.defaultErrors'),
            'config' => [
                'appName' => config('app.name'),
                'company' => [
                    'name' => config('app.company')['name'],
                    'shortName' => config('app.company')['shortName'],
                ],
                'developer' => [
                    'name' => 'Wenla Systems',
                    'website' => 'http://wenlasystems.com',
                ]
            ],
            'storagePaths' => config('app.storagePaths')
        ]);
    }
}
