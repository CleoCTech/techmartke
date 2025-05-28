<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Event;
use App\Models\Faq;
use App\Models\Fee;
use App\Models\Gallery;
use App\Models\ICTContent;
use App\Models\Inquiry;
use App\Models\News;
use App\Models\Staff;
use App\Models\System\AdminUser;
use App\Models\System\Attachment;
use App\Models\System\CompanyInformation;
use App\Models\System\SocialMedia;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Traits\Admin\LayoutTrait;
use App\Traits\Admin\UploadFileTrait;
use App\Traits\NotificationTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\Course;
use App\Models\Partner;
use App\Models\Testmony;
use App\Models\Award;

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
        // $companyInfo = CompanyInformation::first();
        // $staff = Staff::where('status',2)->orWhere(function($query){
        //         $query->where('status',3)
        //         ->where('publish_time','<=',date('Y-m-d h:i:s'));
        //     })->get();
        // $news = News::where('status',2)->orWhere(function($query){
        //         $query->where('status',3)
        //         ->where('publish_time','<=',date('Y-m-d h:i:s'));
        //     })->get();
        // $events = Event::where('status',2)->orWhere(function($query){
        //         $query->where('status',3)
        //         ->where('publish_time','<=',date('Y-m-d h:i:s'));
        //     })->get();
           
        // $video = Video::where('status', 2)
        // ->orderBy('sequence', 'ASC')
        // ->first();
        // $stats = Statistic::where('status', 2)
        // ->orderBy('sequence', 'ASC')
        // ->get();

        // $directory = public_path('storage/companyinfo/images/carousel');
        // $files = File::files($directory);
        // $images = [];
        // //../../../../public/storage/companyinfo/images/3.jpeg
        // foreach ($files as $file) {
        //     $images[] = asset('storage/companyinfo/images/carousel/' . $file->getFilename());
        // }
        // $images = [
        //     url('storage/companyInfo/images/carousel/1.jpeg'),
        //     // url('storage/companyinfo/images/glenn-carstens-peters-npxXWgQ33ZQ-unsplash.jpg'),
        //     // url('storage/companyinfo/images/kevin-bhagat-zNRITe8NPqY-unsplash.jpg'),
        //     // url('storage/companyinfo/images/luca-bravo-9l_326FISzk-unsplash.jpg'),
        //     url('storage/companyInfo/images/carousel/2.jpeg'),
        //     url('storage/companyInfo/images/carousel/3.jpeg'),
        // ];

        return Inertia::render('Guest/Pages/Home', [
            // 'companyLogoUrl' => '',
            // 'staffs' =>$staff,
            // 'news' =>$news,
            // 'events' =>$events,
            // 'video' =>$video,
            'companyLogoUrl' => '',
            'staffs' =>[],
            'news' =>[],
            'events' =>[],
            'video' =>[],
        ]);
    }

    public function dashboard(){
        // dd('her');
        if(Auth::user()->user_category == 100){
            $user = AdminUser::select('status','role')->where('email',Auth::user()->email)->first();
            // if($user == null || $user->role == null){
            //     Auth::logout();
            //     return redirect('/login')->with('error','Oops! your authorization as an admin failed.');
            // }
            // $role = UserRole::find($user->role);
            // if($role == null){
            //     Auth::logout();
            //     return redirect('/login')->with('error','Oops! the user has not been assigned a role in the system');
            // }
            // // info( $user['profile_category']);
            $user['profile_category'] = 'admin';
            // // $user['permissions'] = $role->permissions;
            Auth::user()->profile = $user;
            return redirect('/admin/dashboard');
        }else{
            return Inertia::render('Admin/Pages/Dashboard');
        }
    }

    public function contact()
    {
        
        $images = [
            url('storage/companyinfo/images/1.jpeg'),
            // url('storage/companyinfo/images/glenn-carstens-peters-npxXWgQ33ZQ-unsplash.jpg'),
            // url('storage/companyinfo/images/kevin-bhagat-zNRITe8NPqY-unsplash.jpg'),
            // url('storage/companyinfo/images/luca-bravo-9l_326FISzk-unsplash.jpg'),
            url('storage/companyinfo/images/2.jpeg'),
            url('storage/companyinfo/images/3.jpeg'),
        ];

        return Inertia::render('Guest/Pages/ContactUs', [
            'companyLogoUrl' => url('storage/companyinfo/logo_no_bg.png'),
        ]);
    }
    public function facilities()
    {
        
        $images = [
            url('storage/companyinfo/images/1.jpeg'),
            // url('storage/companyinfo/images/glenn-carstens-peters-npxXWgQ33ZQ-unsplash.jpg'),
            // url('storage/companyinfo/images/kevin-bhagat-zNRITe8NPqY-unsplash.jpg'),
            // url('storage/companyinfo/images/luca-bravo-9l_326FISzk-unsplash.jpg'),
            url('storage/companyinfo/images/2.jpeg'),
            url('storage/companyinfo/images/3.jpeg'),
        ];

        return Inertia::render('Guest/Pages/FacilityCentre', [
            'companyLogoUrl' => url('storage/companyinfo/logo_no_bg.png'),
        ]);
    }
    public function feestructure()
    {
     
        $fees = Fee::where('status',2)->get();
        $feeStructure = Attachment::latest()->first();
        return Inertia::render('Guest/Pages/FeeStructure', [
            'fees' =>$fees,
            'feeStructure' =>$feeStructure,
        ]);
    }
    public function ictCentre()
    {
        
        $images = [
            url('storage/companyinfo/images/1.jpeg'),
            url('storage/companyinfo/images/2.jpeg'),
            url('storage/companyinfo/images/3.jpeg'),
        ];
        $gallery = ICTContent::where('status',2)->latest()->get();
        return Inertia::render('Guest/Pages/ICTContent/Index', [
            'contents' =>$gallery,
        ]);
    }
    public function gallery()
    {
        
        $images = [
            url('storage/companyinfo/images/1.jpeg'),
            url('storage/companyinfo/images/2.jpeg'),
            url('storage/companyinfo/images/3.jpeg'),
        ];
        $gallery = Gallery::where('status',2)->latest()->get();
        return Inertia::render('Guest/Pages/Gallery', [
            'pics' =>$gallery,
        ]);
    }
    public function aboutUs()
    {
        
        $staff = Staff::where('status',2)->orWhere(function($query){
            $query->where('status',3)
            ->where('publish_time','<=',date('Y-m-d h:i:s'));
        })->get();
        $images = [
            url('storage/companyinfo/images/1.jpeg'),
            // url('storage/companyinfo/images/glenn-carstens-peters-npxXWgQ33ZQ-unsplash.jpg'),
            // url('storage/companyinfo/images/kevin-bhagat-zNRITe8NPqY-unsplash.jpg'),
            // url('storage/companyinfo/images/luca-bravo-9l_326FISzk-unsplash.jpg'),
            url('storage/companyinfo/images/2.jpeg'),
            url('storage/companyinfo/images/3.jpeg'),
        ];
        return Inertia::render('Guest/Pages/Aboutus', [
            'companyLogoUrl' => url('storage/companyinfo/logo_no_bg.png'),
            'staffs' =>$staff,
            // 'images' =>$images,
        ]);
    }
    public function application()
    {
        return Inertia::render('Guest/Pages/ApplicationForm', [
            // 'images' =>$images,
        ]);
    }

    public function footerData(){
        $companyInfo = CompanyInformation::first();
        $gallery = Gallery::where('status',2)->get();
        $socialMedias = SocialMedia::where('status',2)->get();
        // $projects = Project::where('status',2)->orWhere(function($query){
        //     $query->where('status',3)
        //     ->where('publish_time','<=',date('Y-m-d h:i:s'));
        // })->get();
        // $takeActions = TakeAction::get();
        // info($gallery);
        $data = [
            'companyInfo' => $companyInfo,
            'socialMedias' => $socialMedias,
            'galleries' => $gallery,
            // 'services' => $services,
            // 'projects' => $projects,
            // 'takeActions' => $takeActions,
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
            'message' => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            //code...
            Inquiry::create([
                'name' => $request->first_name.' ' .$request->last_name,
                'email' => $request->email,
                'message' => $request->message,
                'status' =>1,
            ]); 
            DB::commit();
            $data = [
                'success' => config('app.defaultErrors.crud.created')
            ];
            return $data;
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return ['error' => config('app.defaultErrors.default')];
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function applicationGuide()
    {
        $faqs = Faq::where('status',2)
        ->orWhere(function($query){
            $query->where('status',3)
            ->where('publish_time','<=',date('Y-m-d h:i:s'));
        })->get();
        return Inertia::render('Guest/Pages/ApplicationGuide', [
            // 'images' =>$images,
            'faqs' =>$faqs,
        ]);
    }
    public function applicationPost(Request $request)
    {
        info("applicationPost");
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|max:255',
            'grade_level'       => 'required|string|max:255',
            // 'birth_document' => 'required|file|mimes:pdf,doc,docx,zip|max:5120', // 5MB max size
            // 'student_photo' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max size and specific image formats  // max size in kilobytes (5MB)
            'father_name'       => 'nullable|string|max:255',
            'mother_name'       => 'nullable|string|max:255',
            'contact_number'    => 'required|string|max:255',
            'present_address'   => 'nullable|string|max:255',
            'date_of_birth'     => 'required|date_format:Y-m-d',
            'birth_cert_no'     => 'required|string|max:255',
            'gender'            => 'required|string|in:Male,Female',
            'current_school'    => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            //code...
            $app = Application::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'father_name' => $request->father_name,
                'mother_name' => $request->mother_name,
                'grade_level' => $request->grade_level,
                'email' => $request->email,
                'contact_number' => $request->contact_number,
                'present_address' => $request->present_address,
                'date_of_birth' => $request->date_of_birth,
                'birth_cert_no' => $request->birth_cert_no,
                'current_school' => $request->current_school,
                'status' =>1,
            ]); 


            if($request->hasFile('attachment')){
                $record = [];
                $storageName = 'applications';
                if($request->hasFile('birth_document')){
                    $fileName = $this->generateFileName($request->file('birth_document'));
                    $record['birth_document'] = $fileName;
                }
                $record = [
                    'filename' => $fileName,
                    'description' => $fileName,
                    'table_id' =>'applications',
                    'record_id' => $app->uuid,
                    'storageName' => 'applications',
                    'created_by' => $request->birth_cert_no,
                ];
                Attachment::create($record);
                $fileData = ['file' => $request->file('birth_document'),'fileName' => $fileName, 'storageName' => $storageName.'\\attachments','prevFile' => null];
                if(!$this->uploadFile($fileData)){
                    $this->isCommit = false;
                }
            }
            if($request->hasFile('student_photo')){
                $record = [];
                $storageName = 'applications';
                if($request->hasFile('student_photo')){
                    $fileName = $this->generateFileName($request->file('student_photo'));
                    $record['student_photo'] = $fileName;
                }
                $record = [
                    'filename' => $fileName,
                    'description' => $fileName,
                    'table_id' =>'applications',
                    'record_id' => $app->uuid,
                    'storageName' => 'applications',
                    'created_by' => $request->birth_cert_no,
                ];
                Attachment::create($record);
                $fileData = ['file' => $request->file('student_photo'),'fileName' => $fileName, 'storageName' => $storageName.'\\attachments','prevFile' => null];
                if(!$this->uploadFile($fileData)){
                    $this->isCommit = false;
                }
            }

            if($this->isCommit){
                DB::Commit();
                $data = [
                    'success' => config('app.defaultErrors.crud.created')
                ];
            }else{
                DB::rollback();
                $data = [
                    'error' => 'Some error occurred'
                ];
            }
           
            
            return $data;
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return ['error' => config('app.defaultErrors.default')];
            return response()->json([
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function showNews($slug) {

        $news = News::where('status',2)
        ->where('slug', $slug)->firstOrFail();
        
        return Inertia::render('Guest/Pages/News/Show', [
            'news' =>$news,
        ]);
    }
    public function news() {

        $news = News::where('status',2)
        ->orWhere(function($query){
            $query->where('status',3)
            ->where('publish_time','<=',date('Y-m-d h:i:s'));
        })->get();;

        return Inertia::render('Guest/Pages/News/Index', [
            'news' =>$news,
        ]);
    }
    public function showEvent($slug) {
        $event = Event::where('status',2)
        ->where('slug', $slug)->firstOrFail();
        
        return Inertia::render('Guest/Pages/Events/Show', [
            'event' =>$event,
        ]);
    }

    public function events() {

        $events = Event::where('status',2)
        ->orWhere(function($query){
            $query->where('status',3)
            ->where('publish_time','<=',date('Y-m-d h:i:s'));
        })->get();;

        return Inertia::render('Guest/Pages/Events/Index', [
            'events' =>$events,
        ]);
    }

    public function about()
    {
        // $companyInfo = CompanyInformation::first();
        // $staff = Staff::where('status', 2)
        //     ->orWhere(function($query) {
        //         $query->where('status', 3)
        //             ->where('publish_time', '<=', date('Y-m-d h:i:s'));
        //     })->get();

        return Inertia::render('Guest/Pages/About', [
            // 'companyInfo' => $companyInfo,
            // 'staffs' => $staff,
            'companyInfo' => [],
            'staffs' => [],
        ]);
    }

    public function faq()
    {
        // $faqs = Faq::where('status', 2)
        //     ->orWhere(function($query) {
        //         $query->where('status', 3)
        //             ->where('publish_time', '<=', date('Y-m-d h:i:s'));
        //     })->get();

        return Inertia::render('Guest/Pages/FAQ', [
            // 'faqs' => $faqs,
        ]);
    }

    public function courses()
    {
        // $courses = Course::where('status', 2)
        //     ->orWhere(function($query) {
        //         $query->where('status', 3)
        //             ->where('publish_time', '<=', date('Y-m-d h:i:s'));
        //     })->get();

        return Inertia::render('Guest/Pages/Courses/Index', [
            // 'courses' => $courses,
            'courses' => [],
        ]);
    }

    public function showCourse($slug)
    {
        $course = Course::where('status', 2)
            ->where('slug', $slug)
            ->firstOrFail();
        
        return Inertia::render('Guest/Pages/Courses/Show', [
            'course' => $course,
        ]);
    }

    public function community()
    {
        // $companyInfo = CompanyInformation::first();
        // $events = Event::where('status', 2)
        //     ->orWhere(function($query) {
        //         $query->where('status', 3)
        //             ->where('publish_time', '<=', date('Y-m-d h:i:s'));
        //     })->get();

        return Inertia::render('Guest/Pages/Community', [
                // 'companyInfo' => $companyInfo,
                // 'events' => $events,
                'companyInfo' => [],
                'events' => [],
            ]);
    }

    public function trainingFees()
    {
        // $fees = Fee::where('status', 2)->get();
        // $feeStructure = Attachment::latest()->first();
        
        return Inertia::render('Guest/Pages/TrainingFees', [
            // 'fees' => $fees,
            // 'feeStructure' => $feeStructure,
            'fees' => [],
            'feeStructure' => [],
        ]);
    }

    public function computerEthics()
    {
        return Inertia::render('Guest/Pages/ComputerEthics');
    }

    public function motto()
    {
        $companyInfo = CompanyInformation::first();
        return Inertia::render('Guest/Pages/Institution/Motto', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function mission()
    {
        $companyInfo = CompanyInformation::first();
        return Inertia::render('Guest/Pages/Institution/Mission', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function vision()
    {
        $companyInfo = CompanyInformation::first();
        return Inertia::render('Guest/Pages/Institution/Vision', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function coreValues()
    {
        $companyInfo = CompanyInformation::first();
        return Inertia::render('Guest/Pages/Institution/CoreValues', [
            'companyInfo' => $companyInfo,
        ]);
    }

    public function partners()
    {
        $partners = Partner::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/Partners', [
            'partners' => $partners,
        ]);
    }

    public function testimonials()
    {
        $testimonials = Testmony::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/Testimonials', [
            'testimonials' => $testimonials,
        ]);
    }

    public function admissionRequirements()
    {
        return Inertia::render('Guest/Pages/Admissions/Requirements');
    }

    public function admissionRegulations()
    {
        return Inertia::render('Guest/Pages/Admissions/Regulations');
    }

    public function awards()
    {
        $awards = Award::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        return Inertia::render('Guest/Pages/Awards', [
            'awards' => $awards,
        ]);
    }

}
