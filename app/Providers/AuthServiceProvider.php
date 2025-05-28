<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\DepartmentHead;
use App\Models\Designation;
use App\Models\FiscalPeriod;
use App\Models\FiscalYear;
use App\Policies\DepartmentHeadPolicy;
use App\Policies\DesignationPolicy;
use App\Policies\FiscalPeriodPolicy;
use App\Policies\FiscalYearPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        FiscalYear::class => FiscalYearPolicy::class,
        FiscalPeriod::class => FiscalPeriodPolicy::class,
        Designation::class => DesignationPolicy::class,
        DepartmentHead::class => DepartmentHeadPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
       $this->registerPolicies();

        //
    }
}
