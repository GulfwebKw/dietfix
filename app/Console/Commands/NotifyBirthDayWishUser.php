<?php
namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifyBirthDayWishUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifyBirthdayWishUser:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'notifyBirthdayWishUser for client';

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
        Log::info("run crone for notify birthday wish ".date('Y-m-d h:m'));
        $this->notify();

    }
   
    public function notify(){
   //send push notification
            $curDate = date("Y-m-d");
			$item = User::whereNotNull('deviceToken')->whereNotNull('dob')->whereMonth('dob', '=', date('m'))->whereDay('dob', '=', date('d'))->get();
			if(!empty($item) && count($item)>0){
			$titleAr   = "DietFix أتمنى لك عيد ميلاد سعيد";
			$titleEn   = "DietFix wish you happy birthday";
			$contentAr = "في عيد ميلادك أتمنى لك التوفيق والسعادة التي لا تنتهي!. أتمنى لك عيد ميلاد رائع!";
			$contentEn = "On your birthday I wish you success and endless happiness!.Wishing you an awesome birthday!";			
		    $this->sendPushNotification($titleAr,$titleEn,$contentAr,$contentEn,$item);
			}
		
    }
  

   ///send mobile push notificatiojn
	private function sendPushNotification($titleAr,$titleEn,$contentAr,$contentEn,$items)
    {

     
	    $arrayToken=[];
        if(!empty($items) && count($items)>0){
		foreach($items as $item){	   
          \DB::table('notifications')->insert(['user_id'=>$item->id,'titleEn'=>$titleEn,'contentEn'=>$contentEn,'titleAr'=>$titleAr,'contentAr'=>$contentAr]);
          array_push($arrayToken,$item->deviceToken);
		 }
        }
        if(count($arrayToken)>=1){
            foreach ($arrayToken as $item) {
                $url = "https://fcm.googleapis.com/fcm/send";
                $serverKey =env('SERVER_KEY');
                $notification = array('title' =>$titleEn , 'text' =>$contentEn, 'sound' => 'default', 'badge' => '1','Notifications_type'=>'regular','data'=>['notify_type'=>'regular']);
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
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                //Send the request
                $response = curl_exec($ch);
				
                //Close request
                 curl_close($ch);


            }
          return true;
		  //return ['message'=>$response];
        }
        return false;
		//return ['message'=>"error"];
    }

}
