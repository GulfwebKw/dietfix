<?php
namespace App\Http\Controllers\Api\V3;
use App\Http\Controllers\Api\MainApiController;

use App\Models\Setting;
use App\Models\Discount;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Guest;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;

class GuestController extends MainApiController
{

public function createDates($unique_id,$package_id,$duration_id,$starting_date){
if(empty($unique_id) || empty($package_id) || empty($duration_id) || empty($starting_date)){
return  $this->sendResponse(400,['data'=>$list,'message'=>'Please make sure you have chosen the required information.']);
}
$packageDuration = PackageDurations::find($duration_id);
$package         = Package::find($package_id);
if(empty($packageDuration->id) || !empty($package->id)){
return  $this->sendResponse(400,['data'=>$list,'message'=>'Package or Package duration record not found']);
}
//remove existing records
Guest::where('unique_id',$unique_id)->delete();
//add gues details
$newguest = new Guest;
$newguest->
$countDay = $packageDuration->count_day;

$this->makeUserWeekProgress($countDay,$userId,$startingDay=null,$packageId);
}

private function makeUserWeekProgress($countDay,$userId,$startingDay=null,$packageId){

        if(intval($countDay)>1){

            if($startingDay==null){
                $today = date("Y-m-d");
                $date  = strtotime("+3 day", strtotime($today));
                $firstDate=date("Y-m-d", $date);
            }else{
                $firstDate=$startingDay;
            }


            $arrayDay=[];
            array_push($arrayDay,$firstDate);
            for($j=1;$j<$countDay;$j++){
                $nextDay= date('Y-m-d',strtotime("+$j day", strtotime($firstDate)));
                array_push($arrayDay,$nextDay);
            }

            foreach ($arrayDay as $item) {
                DB::table('user_dates')->insert(['date'=>$item,'user_id'=>$userId,'package_id'=>$packageId]);
            }

            if($forFuture==0){
                DB::table("user_week_progress")->where('user_id',$userId)->delete();
                $countWeek = ceil($countDay / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $userId, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                }
            }

        }
    }
//get packages
public function getPackageList(Request $request)
    {
        $list= Package::with(['meals'=>function($r){
            $r->where('active',1);
        },'packageDurations'=>function($r){
            $r->where('active',1)->where('show_mobile',1);
        }])->where("show_mobile",1)->where('active',1)->get();
        if($list->count()>=1){
            $list->map(function ($item){
                if(isset($item->photo)){
                    return $item->photo = !empty($item->photo)?url('/media/packages/'.$item->photo):url('/images/no-image.jpg');
                }
            });
            return  $this->sendResponse(200,['data'=>$list,'message'=>'send all available packages']);
        }
        return  $this->sendResponse(205,['data'=>$list,'message'=>"record not found"]);
    }


public function saveItem(Request $request)
    {

        try{
            $validator = Validator::make($request->only(['day_id','meals']),['day_id' => 'required','package_id' => 'required','starting_date'=>'required','meals'=>'required|array','meals.*'=>'array']);
            if ($validator->fails()) {
                return $this->sendResponse(400,['data'=>[],'message'=>$validator->errors()->first()]);
            }


            $meals  = $request->meals;
            $packId = $request->package_id;
			$day    = $request->starting_date;


            $change=false;
            if(isset($day)){

                $today         = date("Y-m-d");
                $date          = strtotime("+3 day", strtotime($today));
                $firstValidDay = date("Y-m-d",$date);

                if($day < $firstValidDay){
                    return $this->sendResponse(400,['data'=>[],'message'=>trans('main.not_valid_change_order')]);

                }

                $unixTimestamp = strtotime($day);
                $dayOfWeek     = date("l", $unixTimestamp);
                $dayId         = Day::where('titleEn',$dayOfWeek)->first();



                foreach ($meals as $maleArray) {
                    if(array_key_exists('male_id',$maleArray) && array_key_exists('category_id',$maleArray)&& array_key_exists('item_id',$maleArray) && array_key_exists('addons',$maleArray)){


                                $order = new GuestOrder();
                                $order->category_id = $maleArray['category_id'];
                                $order->item_id     = $maleArray['item_id'];
                                $order->meal_id     = $maleArray['male_id'];
                                $order->date_id     = 0;
                                $order->user_id     = 0;
                                $order->day_id      = optional($dayId)->id;

                                $portion = $this->selectPortion($user->id,$maleArray['male_id'],$packId);
                                if(isset($portion)){
                                $order->portion_id=$portion;
                                }

                                $order->save();
                                $orderId=$order->id;

                        $this->updateAddons($orderId,$maleArray['addons']);
                        $change=true;

                    }
                }
                if($change){
				    $day->isMealSelected=1;
                    $day->update_status='user';
                    $day->save();
                }
                $itemDay=$this->itemDay($user->id,$day->id);
                return  $this->sendResponse(200,['data'=>['dayItems'=>$itemDay],'message'=>"successful update or create day items "]);



                //get day item

            }
            return  $this->sendResponse(205,['data'=>[],'message'=>trans("main.Day_Not_Found")]);

        }catch (\Exception $e){
            return $this->sendResponse(500,['data'=>[],'message'=>$e->getMessage()]);
        }


    }


public function getGuestMeals(Request $request)
    {
        if(empty($request->day)){
		return  $this->sendResponse(500,['data'=>[],'message'=>"Please choose starting date"]);
		}

		if(empty($request->package_id)){
		return  $this->sendResponse(500,['data'=>[],'message'=>"Please choose a package"]);
		}

        if(isset($request->day)){
            $date          = $request->day;
            $unixTimestamp = strtotime($date);
            $dayOfWeek     = date("l", $unixTimestamp);
            $dayNumber     = intval(date("d", $unixTimestamp));
            $validDayName  = $this->selectWeek($date,$dayOfWeek,$dayNumber);
            $day           = $this->getDayId($validDayName);
            if(isset($day)){
                $dayId=$day->id;
            }else{
                $dayId=null;
            }
        }else{
            $dayId=null;
        }



		if(!empty($request->package_id)){
		$packId=$request->package_id;
		}


        $cats=Package::with(["categories"=>function($r){
            $r->where('active',1);
        }])->whereId($packId)->first();

        if(isset($cats)){
            $catId=$cats->categories->pluck('id')->toArray();
        }else{
            $catId=[];
        }


        $result=Package::with(['meals'=>function($r){
                $r->where('meals.active',1);
               },'meals.categories'=>function($e)use($catId,$dayId){
                $e->where('active',1)->whereIn('id',$catId)->whereHas("items",function ($r)use($dayId){
                    $r->where('items.active',1);
                });
               },'meals.categories.items'=>function($o)use($dayId){
                    $o->whereHas('days',function ($r2)use($dayId){
                            $r2->where('days.id',$dayId);
                    })->where('active',1);
                },'meals.categories.items.addons'=>function($q){
                    $q->where('active',1);
                  }])->whereId($packId)->first();


        return  $this->sendResponse(200,['data'=>['prefix_photo_addons'=>url(env('APP_URL').'/media/addons/'),'prefix_photo_item'=>url(env('APP_URL').'/media/items/'),'meals'=>$result->meals],'message'=>""]);

    }


    private function selectWeek($startingDay,$dayName,$dayNumber){
        $now=time();
        $your_date = strtotime($startingDay);
        $daysWeek1=[1,2,3,4,5,6,7,15,16,17,18,19,20,21,29,30,31];
        $daysWeek2=[8,9,10,11,12,13,14,22,23,24,25,26,27,28];
        if(in_array($dayNumber,$daysWeek1)){
            return $dayName;
        }else{
            return $dayName." 2";
        }

    }

	private function getDayId($dayName){
        return  DB::table("days")->where('titleEn',$dayName)->select(['id'])->first();
    }

}
