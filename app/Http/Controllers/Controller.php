<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function makeAdminLog($message,$logOptions=null,$userId=null,$title=null)
    {
        try{
            $admin=Auth::guard('web')->user();
            $adminId=optional($admin)->id;
            if($logOptions==null){
                Log::info($message, ['id' =>$adminId,'username'=>optional($admin)->username]);
                $op=[];
            }else{
                Log::info($message."  adminId==>". $adminId."username==>".optional($admin)->username,$logOptions);
                $op=$logOptions;
            }
            if(is_array($logOptions)){
                $op=json_encode($logOptions);
            }else{
                $op=json_encode([]);
            }
            DB::table('log_system')->insert(['admin_id'=>$adminId,'user_id'=>$userId,'title'=>$title,'message'=>$message,'options'=>$logOptions]);
        }catch(Exception $e){

        }


    }
}
