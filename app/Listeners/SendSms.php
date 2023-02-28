<?php


namespace App\Listeners;
use App\Events\Sms;
use Illuminate\Contracts\Queue\ShouldQueue;
class SendSms
{
    private $mobile;
    private $message;

    public function handle(Sms $event)
    {
        $this->mobile=$event->mobile;
        $this->message=$event->message;
        $this->senSms();
        //$this->release(30);
    }
    public function senSms()
    {
        $senderid=env('SENDER_ID');
        //recipient number
        $mobile=trim($this->mobile);
        //sms
        $sms=trim($this->message);
        //Authentication code provided by DezSMS.com
        $key=env('SMS_API_KEY'); //check api text file
        //DezSMS login ID
        $dezsmsid=env('DEZSMS_ID');
        // posting value
        $post="msg=".urlencode($sms)."&number=".$mobile."&key=".$key."&dezsmsid=".$dezsmsid."&senderid=".$senderid;
        //curl function
        $this->_curl($post);
    }
    private  function _curl($post){
        $ch = curl_init(env('API_SMS_URL'));
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,$post);
       $res= curl_exec ($ch);

        $res=curl_close ($ch);
       // return $res;
    }




}
