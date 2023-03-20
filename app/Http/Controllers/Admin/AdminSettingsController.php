<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 10:31 AM
 */

namespace App\Http\Controllers\Admin;


use App\Events\RegisterUser;
use App\Events\Sms;
use App\Mail\DeleteDay;
use App\Models\Clinic\Day;
use App\Models\Clinic\Item;
use App\Models\Clinic\Order;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\UserDate;
use App\Models\ReferralUser;
use App\Models\Setting;
use App\Models\Sms as MesageSms;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;
use stdClass;

class AdminSettingsController extends AdminController
{


    public function __construct()

    {

        // The Model To Work With

        $this->model = Setting::class;



        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'settings';



        // Human Name

        $this->humanName = 'الاعدادات';



        // Fields for this table

        $this->fields[] = ['title' => trans('main.Arabic Site Name'), 'name' => 'siteNameAr','type' => 'text','col' => 2];

        $this->fields[] = ['title' => trans('main.English Site Name'), 'name' => 'siteNameEn','type' => 'text','col' => 2];

        $this->fields[] = ['title' => 'Meta Keywords', 'name' => 'metaKeywords','type' => 'textarea','col' => 2];

        $this->fields[] = ['title' => 'Meta Description', 'name' => 'metaDescription','type' => 'textarea','col' => 2];

        $this->fields[] = ['title' => trans('main.Email'), 'name' => 'adminEmail','type' => 'text','col' => 2];

        $this->fields[] = ['title' => trans('main.Admin'), 'name' => 'adminPath','type' => 'text','col' => 2];

        // $this->fields[] = array('title' => trans('main.Teacher Header'), 'name' => 'teacherHeader' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'files');
        // $this->fields[] = array('title' => trans('main.School Header'), 'name' => 'schoolHeader' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'files');
        // $this->fields[] = array('title' => trans('main.Club Header'), 'name' => 'clubHeader' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'files');

        $this->fields[] = ['title' => 'Instagram', 'name' => 'socialInstagram','type' => 'text','col' => 2];

        $this->fields[] = ['title' => 'SnapChat', 'name' => 'socialSnap','type' => 'text','col' => 2];
        // $this->fields[] = ['title' => 'Twitter', 'name' => 'socialTwitter','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => 'Facebook', 'name' => 'socialFacebook','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => 'Google+', 'name' => 'socialGoogle','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => 'Youtube', 'name' => 'socialYoutube','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => 'Linkedin', 'name' => 'socialLinkedin','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => trans('main.Expire After'), 'name' => 'expirePeriod','type' => 'text','col' => 2];

        // $this->fields[] = ['title' => 'Google Analytics', 'name' => 'analytics','type' => 'text','col' => 2];

        $this->fields[] = ['title' => trans('main.Language'), 'name' => 'defaultLang','type' => 'select','valOptions'=>'otherType','data' => ['ar' =>'Arabic','en' => 'English'],'col' => 2];
        $this->fields[] = ['title' => trans('main.Phone'), 'name' => 'phone','type' => 'text','col' => 2];

		$this->fields[] = ['title' => trans('main.WhatsApp'), 'name' => 'whatsapp','type' => 'number','col' => 2];

        $this->fields[] = ['title' => trans('main.Mobile'), 'name' => 'mobile','type' => 'text','col' => 2];
        $this->fields[] = ['title' => 'print label Production -day', 'name' => 'printLabelProduction','type' => 'number','col' => 2];
        $this->fields[] = ['title' => 'print label Expiry +day', 'name' => 'printLabelExpiry','type' => 'number','col' => 2];


        // $this->fields[] = ['title' => trans('main.Mail List Url'), 'name' => 'maillist_url' ,'type' => 'text'];
        // $this->fields[] = ['title' => trans('main.Mail List Arabic'), 'name' => 'maillist_helpAr' ,'type' => 'textarea','col' => 2];
        // $this->fields[] = ['title' => trans('main.Mail List English'), 'name' => 'maillist_helpEn' ,'type' => 'textarea','col' => 2];
        $this->fields[] = ['title' => trans('main.Welcome Arabic'), 'name' => 'welcomeAr' ,'type' => 'textarea', 'size' => 1,'col' => 2];
        $this->fields[] = ['title' => trans('main.Welcome English'), 'name' => 'welcomeEn' ,'type' => 'textarea', 'size' => 1,'col' => 2];

        $this->fields[] = ['title' => trans('main.Website Offline?'), 'name' => 'isOffline' ,'type' => 'switcher','col' => 2];

        $this->fields[] = ['title' => trans('main.Offline Page'), 'name' => 'offlinePage' ,'type' => 'wysiwyg', 'size' => 1];
        $this->fields[] = ['title' => trans('main.About Page'), 'name' => 'aboutPage' ,'type' => 'wysiwyg', 'size' => 1];


        // $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher');



        // Grid Buttons

        // $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');

        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');

        // $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');



        // The View Folder

        // $this->views = 'users';



        // Deleteable

        // $this->deletable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";


        parent::__construct();


    }



    protected function checkAdminPermession()

    {



        $adminPermission = (Auth::user()) ? unserialize(Auth::user()->getMeta(Auth::user(),'adminPermission')) : array();



        if(isset($adminPermission[2])) {

            $butsToRemove = array_diff(array_keys($this->buts),$adminPermission[2]);

            if($butsToRemove) {

                foreach ($butsToRemove as $b) {

                    unset($this->buts[$b]);

                }

            }

        }

    }



    public function getEdit($id)

    {





        if(!$id || !is_numeric($id))

            die();



        $M = $this->model;

        $rows = $M::all();

        $this->item = new stdClass();

        foreach ($rows as $row) {

            $this->item->{$row->key} = $row->value;

        }



        return View::make( 'admin.' . $this->views . 'forms.form' )

            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )

            ->with( '_pk' , $this->_pk )

            ->with( 'url' , $this->saveurl )

            ->with( 'item' , $this->item )

            ->with( 'uploadable' , $this->uploadable )

            ->with( 'fields' , $this->fields );

    }
    public function getReferralCount($userId)
    {
        return  DB::table("referral_user")->where('user_id',$userId)->where('status',0)->count();
    }
    public function temp()
    {



        $dates=UserDate::with(["user"])->whereIn('date',['2020-07-30','2020-07-31'])->where('freeze',0)->get();
        dd($dates);
        foreach ($dates as $date) {
            $lastDay=UserDate::where('user_id',$date->user_id)->orderBy('date','desc')->first();
            $day=$lastDay->date;
            $validDay = strtotime("+1 day", strtotime($day));
            $firstDate=date("Y-m-d",$validDay);


            $pId=$lastDay->package_id?$lastDay->package_id:optional($date->user)->package_id;
            UserDate::create(['date'=>$firstDate,'user_id'=>$date->user_id,'update_status'=>"admin",'package_id'=>$pId]);

            Order::where('user_id',$date->user_id)->where('date_id',$date->id)->delete();
            $date->delete();
        }
        dd('ok');




       $res= UserDate::where('isMealSelected',1)->with(['orders','user.package.meals'])->where('date','>=','2019-12-11')->get();

       $arr=[];
        foreach ($res as $item) {
           if($item->orders->count()<$item->user->package->meals->count()){
               $new=[$item->user->id,$item->date];
               array_push($arr,$new);
           }
       }

        dd($arr);
        $referralList=[60646017,69681015,67746661,97113116,97563597];

        $user=User::find(339);
        $count= intval($this->getReferralCount($user->id));

        if($count>=5){
            return [205,['data'=>[],'message'=>trans('main.phone_limit')]];
        }
        $e=5-$count;

        $sms=MesageSms::first();
        foreach ($referralList as $item) {
            if($e>0){
                $oldEx=ReferralUser::where('referral_mobile_number',$item)->where('status',1)->first();
                $userD=User::where("mobile_number",$item)->first();
                if(!empty($userD)||!empty($oldEx)){
                    return [205,['data'=>[],'message'=>trans('main.already_registered',['phone'=>$item])]];
                }

                if(is_null($oldEx)){

                    $itemR=ReferralUser::firstOrNew(['user_id'=>$user->id,'referral_mobile_number'=>$item,'status'=>0]);
                    if(is_null($itemR->id)){
                        $itemR->user_id=$user->id;
                        $itemR->referral_mobile_number=$item;
                        $itemR->status=0;
                        $itemR->save();
                        $e-=1;
                        if(isset($sms)){
                                $msg=$sms->contentEn;

                            //event(new Sms($item,$msg));
                        }
                    }
                }else{
                    return [205,['data'=>[],'message'=>trans('main.phone_limit')]];
                }
            }
        }
        $res=DB::table('referral_user')->where('user_id',$user->id)->where('status',0)->limit(5)->get()->toArray();
        return  [200,['data'=>['referralList'=>$res],'message'=>"successful update or create referral "]];




        ///



        $e= Mail::to('sajjad.rezaei9090@gmail.com')->send(new DeleteDay(420,[],[]));
        dd($e);


        $first=User::with(['package','lastDay','role','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->whereHas('dates',function ($r){
            $r->where('date','>=',date('Y-m-d'));
        })->get();
        $arr=[];
        foreach ($first as $item) {
            if(optional($item->lastDay)->date=='2019-12-04'){
                array_push($arr,$item->id);
            }
        }
       $e= User::whereIn('id',$arr)->where('active',1)->pluck('mobile_number')->toArray();
        dd(array_unique($e));








        $use=Auth::user();
        $this->makeAdminLog("test");
       dd(Auth::guard('web')->user()->username);


        $res=User::whereIn('id',[35])->where('active',1)->pluck('mobile_number')->toArray();
        dd($res);

       $res= DB::table("dup_order")->select('*')->get();
        foreach ($res as $re) {
           $count= DB::table('orders')->where('meal_id',$re->meal_id)->where('user_id',$re->user_id)->where('date_id',$re->date_id)->count();
           if($count>=1){
              $e= DB::table('orders')->where('meal_id',$re->meal_id)->where('user_id',$re->user_id)->where('date_id',$re->date_id)->first();
               DB::table('orders')->where('id',$e->id)->delete();
           }
       }
        dd('.');



      dd(date("H:i:s",time()));
        $user= User::with(['package','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->where('membership_end','>=',date('Y-m-d'))->pluck('id')->toArray();



       $res= DB::table('temp_orders')->orderBy('id','desc')->select('*')->whereIn('user_id',$user)->get();

        foreach ($res as $item) {
            $arr[$item->user_id][$item->day_id][]=$item;
       }
        foreach ($arr as $userId=>$group) {
            $userDays=DB::table('user_dates')->where('user_id',$userId)->select('*')->get();
            foreach ($group as $dayId=>$order) {
                $day=$this->selectDay($dayId);
              //  dd($group,$order,$day);
                $relatedDay=[];
                foreach ($userDays as $userDay) {
                    if(date('l',strtotime($userDay->date))==$day){
                        array_push($relatedDay,$userDay);
                    }
                }

                if(count($relatedDay)>=1){
                    foreach ($relatedDay as $rd) {
                        foreach ($order as $o) {

                            $addOns=  DB::table('temp_orders_addons')->where('order_id',$o->id)->select("*")->get();


                            $orderN= Order::firstOrNew(['date_id'=>$rd->id,'user_id'=>$rd->user_id,'meal_id'=>$o->meal_id,'item_id'=>$o->item_id]);
                           // $orderN=new Order();
                            $orderN->date_id=$rd->id;
                            $orderN->user_id=$rd->user_id;
                            $orderN->meal_id=$o->meal_id;
                            $orderN->category_id=$o->category_id;
                            $orderN->item_id=$o->item_id;
                            $orderN->portion_id=$o->portion_id;
                            $orderN->day_id=$o->day_id;
                            $orderN->save();

                            // DB::table('orders')->insert(['date_id'=>$rd->id,'user_id'=>$rd->user_id,'meal_id'=>$o->meal_id,'category_id'=>$o->category_id,'item_id'=>$o->item_id,'portion_id'=>$o->portion_id,'day_id'=>$o->day_id]);
                            if($addOns->count()>=1){
                                foreach ($addOns as $addOn){
                                    DB::table("orders_addons")->insert(['order_id'=>$orderN->id,'addon_id'=>$addOn->addon_id]);
                                }
                            }
                        }
                    }

                }

            }
        }



        dd('com');

       // app()->setLocale("ar");
        App::setLocale("ar");

        dd('ss');
      $res=  DB::table("user_start_date")->select('*')->get();
        foreach ($res as $re) {
            if($re->membership_start!="0000-00-00")
            User::whereId($re->id)->update(['membership_start'=>$re->membership_start]);
      }
        dd('ok');




        $packageDuratio=PackageDurations::all();
        foreach ($packageDuratio as $item) {
            $res= $item->count_day;
            $item->titleAr=$res." ایام ";
            $item->save();
        }
        dd('a');

        $package=Package::all();
        foreach ($package as $item) {
            $r=[1,7,14,20,24,28];
            foreach ($r as $count) {
                $duration=PackageDurations::where('count_day',$count)->where('package_id',$item->id)->first();
                if(!isset($duration)){

                    PackageDurations::create(['count_day'=>$count,'package_id'=>$item->id,'titleEn'=>$count." Days",'titleAr'=>$count." ",'active'=>1,'show_mobile'=>1,"price"=>5,"price_after_discount"=>5]);
                }
            }

        }

        dd('sadds');


//        $items= Item::with(["category","days"])->get();
//        foreach ($items as $item){
//            $days=$item->days->pluck("titleEn")->toArray();
//            foreach ($days as $day) {
//                $dayB=Day::where("titleEn",$day." 2")->first();
//                if(isset($dayB)&&!in_array($dayB->titleEn,$days)){
//                    $res= DB::table("items_days")->where('item_id',$item->id)->where("day_id",$dayB->id)->first();
//                    if(!isset($res)){
//                        DB::table("items_days")->insert(['item_id'=>$item->id,'day_id'=>$dayB->id]);
//                    }
//                }
//            }
//
//        }

    }

    public function selectDay($dayId)
    {
       switch ($dayId){
           case 7:
               return "Friday";
           case 6:
               return "Saturday";
           case 5:
               return "Thursday";
           case 4:
               return "Wednesday";
           case 3:
               return "Tuesday";
           case 2:
               return "Monday";
           case 1:
               return "Sunday";
       }

    }

    public function store(Request $request)

    {

          $M = $this->model;


        foreach ($this->fields as $field) {
            $settings = Setting::where('key',$field['name'])->first();
            if(isset($settings)){
                $settings->value = Input::get($field['name']);
                if($field['name']=='isOffline'){
                    if(!isset($request->isOffline)){
                        $settings->value=0;
                    }
                }
                $settings->save();
            }


        }


        $this->saveOther();

        return Redirect::to($this->menuUrl.'/edit/1')->with('messages', array('success' => 'Saved!'));

    }


}
