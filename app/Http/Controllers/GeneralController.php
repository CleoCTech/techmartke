<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Faq;
use App\Models\Gallery;
use App\Models\Inquiry;
use App\Models\News;
use App\Models\Partner;
use App\Models\System\AdminUser;
use App\Models\System\Attachment;
use App\Models\System\CompanyInformation;
use App\Models\System\SocialMedia;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Inertia\Inertia;

class GeneralController extends Controller
{
    use NotificationTrait;
    use LayoutTrait;
    use UploadFileTrait;

    public function __construct(){
        $this->middleware('gen-auth')->only('dashboard');
    }

    public function home()
    {
        return Inertia::render('Pages/Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function galleries()
    {
        // Fetch campus gallery
        $campusGallery = Gallery::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Guest/Pages/Gallery', [
            'campusGallery' => $campusGallery,
        ]);
    }

    public function dashboard(){
        if(Auth::user()->user_category == 100){
            $user = AdminUser::select('status','role')->where('email',Auth::user()->email)->first();
            $user['profile_category'] = 'admin';
            Auth::user()->profile = $user;
            return redirect('/admin/dashboard');
        }else{
            return Inertia::render('Admin/Pages/Dashboard');
        }
    }

    public function showNews($slug) {
        $news = News::where('status',2)
            ->where('slug', $slug)->firstOrFail();
        
        return Inertia::render('Guest/Pages/News/Show', [
            'news' => $news,
        ]);
    }

    public function news() {
        $news = News::where('status',2)
            ->orWhere(function($query){
                $query->where('status',3)
                    ->where('publish_time','<=',date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/News/Index', [
            'news' => $news,
        ]);
    }

    public function showEvent($slug) {
        $event = Event::where('status',2)
            ->where('slug', $slug)->firstOrFail();
        
        return Inertia::render('Guest/Pages/Events/Show', [
            'event' => $event,
        ]);
    }

    public function events() {
        $events = Event::where('status',2)
            ->orWhere(function($query){
                $query->where('status',3)
                    ->where('publish_time','<=',date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/Events/Index', [
            'events' => $events,
        ]);
    }

    public function faq()
    {
        $faqs = Faq::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/FAQ', [
            'faqs' => $faqs,
        ]);
    }


    public function footerData(){
        $companyInfo = CompanyInformation::first();
        $socialMedias = SocialMedia::where('status',2)->get();
        $data = [
            'companyInfo' => $companyInfo,
            'socialMedias' => $socialMedias,
        ];
        return $data;
    }

    public function topBarData(){
        $companyInfo = CompanyInformation::first();
        $socialMedias = SocialMedia::where('status',2)->get();
        $data = [
            'companyInfo' => $companyInfo,
            'socialMedias' => $socialMedias,
        ];
        return $data;
    }

    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            Inquiry::create([
                'name' => $request->first_name.' ' .$request->last_name,
                'email' => $request->email,
                'phone_number' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' =>1,
            ]);
            DB::commit();
            $data = [
                'success' => config('app.defaultErrors.crud.created')
            ];
            return $data;
        } catch (\Throwable $th) {
            DB::rollBack();
            $data = [
                'error' => config('app.defaultErrors.crud.error')
            ];
            return $data;
        }
    }
}
