<?php

namespace App\Listeners;

use App\Events\RegisterUser;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncrementCreditReferralUser
{
  //  use InteractsWithQueue;
    private $user;
    /**
     * The name of the queue the job should be sent to.
     *
     * @var string|null
     */
    public $queue = 'incrementCreditUser';
    /**
     * The time (seconds) before the job should be processed.
     *
     * @var int
     */
    public $delay = 60;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }
    /**
     * Handle the event.
     *
     * @param RegisterUser $event
     * @return void
     */
    public function handle(RegisterUser $event)
    {
          $this->user=$event->user;

          $res=$this->existReferral();
            if(isset($res)){
                $this->updateStatus($res);
            }
       //$this->release(30);
    }
    private function existReferral()
    {
       return DB::table('referral_user')->where('referral_mobile_number',$this->user->mobile_number)->where('status',0)->first();
    }
    private function updateStatus($referral){

        Log::info(" referral_user ".optional($this->user)->mobile_number);
        DB::table('referral_user')->where('id',$referral->id)->update(['status'=>1]);
        DB::table('user_point')->insert(['user_id'=>$referral->user_id,'value'=>env('VAL_INCREMENT_REFERRAL',0),'description'=>'increment credit user for referring mobile number --->'.optional($this->user)->mobile_number,'referral_number'=>optional($this->user)->mobile_number]);
        DB::table('credit_user')->insert(['user_id'=>$referral->user_id,'operation'=>'increment','value'=>env('VAL_INCREMENT_REFERRAL',0),'description'=>'increment credit user for referring mobile number --->'.optional($this->user)->mobile_number,'referral_number'=>optional($this->user)->mobile_number]);
        Log::info("increment credit user--->".$referral->user_id);

    }



}
