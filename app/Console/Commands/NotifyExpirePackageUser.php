<?php

namespace App\Console\Commands;

use App\Models\Clinic\Order;
use App\Models\Clinic\Package;
use App\Models\Clinic\UserDate;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyExpirePackageUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyExpirePackageUser:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'NotifyExpirePackageUser for client';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("run crone for notify expire package ".date('Y-m-d h:m'));
        $this->handelNotify();

    }
    public function handelNotify()
    {
        $validExpireDate=$this->getValidEndDayForNotify();
        $before3Days=$validExpireDate[0];
        $before7Days=$validExpireDate[1];

        $res=$this->getAllValidUsersForNotify();
        $user3=$res[0];
        $user7=$res[1];

        foreach ($user3 as $i) {
            Log::info("befor 3 days $i");
        }
        foreach ($user7 as $it) {
            Log::info("befor 7 days $it");
        }


        foreach ($user3 as $mobile) {
            $this->notify($mobile,$before3Days);
            sleep(5);
        }
        foreach ($user7 as $mobile) {
            $this->notify($mobile,$before7Days);
            sleep(5);
        }


    }
    private function notify($mobile,$date){
        if(strlen($mobile)==8 && $this->validMobile($mobile)==true){
            $smsMsg="عزيزنا العميل
                            نود إبلاغكم بأن اشتراككم بالنظام الغذائي دايت فيكس
                        سوف ينتهي
                        ".$date."
                        ما ودك تجدد؟ عندنا عروض حلوة للتجديد";
            $post="msg=".urlencode($smsMsg)."&number=".$mobile."&key=".env('SMS_API_KEY')."&dezsmsid=".env('DEZSMS_ID')."&senderid=".env('SENDER_ID')."&xcode=965";
            $this->_curl($post);
			//send push notification
			$item = User::where('mobile_number',$mobile)->first();
			if(!empty($item->deviceToken)){
			$titleAr ="ما ودك تجدد؟ عندنا عروض حلوة للتجديد";
			$titleEn = "What would you like to renew? We have great offers for renewal";
			$contentAr="عزيزنا العميل
                            نود إبلاغكم بأن اشتراككم بالنظام الغذائي دايت فيكس
                        سوف ينتهي
                        ".$date."
                        ما ودك تجدد؟ عندنا عروض حلوة للتجديد";
			$contentEn="Dear customer
                             We would like to inform you that you have subscribed to the Diet Fix diet
                         will end
                         ".$date."
                         What would you like to renew? We have great offers for renewal";			
		    $this->sendPushNotification($titleAr,$titleEn,$contentAr,$contentEn,$item);
			}
			
        }else{
            Log::info("not valid Mobile For Notify ===>$mobile"."---->".date('Y-m-d'));
        }
		
		
    }
    private function getValidEndDayForNotify()
    {
        $today=date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $date2 = strtotime("+7 day", strtotime($today));
        $before3Days=date("Y-m-d", $date);
        $before7Days=date("Y-m-d", $date2);
        return [$before3Days,$before7Days];

    }
    private function getAllValidUsersForNotify(){
        $validExpireDate=$this->getValidEndDayForNotify();
        $before3Days=$validExpireDate[0];
        $before7Days=$validExpireDate[1];

        $validUser3=$this->getValidUser($before3Days);
        $validUser7=$this->getValidUser($before7Days);
        return [$validUser3,$validUser7];


    }
    private function getValidUser($date){

        
        $first=User::with(['package','lastDay','role','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->whereHas('dates',function ($r){
            $r->where('date','>=',date('Y-m-d'));
        })->get();
		

        $arr=[];
        foreach ($first as $item) {
            if(optional($item->lastDay)->date==$date){
                array_push($arr,$item->id);
            }
        }
        return User::whereIn('id',$arr)->where('active',1)->pluck('mobile_number')->toArray();
    }
   private function validMobile($mobile){
        if(!empty($mobile)){
            if(!empty($mobile) && $this->integerOnly($mobile)==true){
                $mobileval = substr($mobile, 0, -7);
                if($mobileval==5 || $mobileval==6 || $mobileval==9){
                    return true;
                }
            }
        }
       return false;
    }
   private function integerOnly($str){
        return preg_match('/[0-9 ]+/i',$str);
    }
   private function _curl($post){
        $ch = curl_init(env('API_JSON_SMS_URL'));
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,  $post);
        $result= curl_exec ($ch);
        curl_close ($ch);
        return $result;
    }


   ///send mobile push notificatiojn
	private function sendPushNotification($titleAr,$titleEn,$contentAr,$contentEn,$item)
    {

     
	    $arrayToken=[];
        if(!empty($item->id)){
                    \DB::table('notifications')->insert(['user_id'=>$item->id,'titleEn'=>$titleEn,'contentEn'=>$contentEn,'titleAr'=>$titleAr,'contentAr'=>$contentAr]);
                    array_push($arrayToken,$item->deviceToken);
              
        }
        if(count($arrayToken)>=1){
           $splitedArray = array_chunk($arrayToken,1000);
            foreach($splitedArray as $v){
                if(!empty($v)){ 
                $url = "https://fcm.googleapis.com/fcm/send";
                $serverKey =env('SERVER_KEY');
                $notification = array('title' =>$titleEn , 'text' =>$contentEn, 'sound' => 'default', 'badge' => '1','Notifications_type'=>'regular','data'=>['notify_type'=>'regular']);
                $arrayToSend = array('registration_ids' =>$v,'notify_type'=>'regular','notification' => $notification,'priority'=>'high','data'=>['notify_type'=>'regular']);
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='.$serverKey;
                $headers[] = 'Notifications_type=regular';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                //Send the request
                $response = curl_exec($ch);
				
                //Close request
                 curl_close($ch);
                }

            }
          return true;
		  //return ['message'=>$response];
        }
        return false;
		//return ['message'=>"error"];
    }

}
