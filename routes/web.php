<?php

use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\AttendancesController;
use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\BranchLeadershipController;
use App\Http\Controllers\Admin\ChurchLeadershipController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\DepartmentActivityController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DepartmentGoalController;
use App\Http\Controllers\Admin\DepartmentHeadController;
use App\Http\Controllers\Admin\DepartmentRequestController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\EventController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
// use App\Http\Controllers\GeneralContoller as GuestGeneralContoller;
use App\Http\Controllers\GeneralController as GuestGeneralContoller;
use App\Http\Controllers\Admin\GeneralController as AdminGeneralController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\FeeStructure;
use App\Http\Controllers\Admin\FiscalPeriodController;
use App\Http\Controllers\Admin\FiscalYearController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ICTContentController;
use App\Http\Controllers\Admin\InquiriesController as AdminInquiriesController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OfferingsController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServicesController as AdminServicesController;
use App\Http\Controllers\Admin\SpecialNeedsController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\TestimoniesController;
use App\Http\Controllers\Admin\VideosController;
use App\Http\Controllers\System\AttachmentsController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\AwardController;
use App\Http\Controllers\Admin\SuccessStoryController;
use App\Http\Controllers\Admin\TrainingModuleController;
use App\Http\Controllers\Admin\CourseTypeController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\PaymentOptionController;
use App\Http\Controllers\Admin\RegistrationFeeController;
use App\Http\Controllers\Admin\ScholarshipApplicationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * Guest Routes for Novus Institute of Technology
 */
Route::get('/', [GuestGeneralContoller::class, 'home'])->name('home');
Route::get('/about', [GuestGeneralContoller::class, 'about'])->name('about');
Route::get('/faq', [GuestGeneralContoller::class, 'faq'])->name('faq');
Route::get('/events', [GuestGeneralContoller::class, 'events'])->name('events');
Route::get('/event/{slug}', [GuestGeneralContoller::class, 'showEvent'])->name('event.show');
Route::get('/contact-us', [GuestGeneralContoller::class, 'contact'])->name('contact-us');
Route::get('/courses', [GuestGeneralContoller::class, 'courses'])->name('courses');
Route::get('/course/{slug}', [GuestGeneralContoller::class, 'showCourse'])->name('course.show');
Route::get('/community', [GuestGeneralContoller::class, 'community'])->name('community');
Route::get('/training-fees', [GuestGeneralContoller::class, 'trainingFees'])->name('training-fees');
Route::get('/scholarships', [GuestGeneralContoller::class, 'scholarships'])->name('scholarships');
Route::get('/computer-ethics', [GuestGeneralContoller::class, 'computerEthics'])->name('computer-ethics');
Route::get('/institution/motto', [GuestGeneralContoller::class, 'motto'])->name('institution.motto');
Route::get('/institution/mission', [GuestGeneralContoller::class, 'mission'])->name('institution.mission');
Route::get('/institution/vision', [GuestGeneralContoller::class, 'vision'])->name('institution.vision');
Route::get('/institution/core-values', [GuestGeneralContoller::class, 'coreValues'])->name('institution.core-values');
Route::get('/partners', [GuestGeneralContoller::class, 'partners'])->name('partners');
Route::get('/testimonials', [GuestGeneralContoller::class, 'testimonials'])->name('testimonials');
Route::get('/admissions/requirements', [GuestGeneralContoller::class, 'admissionRequirements'])->name('admissions.requirements');
Route::get('/admissions/regulations', [GuestGeneralContoller::class, 'admissionRegulations'])->name('admissions.regulations');
Route::get('/awards', [GuestGeneralContoller::class, 'awards'])->name('awards');
Route::get('/application', [GuestGeneralContoller::class, 'application'])->name('application');
Route::get('/application/download/{uuid}', [GuestGeneralContoller::class, 'downloadApplicationForm'])->name('application.download');
Route::post('/application', [GuestGeneralContoller::class, 'generalApplicationPost'])->name('application.post');
Route::post('/scholarship-application', [GuestGeneralContoller::class, 'scholarshipApplicationPost'])->name('scholarship-application.post');

// Common data routes
Route::get('/topbar-data', [GuestGeneralContoller::class, 'topBarData']);
Route::get('/footer-data', [GuestGeneralContoller::class, 'footerData']);
Route::get('/api/whatsapp-config', [AdminGeneralController::class, 'whatsappConfig']);
Route::post('/customer-inquiry', [GuestGeneralContoller::class, 'sendMessage'])->name('inquiry');

// Route::middleware([
//     'auth:sanctum',
//     config('jetstream.auth_session'),
//     'verified',
// ])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });
/**
 * Auth User
 */
Route::get('/dashboard',[GuestGeneralContoller::class,'dashboard']);
Route::get('/gallery',[GuestGeneralContoller::class,'gallery'])->name('gallery');

Route::get('/news', [GuestGeneralContoller::class, 'news'])->name('news');
Route::get('/news/{slug}', [GuestGeneralContoller::class, 'showNews'])->name('news.show');

Route::prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminGeneralController::class,'dashboard'])->name('admin.dashboard');
    Route::get('/dept-dashboard',[AdminGeneralController::class,'deptDashboard'])->name('dept.dashboard');
    Route::get('/branch-dashboard',[AdminGeneralController::class,'branchDashboard'])->name('branch.dashboard');
    Route::get('/guest-dashboard',[AdminGeneralController::class,'guestDashboard'])->name('guest.dashboard');
    Route::get('/dashboard/statistics',[AdminGeneralController::class,'statistics'])->name('admin.dashboard.statistics');
    Route::get('/whatsapp-config',[AdminGeneralController::class,'whatsappConfig'])->name('admin.whatsapp.config');
    Route::get('/dashboard/charts/{category}/{duration}',[AdminGeneralController::class,'chartsData'])->name('admin.charts');
    Route::get('/recent-reports', [AdminGeneralController::class, 'getRecentReports'])->name('admin.recent-reports');
    Route::get('/financial-overview', [AdminGeneralController::class, 'getFinancialOverview'])->name('admin.financial-overview');
    Route::get('/pending-requests', [AdminGeneralController::class, 'getPendingRequests'])->name('admin.pending-requests');
    Route::post('/request/{id}/update-status', [AdminGeneralController::class, 'updateRequestStatus'])->name('admin.request.update-status');

    // Department Routes
    Route::get('/departments', [DepartmentController::class, 'index'])->name('admin.departments');
    Route::get('/departments/create', [DepartmentController::class, 'create']);
    Route::get('/departments/show/{uuid}', [DepartmentController::class, 'show']);
    Route::get('/departments/edit/{uuid}', [DepartmentController::class, 'edit']);
    Route::post('/departments/store', [DepartmentController::class, 'store']);
    Route::delete('/departments/delete/{uuid}', [DepartmentController::class, 'destroy']);
    Route::get('/departments/list', [DepartmentController::class, 'list'])->name('admin.departments.list');
    Route::get('/departments/overview', [DepartmentController::class, 'overview'])->name('admin.department.overview');
    Route::get('/departments/summary', [DepartmentController::class, 'summary'])->name('admin.departments.summary');

    // Designation Routes
    Route::get('/designations', [DesignationController::class, 'index'])->name('admin.designations');
    Route::get('/designations/create', [DesignationController::class, 'create']);
    Route::get('/designations/show/{uuid}', [DesignationController::class, 'show']);
    Route::get('/designations/edit/{uuid}', [DesignationController::class, 'edit']);
    Route::post('/designations/store', [DesignationController::class, 'store']);
    Route::delete('/designations/delete/{uuid}', [DesignationController::class, 'destroy']);

    /***
     * Courses
     */
    Route::get('/course',[AdminServicesController::class,'index'])->name('admin.course');
    Route::get('/course/create',[AdminServicesController::class,'create']);
    Route::get('/course/show/{uuid}',[AdminServicesController::class,'show']);
    Route::get('/course/edit/{uuid}',[AdminServicesController::class,'edit']);
    Route::post('/course/store',[AdminServicesController::class,'store']);
    Route::delete('/course/delete/{uuid}',[AdminServicesController::class,'destroy']);
    Route::get('/course/report/{name}',[AdminServicesController::class,'report']);

    /***
     * Testimonies
     */
    Route::get('/testimony', [TestimoniesController::class, 'index'])->name('admin.testimony');
    Route::get('/testimony/create', [TestimoniesController::class, 'create']);
    Route::get('/testimony/show/{uuid}', [TestimoniesController::class, 'show']);
    Route::get('/testimony/edit/{uuid}', [TestimoniesController::class, 'edit']);
    Route::post('/testimony/store', [TestimoniesController::class, 'store']);
    Route::delete('/testimony/delete/{uuid}', [TestimoniesController::class, 'destroy']);
    Route::get('/testimony/report/{name}', [TestimoniesController::class, 'report']);

    /***
     * FAQ
     */
    Route::get('/faq',[AdminFaqController::class,'index'])->name('admin.faq');
    Route::get('/faq/create',[AdminFaqController::class,'create']);
    Route::get('/faq/show/{uuid}',[AdminFaqController::class,'show']);
    Route::get('/faq/edit/{uuid}',[AdminFaqController::class,'edit']);
    Route::post('/faq/store',[AdminFaqController::class,'store']);
    Route::delete('/faq/delete/{uuid}',[AdminFaqController::class,'destroy']);
    Route::get('/faq/report/{name}',[AdminFaqController::class,'report']);

    /***
     * inquiries
     */
    Route::get('/inquiry',[AdminInquiriesController::class,'index'])->name('admin.inquiry');
    Route::get('/inquiry/create',[AdminInquiriesController::class,'create']);
    Route::get('/inquiry/show/{uuid}',[AdminInquiriesController::class,'show']);
    Route::get('/inquiry/edit/{uuid}',[AdminInquiriesController::class,'edit']);
    Route::post('/inquiry/store',[AdminInquiriesController::class,'store']);
    Route::delete('/inquiry/delete/{uuid}',[AdminInquiriesController::class,'destroy']);
    Route::get('/inquiry/report/{name}',[AdminInquiriesController::class,'report']);

    /***
     * feedback
     */
    Route::get('/feedback',[AdminFeedbackController::class,'index'])->name('admin.feedback');
    Route::get('/feedback/create',[AdminFeedbackController::class,'create']);
    Route::get('/feedback/show/{uuid}',[AdminFeedbackController::class,'show']);
    Route::get('/feedback/edit/{uuid}',[AdminFeedbackController::class,'edit']);
    Route::post('/feedback/store',[AdminFeedbackController::class,'store']);
    Route::delete('/feedback/delete/{uuid}',[AdminFeedbackController::class,'destroy']);
    Route::get('/feedback/report/{name}',[AdminFeedbackController::class,'report']);

    /***
     * news
     */
    Route::get('/news',[NewsController::class,'index'])->name('admin.news');
    Route::get('/news/create',[NewsController::class,'create']);
    Route::get('/news/show/{uuid}',[NewsController::class,'show']);
    Route::get('/news/edit/{uuid}',[NewsController::class,'edit']);
    Route::post('/news/store',[NewsController::class,'store']);
    Route::delete('/news/delete/{uuid}',[NewsController::class,'destroy']);
    Route::get('/news/report/{name}',[NewsController::class,'report']);

    /***
     * events
     */
    Route::get('/event',[EventController::class,'index'])->name('admin.event');
    Route::get('/event/create',[EventController::class,'create']);
    Route::get('/event/show/{uuid}',[EventController::class,'show']);
    Route::get('/event/edit/{uuid}',[EventController::class,'edit']);
    Route::post('/event/store',[EventController::class,'store']);
    Route::delete('/event/delete/{uuid}',[EventController::class,'destroy']);
    Route::get('/event/report/{name}',[EventController::class,'report']);
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('admin.events.calendar');

    /***
     * staff
     */
    Route::get('/staff',[StaffController::class,'index'])->name('admin.staff');
    Route::get('/staff/create',[StaffController::class,'create']);
    Route::get('/staff/show/{uuid}',[StaffController::class,'show']);
    Route::get('/staff/edit/{uuid}',[StaffController::class,'edit']);
    Route::post('/staff/store',[StaffController::class,'store']);
    Route::delete('/staff/delete/{uuid}',[StaffController::class,'destroy']);
    Route::get('/staff/report/{name}',[StaffController::class,'report']);

    /***
     * gallery
     */
    Route::get('/gallery',[GalleryController::class,'index'])->name('admin.gallery');
    Route::get('/gallery/create',[GalleryController::class,'create']);
    Route::get('/gallery/show/{uuid}',[GalleryController::class,'show']);
    Route::get('/gallery/edit/{uuid}',[GalleryController::class,'edit']);
    Route::post('/gallery/store',[GalleryController::class,'store']);
    Route::delete('/gallery/delete/{uuid}',[GalleryController::class,'destroy']);
    Route::get('/gallery/report/{name}',[GalleryController::class,'report']);

    /***
     * ict-centre-gallery
     */
    Route::get('/ict-centre-gallery',[ICTContentController::class,'index'])->name('admin.ict-centre-gallery');
    Route::get('/ict-centre-gallery/create',[ICTContentController::class,'create']);
    Route::get('/ict-centre-gallery/show/{uuid}',[ICTContentController::class,'show']);
    Route::get('/ict-centre-gallery/edit/{uuid}',[ICTContentController::class,'edit']);
    Route::post('/ict-centre-gallery/store',[ICTContentController::class,'store']);
    Route::delete('/ict-centre-gallery/delete/{uuid}',[ICTContentController::class,'destroy']);
    Route::get('/ict-centre-gallery/report/{name}',[ICTContentController::class,'report']);

    /***
     * course
     */
    Route::get('/course',[CourseController::class,'index'])->name('admin.course');
    Route::get('/course/create',[CourseController::class,'create']);
    Route::get('/course/show/{uuid}',[CourseController::class,'show']);
    Route::get('/course/edit/{uuid}',[CourseController::class,'edit']);
    Route::post('/course/store',[CourseController::class,'store']);
    Route::delete('/course/delete/{uuid}',[CourseController::class,'destroy']);
    Route::get('/course/report/{name}',[CourseController::class,'report']);

    /***
     * fee-structures
     */
    Route::get('/fee-structure',[FeeStructure::class,'index'])->name('admin.fee-structures');
    Route::get('/upload-fee-structure',[AttachmentsController::class,'feeStructure'])->name('admin.upload-fee-structure');
    Route::post('/fee-structure', [FeeStructure::class, 'store'])->name('admin.fees.store');
    Route::put('/fee-structure/{fee}', [FeeStructure::class, 'update'])->name('admin.fees.update');

    /***
     * videos gallery
     */
    Route::get('/video-gallery',[VideosController::class,'index'])->name('admin.video-gallery');
    Route::get('/video-gallery/create',[VideosController::class,'create']);
    Route::get('/video-gallery/show/{uuid}',[VideosController::class,'show']);
    Route::get('/video-gallery/edit/{uuid}',[VideosController::class,'edit']);
    Route::post('/video-gallery/store',[VideosController::class,'store']);
    Route::delete('/video-gallery/delete/{uuid}',[VideosController::class,'destroy']);
    Route::get('/video-gallery/report/{name}',[VideosController::class,'report']);

    Route::post('/video-gallery/upload-chunk', [VideosController::class, 'uploadChunk'])
        ->name('admin.video-gallery.upload-chunk');
    Route::post('video-gallery/merge-chunks', [VideosController::class, 'mergeChunks'])
        ->name('admin.video-gallery.merge-chunks');

    /***
     * application
     */
    Route::get('/application',[ApplicationsController::class,'index'])->name('admin.application');
    Route::get('/application/create',[ApplicationsController::class,'create']);
    Route::get('/application/show/{uuid}',[ApplicationsController::class,'show']);
    Route::get('/application/edit/{uuid}',[ApplicationsController::class,'edit']);
    Route::post('/application/store',[ApplicationsController::class,'store']);
    Route::delete('/application/delete/{uuid}',[ApplicationsController::class,'destroy']);
    Route::get('/application/report/{name}',[ApplicationsController::class,'report']);

    /***
     * scholarship application
     */
    Route::get('/scholarship-application',[ScholarshipApplicationController::class,'index'])->name('admin.scholarship-application');
    Route::get('/scholarship-application/create',[ScholarshipApplicationController::class,'create']);
    Route::get('/scholarship-application/show/{uuid}',[ScholarshipApplicationController::class,'show']);
    Route::get('/scholarship-application/edit/{uuid}',[ScholarshipApplicationController::class,'edit']);
    Route::post('/scholarship-application/store',[ScholarshipApplicationController::class,'store']);
    Route::delete('/scholarship-application/delete/{uuid}',[ScholarshipApplicationController::class,'destroy']);
    Route::get('/scholarship-application/report/{name}',[ScholarshipApplicationController::class,'report']);

    /***
     * Partners
     */
    Route::get('/partner', [PartnerController::class, 'index'])->name('admin.partner');
    Route::get('/partner/create', [PartnerController::class, 'create']);
    Route::get('/partner/show/{uuid}', [PartnerController::class, 'show']);
    Route::get('/partner/edit/{uuid}', [PartnerController::class, 'edit']);
    Route::post('/partner/store', [PartnerController::class, 'store']);
    Route::delete('/partner/delete/{uuid}', [PartnerController::class, 'destroy']);
    Route::get('/partner/report/{name}', [PartnerController::class, 'report']);

    /***
     * Awards
     */
    Route::get('/award', [AwardController::class, 'index'])->name('admin.award');
    Route::get('/award/create', [AwardController::class, 'create']);
    Route::get('/award/show/{uuid}', [AwardController::class, 'show']);
    Route::get('/award/edit/{uuid}', [AwardController::class, 'edit']);
    Route::post('/award/store', [AwardController::class, 'store']);
    Route::delete('/award/delete/{uuid}', [AwardController::class, 'destroy']);
    Route::get('/award/report/{name}', [AwardController::class, 'report']);

    /***
     * Success Stories
     */
    Route::get('/success-story', [SuccessStoryController::class, 'index'])->name('admin.success-story');
    Route::get('/success-story/create', [SuccessStoryController::class, 'create']);
    Route::get('/success-story/show/{uuid}', [SuccessStoryController::class, 'show']);
    Route::get('/success-story/edit/{uuid}', [SuccessStoryController::class, 'edit']);
    Route::post('/success-story/store', [SuccessStoryController::class, 'store']);
    Route::delete('/success-story/delete/{uuid}', [SuccessStoryController::class, 'destroy']);
    Route::get('/success-story/report/{name}', [SuccessStoryController::class, 'report']);

    /***
     * Training Modules
     */
    Route::get('/training-module', [TrainingModuleController::class, 'index'])->name('admin.training-module');
    Route::get('/training-module/create', [TrainingModuleController::class, 'create']);
    Route::get('/training-module/show/{uuid}', [TrainingModuleController::class, 'show']);
    Route::get('/training-module/edit/{uuid}', [TrainingModuleController::class, 'edit']);
    Route::post('/training-module/store', [TrainingModuleController::class, 'store']);
    Route::delete('/training-module/delete/{uuid}', [TrainingModuleController::class, 'destroy']);
    Route::get('/training-module/report/{name}', [TrainingModuleController::class, 'report']);

    /***
     * Course Types
     */
    Route::get('/course-type', [CourseTypeController::class, 'index'])->name('admin.course-type');
    Route::get('/course-type/create', [CourseTypeController::class, 'create']);
    Route::get('/course-type/show/{uuid}', [CourseTypeController::class, 'show']);
    Route::get('/course-type/edit/{uuid}', [CourseTypeController::class, 'edit']);
    Route::post('/course-type/store', [CourseTypeController::class, 'store']);
    Route::delete('/course-type/delete/{uuid}', [CourseTypeController::class, 'destroy']);
    Route::get('/course-type/report/{name}', [CourseTypeController::class, 'report']);

    /***
     * Fee Structures
     */
    Route::get('/fee-structure', [FeeStructureController::class, 'index'])->name('admin.fee-structure');
    Route::get('/fee-structure/create', [FeeStructureController::class, 'create']);
    Route::get('/fee-structure/show/{uuid}', [FeeStructureController::class, 'show']);
    Route::get('/fee-structure/edit/{uuid}', [FeeStructureController::class, 'edit']);
    Route::post('/fee-structure/store', [FeeStructureController::class, 'store']);
    Route::delete('/fee-structure/delete/{uuid}', [FeeStructureController::class, 'destroy']);
    Route::get('/fee-structure/report/{name}', [FeeStructureController::class, 'report']);

    /***
     * Payment Options
     */
    Route::get('/payment-option', [PaymentOptionController::class, 'index'])->name('admin.payment-option');
    Route::get('/payment-option/create', [PaymentOptionController::class, 'create']);
    Route::get('/payment-option/show/{uuid}', [PaymentOptionController::class, 'show']);
    Route::get('/payment-option/edit/{uuid}', [PaymentOptionController::class, 'edit']);
    Route::post('/payment-option/store', [PaymentOptionController::class, 'store']);
    Route::delete('/payment-option/delete/{uuid}', [PaymentOptionController::class, 'destroy']);
    Route::get('/payment-option/report/{name}', [PaymentOptionController::class, 'report']);

    /***
     * Registration Fees
     */
    Route::get('/registration-fee', [RegistrationFeeController::class, 'index'])->name('admin.registration-fee');
    Route::get('/registration-fee/create', [RegistrationFeeController::class, 'create']);
    Route::get('/registration-fee/show/{uuid}', [RegistrationFeeController::class, 'show']);
    Route::get('/registration-fee/edit/{uuid}', [RegistrationFeeController::class, 'edit']);
    Route::post('/registration-fee/store', [RegistrationFeeController::class, 'store']);
    Route::delete('/registration-fee/delete/{uuid}', [RegistrationFeeController::class, 'destroy']);
    Route::get('/registration-fee/report/{name}', [RegistrationFeeController::class, 'report']);

    // Department Head Routes
    Route::get('/department-head', [DepartmentHeadController::class, 'index'])->name('admin.department-head');
    Route::get('/department-head/create', [DepartmentHeadController::class, 'create']);
    Route::get('/department-head/show/{uuid}', [DepartmentHeadController::class, 'show']);
    Route::get('/department-head/edit/{uuid}', [DepartmentHeadController::class, 'edit']);
    Route::post('/department-head/store', [DepartmentHeadController::class, 'store']);
    Route::delete('/department-head/delete/{uuid}', [DepartmentHeadController::class, 'destroy']);

    // Department Goal Routes
    Route::get('/department-goal', [DepartmentGoalController::class, 'index'])->name('admin.department-goal');
    Route::get('/department-goal/create', [DepartmentGoalController::class, 'create']);
    Route::get('/department-goal/show/{uuid}', [DepartmentGoalController::class, 'show']);
    Route::get('/department-goal/edit/{uuid}', [DepartmentGoalController::class, 'edit']);
    Route::post('/department-goal/store', [DepartmentGoalController::class, 'store']);
    Route::delete('/department-goal/delete/{uuid}', [DepartmentGoalController::class, 'destroy']);

    // Department Activity Routes
    Route::get('/department-activity', [DepartmentActivityController::class, 'index'])->name('admin.department-activity');
    Route::get('/department-activity/create', [DepartmentActivityController::class, 'create']);
    Route::get('/department-activity/show/{uuid}', [DepartmentActivityController::class, 'show']);
    Route::get('/department-activity/edit/{uuid}', [DepartmentActivityController::class, 'edit']);
    Route::post('/department-activity/store', [DepartmentActivityController::class, 'store']);
    Route::delete('/department-activity/delete/{uuid}', [DepartmentActivityController::class, 'destroy']);

    // Department Requests 
    Route::get('/department-requests', [DepartmentRequestController::class, 'index'])->name('admin.department-requests');
    Route::get('/department-requests/create', [DepartmentRequestController::class, 'create'])->name('admin.department-requests.create');
    Route::post('/department-requests', [DepartmentRequestController::class, 'store'])->name('admin.department-requests.store');
    Route::get('/department-requests/{uuid}', [DepartmentRequestController::class, 'show'])->name('admin.department-requests.show');
    Route::get('/department-requests/{uuid}/edit', [DepartmentRequestController::class, 'edit'])->name('admin.department-requests.edit');
    Route::put('/department-requests/{uuid}', [DepartmentRequestController::class, 'update'])->name('admin.department-requests.update');
    Route::delete('/department-requests/{uuid}', [DepartmentRequestController::class, 'destroy'])->name('admin.department-requests.destroy');

    Route::get('/departments-for-modal', [DepartmentRequestController::class, 'getDepartments'])
        ->name('admin.departments-for-modal');

    //// Fiscal Year Routes
    Route::get('/fiscal', [FiscalYearController::class, 'index'])->name('admin.fiscal');
    Route::get('/fiscal/create', [FiscalYearController::class, 'create']);
    Route::get('/fiscal/show/{uuid}', [FiscalYearController::class, 'show']);
    Route::get('/fiscal/edit/{uuid}', [FiscalYearController::class, 'edit']);
    Route::post('/fiscal/store', [FiscalYearController::class, 'store']);
    Route::delete('/fiscal/delete/{uuid}', [FiscalYearController::class, 'destroy']);

    // Fiscal Period Routes
    Route::get('/fiscal-period', [FiscalPeriodController::class, 'index'])->name('admin.fiscal-period');
    Route::get('/fiscal-period/create', [FiscalPeriodController::class, 'create']);
    Route::get('/fiscal-period/show/{uuid}', [FiscalPeriodController::class, 'show']);
    Route::get('/fiscal-period/edit/{uuid}', [FiscalPeriodController::class, 'edit']);
    Route::post('/fiscal-period/store', [FiscalPeriodController::class, 'store']);
    Route::delete('/fiscal-period/delete/{uuid}', [FiscalPeriodController::class, 'destroy']);
    
    // Change Password Routes
    Route::get('/settings/change-password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/settings/change-password', [AdminPasswordController::class, 'update'])->name('password.change');
});

Route::group([], __DIR__.'/system.php');