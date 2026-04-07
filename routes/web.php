<?php

use App\Http\Controllers\Admin\EventController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\GeneralController as GuestGeneralContoller;
use App\Http\Controllers\Admin\GeneralController as AdminGeneralController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\BulkUploadController as AdminBulkUploadController;
use App\Http\Controllers\Admin\FaqController as AdminFaqController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\InquiriesController as AdminInquiriesController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\PasswordController as AdminPasswordController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ServicesController as AdminServicesController;
use App\Http\Controllers\Admin\TestimoniesController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\AlbumCollectionController;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\ComparisonController;
use App\Http\Controllers\Customer\TradeInController;
use App\Http\Controllers\Admin\CommunityController as AdminCommunityController;
use App\Http\Controllers\Admin\TradeInController as AdminTradeInController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\AIAssistantController;
use App\Http\Controllers\Customer\CommunityController;
use App\Http\Controllers\Customer\VipController;
use App\Http\Controllers\Admin\VipController as AdminVipController;
use App\Http\Controllers\System\AttachmentsController;

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
 * Customer / Storefront Routes
 */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [GuestGeneralContoller::class, 'dashboard'])->name('dashboard');

// Products
Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [CustomerProductController::class, 'show'])->name('products.show');

// Budget Comparison
Route::get('/compare', [ComparisonController::class, 'compare'])->name('compare');

// Trade-In
Route::get('/trade-in', [TradeInController::class, 'index'])->name('trade-in');
Route::post('/trade-in/estimate', [TradeInController::class, 'estimate'])->name('trade-in.estimate');
Route::post('/trade-in', [TradeInController::class, 'store'])->name('trade-in.store');

// Cart & Checkout
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

// AI Assistant
Route::post('/api/ai-chat', [AIAssistantController::class, 'chat'])->name('ai.chat');

// Community
Route::get('/community', [CommunityController::class, 'index'])->name('community');
Route::get('/community/photos', [CommunityController::class, 'photos'])->name('community.photos');
Route::get('/community/stories', [CommunityController::class, 'stories'])->name('community.stories');
Route::get('/community/stories/{id}', [CommunityController::class, 'showStory'])->name('community.stories.show');
Route::get('/community/questions', [CommunityController::class, 'questions'])->name('community.questions');
Route::post('/community/photos', [CommunityController::class, 'storePhoto'])->name('community.photos.store');
Route::post('/community/stories', [CommunityController::class, 'storeStory'])->name('community.stories.store');
Route::post('/community/questions', [CommunityController::class, 'storeQuestion'])->name('community.questions.store');
Route::post('/community/questions/{questionId}/answers', [CommunityController::class, 'storeAnswer'])->name('community.answers.store');

// VIP Early Access
Route::get('/vip', [VipController::class, 'index'])->name('vip');
Route::post('/vip/register', [VipController::class, 'register'])->name('vip.register');
Route::post('/vip/activate', [VipController::class, 'activate'])->name('vip.activate');
Route::post('/vip/check-status', [VipController::class, 'checkStatus'])->name('vip.check-status');
Route::post('/vip/apply-promo', [VipController::class, 'applyPromo'])->name('vip.apply-promo');

// Common data routes
Route::get('/topbar-data', [GuestGeneralContoller::class, 'topBarData']);
Route::get('/footer-data', [GuestGeneralContoller::class, 'footerData']);
Route::get('/api/whatsapp-config', [AdminGeneralController::class, 'whatsappConfig']);
Route::post('/customer-inquiry', [GuestGeneralContoller::class, 'sendMessage'])->name('inquiry');

/**
 * Admin Routes
 */
Route::prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/dashboard/statistics', [AdminGeneralController::class, 'statistics'])->name('admin.dashboard.statistics');
    Route::get('/dashboard/web-traffic', [AdminGeneralController::class, 'webTraffic'])->name('admin.dashboard.web-traffic');
    Route::get('/dashboard/media-gallery', [AdminGeneralController::class, 'mediaGallery'])->name('admin.dashboard.media-gallery');
    Route::get('/whatsapp-config', [AdminGeneralController::class, 'whatsappConfig'])->name('admin.whatsapp.config');

    /***
     * Products (E-Commerce)
     */
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products/store', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/show/{uuid}', [AdminProductController::class, 'show'])->name('admin.products.show');
    Route::get('/products/edit/{uuid}', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/update/{uuid}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::post('/products/update/{uuid}', [AdminProductController::class, 'update'])->name('admin.products.update.post');
    Route::delete('/products/delete/{uuid}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');

    Route::post('/products/generate-content', [AdminProductController::class, 'generateContent'])->name('admin.products.generate-content');

    /***
     * Bulk Upload
     */
    Route::get('/products/bulk-upload', [AdminBulkUploadController::class, 'index'])->name('admin.bulk-upload');
    Route::post('/products/bulk-upload/parse', [AdminBulkUploadController::class, 'parse'])->name('admin.bulk-upload.parse');
    Route::post('/products/bulk-upload/store', [AdminBulkUploadController::class, 'store'])->name('admin.bulk-upload.store');

    /***
     * Community Management
     */
    Route::get('/community', [AdminCommunityController::class, 'index'])->name('admin.community');
    Route::post('/community/photos/{id}/approve', [AdminCommunityController::class, 'approvePhoto'])->name('admin.community.photos.approve');
    Route::delete('/community/photos/{id}/reject', [AdminCommunityController::class, 'rejectPhoto'])->name('admin.community.photos.reject');
    Route::post('/community/photos/{id}/feature', [AdminCommunityController::class, 'toggleFeaturePhoto'])->name('admin.community.photos.feature');
    Route::post('/community/stories/{id}/approve', [AdminCommunityController::class, 'approveStory'])->name('admin.community.stories.approve');
    Route::delete('/community/stories/{id}/reject', [AdminCommunityController::class, 'rejectStory'])->name('admin.community.stories.reject');
    Route::post('/community/stories/{id}/feature', [AdminCommunityController::class, 'toggleFeatureStory'])->name('admin.community.stories.feature');
    Route::put('/community/questions/{id}', [AdminCommunityController::class, 'updateQuestion'])->name('admin.community.questions.update');
    Route::post('/community/questions/{id}/publish', [AdminCommunityController::class, 'publishQuestion'])->name('admin.community.questions.publish');
    Route::delete('/community/questions/{id}', [AdminCommunityController::class, 'deleteQuestion'])->name('admin.community.questions.delete');

    /***
     * Trade-In Management
     */
    Route::get('/trade-in', [AdminTradeInController::class, 'index'])->name('admin.trade-in');
    Route::get('/trade-in/{uuid}', [AdminTradeInController::class, 'show'])->name('admin.trade-in.show');
    Route::put('/trade-in/{uuid}', [AdminTradeInController::class, 'update'])->name('admin.trade-in.update');
    Route::delete('/trade-in/{uuid}', [AdminTradeInController::class, 'destroy'])->name('admin.trade-in.destroy');

    /***
     * Orders (E-Commerce)
     */
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders');
    Route::get('/orders/show/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::put('/orders/status/{id}', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    /***
     * FAQ
     */
    Route::get('/faq', [AdminFaqController::class, 'index'])->name('admin.faq');
    Route::get('/faq/create', [AdminFaqController::class, 'create']);
    Route::get('/faq/show/{uuid}', [AdminFaqController::class, 'show']);
    Route::get('/faq/edit/{uuid}', [AdminFaqController::class, 'edit']);
    Route::post('/faq/store', [AdminFaqController::class, 'store']);
    Route::delete('/faq/delete/{uuid}', [AdminFaqController::class, 'destroy']);
    Route::get('/faq/report/{name}', [AdminFaqController::class, 'report']);

    /***
     * Events
     */
    Route::get('/event', [EventController::class, 'index'])->name('admin.event');
    Route::get('/event/create', [EventController::class, 'create']);
    Route::get('/event/show/{uuid}', [EventController::class, 'show']);
    Route::get('/event/edit/{uuid}', [EventController::class, 'edit']);
    Route::post('/event/store', [EventController::class, 'store']);
    Route::delete('/event/delete/{uuid}', [EventController::class, 'destroy']);
    Route::get('/event/report/{name}', [EventController::class, 'report']);
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('admin.events.calendar');

    /***
     * Gallery
     */
    Route::get('/gallery', [GalleryController::class, 'index'])->name('admin.gallery');
    Route::get('/gallery/create', [GalleryController::class, 'create']);
    Route::get('/gallery/show/{uuid}', [GalleryController::class, 'show']);
    Route::get('/gallery/edit/{uuid}', [GalleryController::class, 'edit']);
    Route::post('/gallery/store', [GalleryController::class, 'store']);
    Route::delete('/gallery/delete/{uuid}', [GalleryController::class, 'destroy']);
    Route::get('/gallery/report/{name}', [GalleryController::class, 'report']);

    /***
     * Feedback
     */
    Route::get('/feedback', [AdminFeedbackController::class, 'index'])->name('admin.feedback');
    Route::get('/feedback/create', [AdminFeedbackController::class, 'create']);
    Route::get('/feedback/show/{uuid}', [AdminFeedbackController::class, 'show']);
    Route::get('/feedback/edit/{uuid}', [AdminFeedbackController::class, 'edit']);
    Route::post('/feedback/store', [AdminFeedbackController::class, 'store']);
    Route::delete('/feedback/delete/{uuid}', [AdminFeedbackController::class, 'destroy']);
    Route::get('/feedback/report/{name}', [AdminFeedbackController::class, 'report']);

    /***
     * Inquiries
     */
    Route::get('/inquiry', [AdminInquiriesController::class, 'index'])->name('admin.inquiry');
    Route::get('/inquiry/create', [AdminInquiriesController::class, 'create']);
    Route::get('/inquiry/show/{uuid}', [AdminInquiriesController::class, 'show']);
    Route::get('/inquiry/edit/{uuid}', [AdminInquiriesController::class, 'edit']);
    Route::post('/inquiry/store', [AdminInquiriesController::class, 'store']);
    Route::delete('/inquiry/delete/{uuid}', [AdminInquiriesController::class, 'destroy']);
    Route::get('/inquiry/report/{name}', [AdminInquiriesController::class, 'report']);

    /***
     * News
     */
    Route::get('/news', [NewsController::class, 'index'])->name('admin.news');
    Route::get('/news/create', [NewsController::class, 'create']);
    Route::get('/news/show/{uuid}', [NewsController::class, 'show']);
    Route::get('/news/edit/{uuid}', [NewsController::class, 'edit']);
    Route::post('/news/store', [NewsController::class, 'store']);
    Route::delete('/news/delete/{uuid}', [NewsController::class, 'destroy']);
    Route::get('/news/report/{name}', [NewsController::class, 'report']);

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
     * Reports
     */
    Route::get('/report', [ReportController::class, 'index'])->name('admin.report');
    Route::get('/report/create', [ReportController::class, 'create']);
    Route::get('/report/show/{uuid}', [ReportController::class, 'show']);
    Route::get('/report/edit/{uuid}', [ReportController::class, 'edit']);
    Route::post('/report/store', [ReportController::class, 'store']);
    Route::delete('/report/delete/{uuid}', [ReportController::class, 'destroy']);
    Route::get('/report/report/{name}', [ReportController::class, 'report']);

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
     * Services
     */
    Route::get('/course', [AdminServicesController::class, 'index'])->name('admin.course');
    Route::get('/course/create', [AdminServicesController::class, 'create']);
    Route::get('/course/show/{uuid}', [AdminServicesController::class, 'show']);
    Route::get('/course/edit/{uuid}', [AdminServicesController::class, 'edit']);
    Route::post('/course/store', [AdminServicesController::class, 'store']);
    Route::delete('/course/delete/{uuid}', [AdminServicesController::class, 'destroy']);
    Route::get('/course/report/{name}', [AdminServicesController::class, 'report']);

    /***
     * Staff
     */
    Route::get('/staff', [StaffController::class, 'index'])->name('admin.staff');
    Route::get('/staff/create', [StaffController::class, 'create']);
    Route::get('/staff/show/{uuid}', [StaffController::class, 'show']);
    Route::get('/staff/edit/{uuid}', [StaffController::class, 'edit']);
    Route::post('/staff/store', [StaffController::class, 'store']);
    Route::delete('/staff/delete/{uuid}', [StaffController::class, 'destroy']);
    Route::get('/staff/report/{name}', [StaffController::class, 'report']);

    /***
     * Video Gallery
     */
    Route::get('/video-gallery', [VideoController::class, 'index'])->name('admin.video-gallery');
    Route::get('/video-gallery/create', [VideoController::class, 'create']);
    Route::get('/video-gallery/show/{uuid}', [VideoController::class, 'show']);
    Route::get('/video-gallery/edit/{uuid}', [VideoController::class, 'edit']);
    Route::post('/video-gallery/store', [VideoController::class, 'store']);
    Route::delete('/video-gallery/delete/{uuid}', [VideoController::class, 'destroy']);
    Route::get('/video-gallery/report/{name}', [VideoController::class, 'report']);

    /***
     * Album Collections
     */
    Route::get('/album-collections', [AlbumCollectionController::class, 'index'])->name('admin.album-collections');
    Route::get('/album-collections/create', [AlbumCollectionController::class, 'create']);
    Route::get('/album-collections/show/{uuid}', [AlbumCollectionController::class, 'show']);
    Route::get('/album-collections/edit/{uuid}', [AlbumCollectionController::class, 'edit']);
    Route::post('/album-collections/store', [AlbumCollectionController::class, 'store']);
    Route::put('/album-collections/update/{uuid}', [AlbumCollectionController::class, 'update']);
    Route::delete('/album-collections/delete/{uuid}', [AlbumCollectionController::class, 'destroy']);
    Route::delete('/album-collections/{albumUuid}/delete-image/{imageId}', [AlbumCollectionController::class, 'deleteImage'])->name('admin.album-collections.delete-image');
    Route::get('/album-collections/report/{name}', [AlbumCollectionController::class, 'report']);

    /***
     * VIP Management
     */
    Route::get('/vip', [AdminVipController::class, 'index'])->name('admin.vip');
    Route::get('/vip/notifications', [AdminVipController::class, 'notifications'])->name('admin.vip.notifications');
    Route::post('/vip/notifications/send', [AdminVipController::class, 'sendNotification'])->name('admin.vip.notifications.send');
    Route::get('/vip/campaigns', [AdminVipController::class, 'campaigns'])->name('admin.vip.campaigns');
    Route::post('/vip/campaigns', [AdminVipController::class, 'storeCampaign'])->name('admin.vip.campaigns.store');
    Route::put('/vip/subscribers/{id}/toggle', [AdminVipController::class, 'toggleSubscriber'])->name('admin.vip.toggle');

    // Change Password Routes
    Route::get('/settings/change-password', [AdminPasswordController::class, 'edit'])->name('password.edit');
    Route::put('/settings/change-password', [AdminPasswordController::class, 'update'])->name('password.change');
});

Route::group([], __DIR__.'/system.php');


//php artisan products:fetch-images --force --limit=55