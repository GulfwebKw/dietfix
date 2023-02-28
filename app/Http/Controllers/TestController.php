<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use Illuminate\Support\Facades\Log;

use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\User;
use App\Models\Clinic\Order;
use App\Models\Clinic\UserDate;
use App\Models\Clinic\UserDateTemp;
use App\Models\Clinic\OrderTemp;
use App\Models\Clinic\Item;

use App\Http\Helpers\Excel;

use DB;

class TestController extends Controller
{

  public function importexcel(){
  $pathExcel=public_path("uploads/itemImport/items.xlsx");
  //dd($pathExcel);
  $xlsx = new Excel($pathExcel);
  if(!empty($xlsx->rows())){
  foreach($xlsx->rows() as $k => $r){
  if(!empty($r[0])){
  $items = Item::where('id',$r[0])->first();
  if(!empty($items->id)){
  $items->titleAr = !empty($r[1])?$r[1]:'';
  $items->titleEn = !empty($r[2])?$r[2]:'';
  $items->protien = !empty($r[3])?$r[3]:'';
  $items->fat     = !empty($r[4])?$r[4]:'';
  $items->carb     = !empty($r[5])?$r[5]:'';
  $items->calories = !empty($r[6])?$r[6]:'';
  $items->save();
  }
  }  
  }
  }          			 
  }
  
  
  public function renew()
    {
        Log::info("Cron-anothet future package started");
        $today=date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        //$enableDayPackage=date("Y-m-d", $date);
		$enableDayPackage = "2021-07-07";
        $result=DB::table("renew_future_package")->where("starting_date",$enableDayPackage)->get();
        if(!empty($result) && count($result)>0){
        foreach($result as $item) {
            $package=Package::find($item->package_id);
            $packageDurationId=PackageDurations::find($item->package_duration_id);
   
			$firstDate = $item->starting_date;
            if(isset($package)){
                $user=User::find($item->user_id);
                if(isset($user)){
                    $user->package_id          = $package->id;
                    $user->package_duration_id = $item->package_duration_id;
					$user->membership_start    = $firstDate;
					$user->membership_end      = date("Y-m-d",strtotime('+360 days '.$item->starting_date));
                    $user->save();
                    Log::info("cron-another future package is updated for ".$user->id);
                }
				//update dates
				$packageId = $package->id;
				$userId    = $item->user_id;
				$countDay  = $packageDurationId->count_day;
				self::updateDates($userId);	
                DB::table("user_week_progress")->where('user_id',$item->user_id)->delete();
				
				Log::info("cron-another user-weekprogress removed, userId = ".$item->user_id);
				
                $countWeek = ceil($packageDurationId->count_day / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $item->user_id, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
					
					Log::info("cron-another user-weekprogress added, userId = ".$item->user_id.",Week=".$i);
                }
            }
             DB::table("renew_future_package")->where("id",$item->id)->delete();
             Log::info("cron-another future package is removed for userid = ".$user->id);
          }
        }
    }
	
	
	public static function updateDates($userId){
            $datesInfo = DB::table('user_dates_temp')->where('user_id',$userId)->orderBy('date','asc')->get();
			if(!empty($datesInfo) && count($datesInfo)>0){
            foreach($datesInfo as $item) {
			    Log::info("cron-tempDate = ".$item->date);
			    if(empty(self::isDateExist($userId,$item->date))){
				$newdate = new UserDate();
				$newdate->date           = $item->date;
				$newdate->user_id        = $userId;
				$newdate->package_id     = $item->package_id;
				$newdate->isMealSelected = $item->isMealSelected;
				$newdate->update_status  = $item->update_status;
				$newdate->save();
				$dayid = $newdate->id;
				self::updateOrder($userId,$dayid,$item->id);
				Log::info("cron-another future package > userId = ".$userId." , Date= ".$item->date.",PackageId = ".$item->package_id);
				}
				Log::info("cron-removed tempDate = ".$item->date);
				self::removeTempDate($item->id);
            }
			
		}

	}
	//get last ending date
    public static function getLastEndDate($userid){
        $datesInfo = DB::table('user_dates_temp')->where('user_id',$userid)->orderBy('date','asc')->first();
		$lastDate  = !empty($datesInfo->date)?$datesInfo->date:date('Y-m-d');
		return $lastDate;
    }
   
   public static function updateOrder($userId,$date_id,$old_date_id){
   $ordersInfo = DB::table('orders_temp')->where('user_id',$userId)->where('date_id',$old_date_id)->get();
   if(!empty($ordersInfo) && count($ordersInfo)>0){
   foreach($ordersInfo as $orders){
                                
                                $order=Order::firstOrNew(['user_id'=>$userId,'day_id'=>$orders->day_id,'meal_id'=>$orders->meal_id,'category_id'=>$orders->category_id,'item_id'=>$orders->item_id,'date_id'=>$date_id]);
							
                                $order->category_id = $orders->category_id;
                                $order->item_id     = $orders->item_id;
                                $order->meal_id     = $orders->meal_id;
                                $order->date_id     = $date_id;
                                $order->user_id     = $orders->user_id;
                                $order->day_id      = $orders->day_id;
                                $order->portion_id  = $orders->portion_id;
								$order->approved    = 1;
                                $order->save();
                                $orderId=$order->id;  
								
								self::updateAddons($orderId,$orders->id);
								
		   Log::info("cron-another future package - removed temp orders > userId = ".$orders->user_id);
		   Log::info("cron-another future package - added orders from Temp orders > userId = ".$orders->user_id);
                                
		   
		   self::removeTempOrder($orders->id);						
        }
     }
   }
	

   public static function updateAddons($neworderId,$oldorderid)
    {
        $addons = DB::table('orders_addons_temp')->where('order_id',$oldorderid)->get();
        if(!empty($addons) && count($addons)>0){
            foreach ($addons as $item) {
                DB::table('orders_addons')->insert(['order_id'=>$neworderId,'addon_id'=>$item->addon_id]);
				Log::info("cron-another future package - removed temp addons > orderid = ".$oldorderid);
		        Log::info("cron-another future package - added addons from Temp addon > orderid = ".$neworderId);
				DB::table("orders_addons_temp")->where('id',$item->id)->delete();	
            }
	   }		
    }
	
  public static function isDateExist($userId,$date){
  $datesInfo = DB::table('user_dates')->where('user_id',$userId)->where('date',$date)->first();
  if(!empty($datesInfo->id)){
  return true;
  }else{
  return false;
  }
  }	
  
  public static function isOrderExist($user_id,$category_id,$item_id,$meal_id,$date_id,$day_id){
  $datesInfo = Order::where('user_id',$user_id)
               ->where('category_id',$category_id)
			   ->where('item_id',$item_id)
			   ->where('meal_id',$meal_id)
			   ->where('date_id',$date_id)
			   ->where('day_id',$day_id)
			   ->first();
  if(!empty($datesInfo->id)){
  return true;
  }else{
  return false;
  }
  }
  
  //remve temp date
  public static function removeTempDate($id){
   $date = UserDateTemp::find($id);
   $date->delete();
  }
  
  public static function removeTempOrder($id){
   $date = OrderTemp::find($id);
   $date->delete();
  }
  
  
    
}
