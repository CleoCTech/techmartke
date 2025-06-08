<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\System\AttachmentsController as SystemAttachmentsController;
use App\Http\Controllers\System\CompanyInformationController as SystemCompanyInformationController;
use App\Http\Controllers\System\SocialMediasController as SystemSocialMediasController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\System\UsersContoller;
use App\Http\Controllers\System\CourseController;
/***
 * Default System Routes
 */
Route::prefix('system')->group(function () {
    /***
     * Company Information
     */
    Route::get('/company-information',[SystemCompanyInformationController::class,'index'])->name('system.company');
    Route::get('/company-information/show/{uuid}',[SystemCompanyInformationController::class,'show']);
    Route::get('/company-information/edit/{uuid}',[SystemCompanyInformationController::class,'edit']);
    Route::post('/company-information/store',[SystemCompanyInformationController::class,'store']);
    /***
     * Social Media
     */
    Route::get('/social-media',[SystemSocialMediasController::class,'index'])->name('system.social');
    Route::get('/social-media/create',[SystemSocialMediasController::class,'create']);
    Route::get('/social-media/show/{uuid}',[SystemSocialMediasController::class,'show']);
    Route::get('/social-media/edit/{uuid}',[SystemSocialMediasController::class,'edit']);
    Route::post('/social-media/store',[SystemSocialMediasController::class,'store']);
    Route::delete('/social-media/delete/{uuid}',[SystemSocialMediasController::class,'destroy']);
    Route::get('/social-media/report/{name}',[SystemSocialMediasController::class,'report']);

    /***
     * User Management
     */
    Route::get('/user',[UsersContoller::class,'index'])->name('system.user');
    Route::get('/user/create',[UsersContoller::class,'create']);
    Route::get('/user/show/{uuid}',[UsersContoller::class,'show']);
    Route::get('/user/edit/{uuid}',[UsersContoller::class,'edit']);
    Route::post('/user/store',[UsersContoller::class,'store']);
    Route::delete('/user/delete/{uuid}',[UsersContoller::class,'destroy']);
    Route::get('/user/report/{name}',[UsersContoller::class,'report']);

    
    // Roles Routes
    Route::get('/roles', [RoleController::class, 'index'])->name('system.roles');
    Route::get('/roles/create', [RoleController::class, 'create']);
    Route::get('/roles/show/{uuid}', [RoleController::class, 'show']);
    Route::get('/roles/edit/{uuid}', [RoleController::class, 'edit']);
    Route::post('/roles/store', [RoleController::class, 'store']);
    Route::delete('/roles/delete/{uuid}', [RoleController::class, 'destroy']);
    Route::get('/roles/report/{name}', [RoleController::class, 'report']);

    // Permissions Routes
    Route::get('/permissions', [PermissionController::class, 'index'])->name('system.permissions');
    Route::get('/permissions/create', [PermissionController::class, 'create']);
    Route::get('/permissions/show/{uuid}', [PermissionController::class, 'show']);
    Route::get('/permissions/edit/{uuid}', [PermissionController::class, 'edit']);
    Route::post('/permissions/store', [PermissionController::class, 'store']);
    Route::delete('/permissions/delete/{uuid}', [PermissionController::class, 'destroy']);
    Route::get('/permissions/report/{name}', [PermissionController::class, 'report']);

    /***
     * Admin Users
     */
    /***
     * User Roles
     */
    /***
     * System Actions
     */
    /***
     * General Setups
     */
     /***
     * Attachment
     */
    Route::get('/attachment/index/{tableName}/{recordId}',[SystemAttachmentsController::class,'index']);
    Route::get('/attachment/index/{tableID}',[SystemAttachmentsController::class,'indexAll']);
    Route::get('/attachment/show/{uuid}',[SystemAttachmentsController::class,'show']);
    Route::post('/attachment/store',[SystemAttachmentsController::class,'store']);
    Route::delete('/attachment/delete/{uuid}',[SystemAttachmentsController::class,'destroy']);
    /***
     * Data Import
     */

    // Courses Routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::controller(CourseController::class)->group(function () {
            Route::get('/courses', 'index')->name('system.courses.index');
            Route::get('/courses/create', 'create')->name('system.courses.create');
            Route::post('/courses/store', 'store')->name('system.courses.store');
            Route::get('/courses/{uuid}', 'show')->name('system.courses.show');
            Route::get('/courses/edit/{uuid}', 'edit')->name('system.courses.edit');
            Route::delete('/courses/{uuid}', 'destroy')->name('system.courses.destroy');
        });
    });

});
/**
 * Other
 */
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:cache');
    return redirect('/');
});
//create a symlink to storage folder
Route::get('/storage-link', function() {
    Artisan::call('storage:link');
    return redirect('/');
});
