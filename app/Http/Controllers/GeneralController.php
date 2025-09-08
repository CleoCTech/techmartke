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
use App\Models\SuccessStory;
use App\Models\System\Course as SystemCourse;
use App\Models\Testimonial;
use App\Models\TrainingModule;
use App\Models\CourseType;
use App\Models\FeeStructure;
use App\Models\PaymentOption;
use App\Models\RegistrationFee;
use App\Models\ScholarshipApplication;
use App\Models\AlbumCollection;

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
        // Fetch testimonials (using Testmony model) – filter by status 2 or (status 3 and publish_time <= now)
        $testimonials = Testimonial::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        // Fetch campus gallery (using Gallery model) – filter by status 2 or (status 3 and publish_time <= now)
        $campusGallery = Gallery::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        // Fetch success stories (using Testmony model – you may choose to filter or map further if needed)
        $successStories = SuccessStory::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        // Fetch course preview (using Course model) – filter by status 2 or (status 3 and publish_time <= now)
        $coursePreview = SystemCourse::where('status', 2)->get();

        // Fetch faculty spotlight (using Staff model) – filter by status 2 or (status 3 and publish_time <= now)
        $facultySpotlight = Staff::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        // Fetch upcoming events slider (using Event model) – filter by status 2 or (status 3 and publish_time <= now)
        $upcomingEventsSlider = Event::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })->get();

        // Fetch video gallery (using Video model) – filter by status 2 or (status 3 and publish_time <= now)
        $videoGallery = Video::where('status', 2)->get();

        // Fetch album collections for gallery section
        $albumCollections = AlbumCollection::with('images')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        $firstAttachment = Attachment::where('is_archived',0)
        ->where('description','like','%Application Form%')
        ->where('table_id','undefined')
        ->first();

        // dd($firstAttachment);

        return Inertia::render('Guest/Pages/Home', [
            'testimonials' => $testimonials,
            'campusGallery' => $campusGallery,
            'successStories' => $successStories,
            'coursePreview' => $coursePreview,
            'facultySpotlight' => $facultySpotlight,
            'upcomingEventsSlider' => $upcomingEventsSlider,
            'videoGallery' => $videoGallery,
            'albumCollections' => $albumCollections,
            'firstAttachment' => $firstAttachment,
            // ...other props as needed
        ]);
    }

    public function galleries()
    {
        // Fetch all published album collections with their images
        $albumCollections = AlbumCollection::with('images')
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        // Fetch featured album collections
        $featuredAlbums = AlbumCollection::with('images')
            ->where('is_published', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Fetch campus gallery for featured slider (same as Home.vue carouselImages)
        $campusGallery = Gallery::where('status', 2)
            ->orWhere(function($query) {
                $query->where('status', 3)
                    ->where('publish_time', '<=', date('Y-m-d h:i:s'));
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Fetch video gallery for the gallery page
        $videoGallery = Video::where('status', 2)->get();

        return Inertia::render('Guest/Pages/Gallery', [
            'albumCollections' => $albumCollections,
            'featuredAlbums' => $featuredAlbums,
            'campusGallery' => $campusGallery,
            'videoGallery' => $videoGallery,
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

    public function generalApplicationPost(Request $request)
    {
        info("generalApplicationPost");
        
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'program' => 'required|string|max:255',
                'start_date' => 'required|string|max:255',
                'study_format' => 'required|in:in-person,online,hybrid',
                'career_goals' => 'required|string',
                'hear_about_us' => 'nullable|string|max:255',
                'additional_info' => 'nullable|string',
                'terms_accepted' => 'required|in:0,1',
                'marketing_consent' => 'in:0,1',
            ]);
            info('after validation');
        
            DB::beginTransaction();
            
            $app = Application::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'date_of_birth' => $request->date_of_birth,
                'program' => $request->program,
                'start_date' => $request->start_date,
                'study_format' => $request->study_format,
                'career_goals' => $request->career_goals,
                'hear_about_us' => $request->hear_about_us,
                'additional_info' => $request->additional_info,
                'terms_accepted' => $request->terms_accepted === '1',
                'marketing_consent' => $request->marketing_consent === '1',
                'status' => 1,
            ]);

            DB::commit();
            return response()->json([
                'success' => 'Application submitted successfully! We will contact you within 2-3 business days.'
            ]);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            info('Validation error: ' . json_encode($e->errors()));
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json([
                'error' => config('app.defaultErrors.default')
            ], 500);
        }
    }


    public function scholarshipApplicationPost(Request $request)
    {
        info("scholarshipApplicationPost");
        $validated = $request->validate([
            'type' => 'required|in:student,professional',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $scholarshipApp = ScholarshipApplication::create([
                'type' => $request->type,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'notes' => $request->notes,
                'status' => 1, // New
            ]);

            // Handle file uploads if any
            if($request->hasFile('application_form')){
                $fileName = time() . '_' . $request->file('application_form')->getClientOriginalName();
                $record = [
                    'filename' => $fileName,
                    'description' => 'Scholarship Application Form',
                    'table_id' => 'scholarship_applications',
                    'record_id' => $scholarshipApp->uuid,
                    'storageName' => 'scholarshipApplications',
                    'created_by' => $request->email,
                ];
                Attachment::create($record);
                $request->file('application_form')->storeAs('public/attachments/scholarship_applications', $fileName);
            }

            if($request->hasFile('supporting_documents')){
                foreach($request->file('supporting_documents') as $file){
                    $fileName = time() . '_' . $file->getClientOriginalName();
                    $record = [
                        'filename' => $fileName,
                        'description' => 'Supporting Document',
                        'table_id' => 'scholarship_applications',
                        'record_id' => $scholarshipApp->uuid,
                        'storageName' => 'scholarshipApplications',
                        'created_by' => $request->email,
                    ];
                    Attachment::create($record);
                    $file->storeAs('public/attachments/scholarship_applications', $fileName);
                }
            }

            DB::commit();
            return response()->json([
                'success' => 'Scholarship application submitted successfully! We will review your application and contact you within 2 weeks.'
            ]);
            
        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json([
                'error' => config('app.defaultErrors.default')
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
        // Get all active training modules with their fee structures
        $modules = TrainingModule::active()
            ->with(['feeStructures' => function($query) {
                $query->active()->with('courseType');
            }])
            ->orderBy('sort_order')
            ->get()
            ->map(function($module) {
                return [
                    'id' => $module->id,
                    'title' => $module->title,
                    'courses' => $module->feeStructures->map(function($feeStructure) {
                        return [
                            'type' => $feeStructure->courseType->name,
                            'fee' => $feeStructure->fee,
                            'duration' => $feeStructure->duration,
                            'color_class' => $feeStructure->courseType->color_class
                        ];
                    })
                ];
            });

        // Get payment options
        $paymentOptions = PaymentOption::active()
            ->orderBy('sort_order')
            ->get()
            ->map(function($option) {
                return [
                    'title' => $option->title,
                    'description' => $option->description,
                    'benefit' => $option->benefit,
                    'icon' => $option->icon
                ];
            });

        // Get registration fee
        $registrationFee = RegistrationFee::active()->first();

        return Inertia::render('Guest/Pages/TrainingFees', [
            'modules' => $modules,
            'paymentOptions' => $paymentOptions,
            'registrationFee' => $registrationFee,
        ]);
    }

    public function scholarships()
    {
        return Inertia::render('Guest/Pages/Scholarships');
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
        $testimonials = Testimonial::where('status', 2)
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

    public function downloadApplicationForm($uuid){
        $cardData = Attachment::where('uuid',$uuid)->first();
        if($cardData == null){
            return redirect('/')->with('error',"Not Found");
        }
        $file = public_path('storage/attachments/admin/') . $cardData->filename;

        if (!file_exists($file)) {
            return redirect('/')->with('error', "File does not exist");
        }
        
        return response()->file($file);
    }

}
