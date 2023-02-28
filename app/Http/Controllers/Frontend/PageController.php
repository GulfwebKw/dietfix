<?php


namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Mail\UserReport;
use App\Models\Frontend\About;
use App\Models\Frontend\Contact;
use App\Models\Frontend\MealsSample;
use App\Models\Frontend\UserContact;
use App\Models\Slideshow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\User;
class PageController extends Controller
{

      public static function getDietUserNumber(){
	$users = User::select('mobile_number')->whereRaw('LENGTH(mobile_number)=8')->get();	
	foreach($users as $user){
	echo $user->mobile_number.'<br>';
	}
	}
    
	 public function daysFromDates($fdate,$tdate){
		$datetime1 = new \DateTime($fdate);
		$datetime2 = new \DateTime($tdate);
		$interval  = $datetime1->diff($datetime2);
		$days      = $interval->format('%a');
		return $days;
    }
 
 
	 
    public function index(Request $request)
    {

        $sliders=Slideshow::where('active',1)->get();
        return view('frontend.index',compact('sliders'));
    }

    public function appTermsAndConditions()
    {
        $item=About::where('page_name','TAC')->first();
        return view('frontend.about_app',compact('item'));
    }

    public function appAboutPage()
    {
        $item=About::where('page_name','about')->first();
        return view('frontend.about_app',compact('item'));
    }
    public function aboutPage()
    {
        $item=About::where('page_name','about')->first();
        return view('frontend.about',compact('item'));

    }
    public function termsAndConditions()
    {
        $item=About::where('page_name','TAC')->first();
        return view('frontend.about',compact('item'));
    }

    public function termsAndConditions2()
    {
        $settings= DB::table('app_settings')->whereIn('setting_key',['terms_conditions'])->get()->keyBy('setting_key');
        $content=optional($settings['terms_conditions'])->value;

        return view('frontend.terms_conditions',compact('content'));
    }
    public function membershipPage()
    {
        $item=About::where('page_name','membership')->first();
        return view('frontend.membership',compact('item'));
    }
    public function examplesOfMealsPage()
    {
        $resultslider=MealsSample::get();
        return view('frontend.examplesOfMeals',compact('resultslider'));
    }
    public function contactsPage()
    {
        $contact=Contact::first();
        return view('frontend.contacts',compact('contact'));
    }
    public function contactsPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email',
            'message' => 'required|max:1500',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'g-recaptcha-response' => 'required|captcha',

        ],[
            'name.required'=>"Please enter your name",
            'email.required'=>"Please enter your Email",
            'message.required'=>"Please enter your message ",
            'phone.required'=>"Please enter your phone",
            'phone.number'=>'The phone must be a number',
            '.g-recaptcha-response.required' => 'Please verify that you are not a robot.',
            'captcha' => 'Captcha error! try again later or contact site admin.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

          UserContact::create(['name'=>$request->name,'email'=>$request->email,'phone'=>$request->phone,'message'=>$request->message]);

        session()->flash('message','Contact information has been sent successfuly');
        return redirect()->back();


    }
    public function registerMessage(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'weight' => 'required',
            'height' => 'required',
            'age' => 'required',
            'option' => 'required',
        ],[
            'name.required'=>"يرجى إدخال الإسم",
            'weight.required'=>"يرجى إدخال الوزن بالكيلوغرام",
            'height.required'=>"يرجى إدخال الطول بالسنتيمتر",
            'phone.required'=>"يرجى إدخال التلفون",
            'phone.regex'=>"يرجى إدخال التلفون",
            'phone.min'=>"يرجى إدخال التلفون",
            'phone.number'=>'يرجى إدخال التلفون',
            'option.required'=>'يرجى إدخال الغرض من الحمية',
        ]);
        $name=$request->name;
        $phone=$request->phone;
        $weight=$request->weight;
        $height=$request->height;
        $age=$request->age;
        $option=$request->option;
        $details=$request->details;

        if ($validator->fails()) {
            return response()->json(['status'=>false,'errors'=>$validator->errors()->first()]);
        }

        // DB::table('')->insert([]);

        Mail::to(env('MAIL_ADMIN_TO'))->send(new UserReport($name,$phone,$weight,$height,$age,$option,$details));

        return response()->json(['status'=>true,'message'=>"تم إرسال البيانات بنجاح"]);





    }


}
