<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Area;
use App\Models\Clinic\Province;
use App\Models\Slideshow;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class MainController extends Controller
{
    var $siteMenu;

    public $data = array();
    public function __construct()
    {

        //runkit_constant_redefine ('LANG_SHORT' , 'ar' );
       // dd(testSess());

        $this->middleware(function ($request, $next) {
                if (session()->has('lang')) {
                    app()->setLocale(session()->get('lang'));
                }else{
                    app()->setLocale('en');
                }
                return $next($request);
        });

        // $this->_ses = Session::all();
        // dd($this->_ses);

        if(!\request()->ajax()){

            $this->_setting = \config('settings');

            $this->appUrl = config('app.url');
            $this->genders = ['Female' => trans('main.Female'),'Male' => trans('main.Male')];
            $genders_select = [trans('main.Gender')] + $this->genders;
            $this->periods = ['Summery' => trans('main.Summery'),'Wintry' => trans('main.Wintry'),'All Year' => trans('main.All Year')];
            $periods_select = [trans('main.Period')] + $this->periods;

            // View::share('_ses',$this->_ses);

            $this->merchantID = '1014';
            $this->merchantUserName = 'test';
            $this->merchantPassword = '4l3S3T5gQvo%3d';
            $this->merchantName = 'Web Pay';
            $this->paymentWebService = 'http://live.gotapnow.com/webservice/PayGatewayService.svc?wsdl';

            // $this->merchantID = '81083';
            // $this->merchantUserName = 'Tap@OrdQ8';
            // $this->merchantPassword = 'OrdQ8@tap23';
            // $this->merchantName = 'Order Q8';
            // $this->paymentWebService = 'https://www.gotapnow.com/webservice/PayGatewayService.svc?wsdl';

            $provinces=Cache::remember('provinces', 15, function() {
                return Province::where('active',1)->with('areas')->orderBy('ordering')->get();
            });

            $areas=Cache::remember('areas', 15, function() {
                return Area::where('active',1)->orderBy('title'.LANG)->get();
            });

            $slideshow=Cache::remember('slideshow', 15, function() {
                return Slideshow::where('active' ,1)->get();
            });
            view()->share(['_pageTitle'=>'Home','periods_select'=>$periods_select,'periods'=>$this->periods,'genders_select'=>$genders_select,'_langShort'=>LANG_SHORT,'_setting'=>$this->_setting,'appUrl'=>$this->appUrl,'genders'=>$this->genders,'provinces'=>$provinces,'provinces_with_areas'=>$provinces,'areas'=>$areas,'slideshow'=>$slideshow]);

        }




    }
    public function mobileNotifyUser($msg, $user = false)
    {
        if(!$user)
            $user = Auth::user();

        if(!$user)
            return false;

        $no = $user->phone;

        if(strpos($no, '+') !== false)
            $no = str_replace('+', '', $no);
        else if (strpos($no, '00') === 0)
            $no = substr($no, 2);

        return $this->send_sms($no,$msg);
    }
    public function send_sms($no,$msg)
    {
        $user = "lookforschools";
        $password = "LKZFCBRQgFMWeb";
        $api_id = "3552486";
        $baseurl ="http://api.clickatell.com";

        $text = urlencode($msg);
        $to = $no;

        // auth call
        $url = "$baseurl/http/auth?user=$user&password=$password&api_id=$api_id";

        // do auth call
        $ret = file($url);

        // explode our response. return string is on first line of the data returned
        $sess = explode(":",$ret[0]);
        if ($sess[0] == "OK") {

            $sess_id = trim($sess[1]); // remove any whitespace
            $url = "$baseurl/http/sendmsg?session_id=$sess_id&to=$to&text=$text";

            // do sendmsg call
            $ret = file($url);
            $send = explode(":",$ret[0]);

            if ($send[0] == "ID") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
        // http://api.clickatell.com/http/sendmsg?user=[USERNAME]&password=[PASSWORD]&api_id=3552486&to=96566991985&text=Message
    }
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout()
    {
        if ( ! is_null($this->layout))
        {
            $this->layout = View::make($this->layout);
        }
    }
    public function queries()
    {
        $queries = DB::getQueryLog();
        dd($queries);
    }

    public function isAdmin()
    {
        if (!Auth::user() || in_array(Auth::user()->role_id,[10]) || Auth::user()->active === 1) return true;
        else return false;
    }

    public function notKitchen()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[3,10]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function notDoctor()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[2,10]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function notUser()
    {
        if (!Auth::user() || !in_array(Auth::user()->role_id,[1,10,20]) || Auth::user()->active != 1) return true;
        else return false;
    }

    public function dontAllow()
    {
        return Redirect::to('/')->withMessages([trans('You don\'t have access to this resources')]);
    }

    public function resize($folder = '')
    {
        // http://intervention.olivervogel.net/image/getting_started/laravel
        // http://web.dev/Dropbox/Work/Developments/www/system/public/resize?src=assets/img/a.jpg&w=400&h=400&c=1
        // http://web.dev/Dropbox/Work/Developments/www/system/public/resize?src=assets/img/a.jpg&w=400&r=1

        // Image Source
        if (!isset($_GET['src']))
            die();

        if (!empty($folder))
            $_GET['src'] = $folder.$_GET['src'];
        // Width
        $w = (isset($_GET['w'])) ? (int) $_GET['w'] : null;
        // Height
        $h = (isset($_GET['h'])) ? (int) $_GET['h'] : null;
        // Ratio
        $r = (isset($_GET['r']) && $_GET['r'] == 1) ? true : false;
        // Watermark
        $wm = (isset($_GET['wm']) && $_GET['wm'] == 1) ? true : false;
        // Crop
        $c = (isset($_GET['c']) && $_GET['c'] == 1) ? true : false;
        // Background
        $bg = (isset($_GET['bg'])) ? $_GET['bg'] : '#ffffff';

        // Make the image
        // dd(public_path($_GET['src']));
        $image = Image::make(public_path($_GET['src']));

        // Resize or Crop
        if ($c) {
            $image = Image::canvas($w,$h,$bg);
            $thumb = Image::make(public_path($_GET['src']));
            $thumb->resize($w,$h,$r);
            $image->insert($thumb,0,0,'center');
            // $image->resize($w,$h,$r)->crop($w,$h);
        }
        else {
            $image->resize($w,$h,$r);
        }

        // $path = dirname(__FILE__).'/../';

        // Add Watermark
        if ($wm)
            $image->insert(public_path('assets/img/wm.png'),0,0,'bottom-right');

        // echo $path.'public/assets/img/wm.png';
        // View The Image
        return $image->response();
        // return Response::make($image, 200, array('Content-Type' => 'image/jpeg'));

    }


    public function upload_files()
    {
        $this->forgetBeforeFilter('csrf');
        if (Auth::guest()) {
            return Redirect::guest('user/login');
        }

        // Set the uplaod directory
        $folder = str_replace("..", "", $_POST['folder']);
        $uploadDir = public_path($folder);

        // Set the allowed file extensions
        $fileTypes = (isset($_POST['fileExt'])) ? explode(',', $_POST['fileExt']) : "jpg,jpeg,png,gif,bmp"; // Allowed file extensions


        $verifyToken = md5('SecUre!tN0w' . $_POST['timestamp']);

        if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $currentExt = strtolower(pathinfo($_FILES['Filedata']['name'], PATHINFO_EXTENSION));
            $newName = date('Y-m-d-H-i-s').'-'.substr(md5($_FILES['Filedata']['name']), 0, 5).'.'.$currentExt;
            $targetFile = $uploadDir . $newName;

            // Validate the filetype
            if (in_array($currentExt, $fileTypes)) {

                // Save the file
                move_uploaded_file($tempFile, $targetFile);
                echo $newName;

            } else {

                // The file type wasn't allowed
                echo false;

            }
        }

    }

    public function sendResponse($status,$keyVal)
    {
        $arrayResponse=[];
        $arrayResponse['status']=$status;
        foreach ($keyVal as $key=>$value) {
            $arrayResponse[$key]=$value;
        }
        return response()->json($arrayResponse);
    }

    public function getUser(Request $request)
    {
        return User::find(auth()->user()->id);
    }


}
