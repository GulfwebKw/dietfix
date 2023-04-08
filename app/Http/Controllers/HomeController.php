<?php
namespace App\Http\Controllers;
use App\Models\Page;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Auth;
use DB;

use App\Models\Clinic\UserDate;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Progress;

class HomeController extends MainController {

  public function getprogress(){
        $res = UserDate::select('user_dates.user_id','users.id','users.package_duration_id','package_duration.id','package_duration.count_day')
                       ->join('users','users.id','=','user_dates.user_id')
                       ->join('package_duration','package_duration.id','=','users.package_duration_id')
                       ->whereNOTNULL('users.package_duration_id')
                       ->distinct()->get();
        foreach($res as $list){
           if(!empty($list->package_duration_id)){
            $progressweek    = Progress::where('user_id',$list->user_id)->get()->count();
            $packageDuration = PackageDurations::where('id',$list->package_duration_id)->first();
            if(empty($progressweek) && !empty($packageDuration->count_day)){
                $this->addprogress($list->user_id,$packageDuration->count_day);
                //echo $list->user_id; echo "<br>";
            }
           }
        }
    }

public function addprogress($userId,$countday){
    $countWeek = ceil($countday/7);
    for ($i = 1; $i <= $countWeek; $i++) {
        $progressweek    = new Progress;
        $progressweek->user_id = $userId;
        $progressweek->titleEn = ' Week ' . $i . ' Progress';
        $progressweek->titleAr = ' Week ' . $i . ' Progress';
        $progressweek->water      = 0;
        $progressweek->commitment = 0;
        $progressweek->exercise   = 0;
        $progressweek->save();
    }
}

   public function getrandmeals(){
        $res= DB::table('meals')
        ->join('categories','meals.id','=','categories.meal_id')
        ->join('items','categories.id','=','items.category_id')
        ->join('items_days','items.id','=','items_days.item_id')
        ->where('items_days.day_id',1)
        ->where('categories.active',1)
        ->where('items.active',1)
        ->whereIn('categories.id',[1,2,5,6,12])
        ->orderBy('items.most_order','desc')
        ->select(['items.id as item_id','categories.id as category_id'])->first();
        dd($res);
    }

	public function showHome()
	{
	    return View::make('myhome')->withTitle(trans('main.Home'));
       /*

        if (!Auth::user())
			return View::make('user.login')->withTitle(trans('main.Home'));
		else
			return View::make('home')->withTitle(trans('main.Home'));
            */
	}
    public function switchLang2($lang)
    {
        Session::put('lang',$lang);
        App::setLocale($lang);
      //  app()->setLocale($lang);


        if(URL::previous())
            return Redirect::to(URL::previous());
        else
            return Redirect::to(url('/'));
    }
    public function renderCss()
    {

        if (Cache::has('rendered_css')) {
            $rendered = Cache::get('rendered_css');


        } else {
            $file = File::get(storage_path('css.json'));
            $paths = json_decode($file);

            $rendered = '';
            foreach ($paths as $path) {
                $rendered .= File::get(public_path($path));
            }

            Cache::put('rendered_css',$rendered,10);
        }

        $offset = 86400*7;

        $response = Response::make($rendered, 200);

        $response->header('Content-Type', 'text/css; charset: UTF-8');
        $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $offset).' GMT');
        $response->header('Cache-Control', 'maxage='.$offset);

        return $response;

    }
    public function renderJs()
    {

        if (Cache::has('rendered_js')) {
            $rendered = Cache::get('rendered_js');


        } else {
            $file = File::get(storage_path('js.json'));
            $paths = json_decode($file);

            $rendered = '';
            foreach ($paths as $path) {
                $rendered .= File::get(public_path($path));
            }

            Cache::put('rendered_js',$rendered,10);
        }

        $offset = 86400*7;

        $response = Response::make($rendered, 200);

        $response->header('Content-Type', 'application/x-javascript');
        $response->header('Expires', gmdate('D, d M Y H:i:s', time() + $offset).' GMT');
        $response->header('Cache-Control', 'maxage='.$offset);

        return $response;

    }
    public function subscribeList()
    {
        $lists = MailchimpWrapper::lists()->getList()['data'];
        MailchimpWrapper::lists()->subscribe($this->_setting['mailchimp_list'], array('email'=>Input::get('email')));

        return Redirect::to('');
    }
	public function showSiteMap()
	{
		$pages = Page::where('active',1)
					->get();
		$schools = School::where('active',1)
					->where('published_at','<=',date('Y-m-d H:i:s'))
					->where('expire_at','>=',date('Y-m-d H:i:s'))
					->orderBy('ordering','asc')
					->orderBy('published_at','desc')
					->get();
		$clubs = Club::where('active',1)
					->where('published_at','<=',date('Y-m-d H:i:s'))
					->where('expire_at','>=',date('Y-m-d H:i:s'))
					->orderBy('ordering','asc')
					->orderBy('published_at','desc')
					->get();
		$teachers = Teacher::where('active',1)
					->where('published_at','<=',date('Y-m-d H:i:s'))
					->where('expire_at','>=',date('Y-m-d H:i:s'))
					->orderBy('ordering','asc')
					->orderBy('published_at','desc')
					->get();
		return View::make('sitemap')
					->withTitle(trans('main.Sitemap'))
					->withSchools($schools)
					->withClubs($clubs)
					->withTeachers($teachers)
					->withPages($pages);
	}


 public function sendPushNotification()
    {

	   $item = User::where('id','4503')->first();

	   $titleAr="test";
	   $titleEn="test";
	   $contentAr="test details";
	   $contentEn="test Details";

	    $arrayToken=[];
        if(!empty($item->id)){
                    ///\DB::table('notifications')->insert(['user_id'=>$item->id,'titleEn'=>$titleEn,'contentEn'=>$contentEn,'titleAr'=>$titleAr,'contentAr'=>$contentAr]);
                    array_push($arrayToken,$item->deviceToken);

        }
        if(count($arrayToken)>=1){
            foreach ($arrayToken as $item) {
                $url = "https://fcm.googleapis.com/fcm/send";
                $serverKey =env('SERVER_KEY');
                $notification = array('title' =>$titleEn,'body' =>$contentEn , 'text' =>$contentEn, 'sound' => 'default', 'badge' => '1','Notifications_type'=>'regular','data'=>['notify_type'=>'regular']);
                $arrayToSend = array('to' =>$item,'notify_type'=>'regular','notification' => $notification,'priority'=>'high','data'=>['notify_type'=>'regular']);
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='.$serverKey;
                $headers[] = 'Notifications_type=regular';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                //Send the request
                $response = curl_exec($ch);

                //Close request
                 curl_close($ch);


            }
          //return true;
		  return ['message'=>$response];
        }
        //return false;
		return ['message'=>"error"];
    }




}
