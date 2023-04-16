<?php
namespace App\Http\Controllers\Api\V2;
use App\Events\Sms;
use App\Models\App\UserWeekProgress;
use App\Models\CashBack;
use App\Models\Clinic\Day;
use App\Models\Clinic\Item;
use App\Models\Clinic\Order;
use App\Models\Clinic\OrderTemp;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Payment;
use App\Models\Clinic\UserDateTemp;
use App\Models\Sms as MesageSms;
use App\Http\Controllers\Api\MainApiController;
use App\Models\Clinic\Package;
use App\Models\ReferralUser;
use App\Models\FutureRenewalPackage;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class UserTempController extends MainApiController
{

    use AuthenticatesUsers;

    public function __construct()
    {
        parent::__construct();
    }

	public function getMeals(Request $request)
    {
        if(empty(auth()->user()->id)){
		return  $this->sendResponse(205,['data'=>[],'message'=>'Please login to access this']);
		}

		$user = $this->getUser($request);
		$pendingSubscription = FutureRenewalPackage::where('user_id',auth()->user()->id)->first();

		if(empty($pendingSubscription->id)){
		return  $this->sendResponse(205,['data'=>[],'message'=>'Future package subscription is not available']);
		}

        $firstDate = self::getLastEndDate(auth()->user()->id);
		$membership_start = $firstDate;

        if(isset($request->day)){
            $date          = $request->day;
            $unixTimestamp = strtotime($date);
            $dayOfWeek     = date("l", $unixTimestamp);
            $dayNumber     = intval(date("d", $unixTimestamp));
            $validDayName  = $this->selectWeek($membership_start,$dayOfWeek,$dayNumber);
            $day           = $this->getDayId($validDayName);

            if(isset($day)){
                $dayId=$day->id;
            }else{
                $dayId=null;
            }
        }else{
            $dayId=null;
        }



        $day=UserDateTemp::where('date',$request->day)->where('user_id',$user->id)->first();

        if(isset($day)){
            if(isset($day->package_id)){
                $packId=$day->package_id;
            }else{
                $packId=$user->package_id;
            }
        }else{
            $packId=$user->package_id;
        }



		if(empty($packId)){
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

       // $result->meals=$this->filterMealsAndCateGory($result->meals);

        if(!isset($result->meals)){
            return  $this->sendResponse(205,['data'=>[],'message'=>trans('main.user_not_subscribe')]);
        }
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

	//get last ending date
    public static function getLastEndDate($userid){
        $datesInfo = DB::table('user_dates_temp')->where('user_id',$userid)->where('freeze',0)->orderBy('date','asc')->first();
		$lastDate  = !empty($datesInfo->date)?$datesInfo->date:date('Y-m-d');
		if($lastDate >= date('Y-m-d')){
		$lastDate  = $lastDate;
		}else{
		$lastDate  = date('Y-m-d');
		$lastDate  = date('Y-m-d', strtotime($lastDate. ' + 1 days'));
		}
		return $lastDate;
     }

	/**@param Request $request
     * @return \Illuminate\Http\JsonResponse
     * return User Object and return status and message
     * @api
     * create new User
     */

    public function logUserActivity($title,$options=null,$devicetype=null)
    {
	    $devicetype = !empty($devicetype)?$devicetype:'NA';
        if(is_array($options)){
            Log::info("UserInApp::{".$devicetype.")->".$title,$options);
        }else{
            Log::info("UserInApp::{".$devicetype.")->".$title);
        }


    }

	//save item by date
   public function saveItem(Request $request)
    {

        try{
            $validator = Validator::make($request->only(['day_id','males']),[ 'day_id' => 'required','males'=>'required|array','males.*'=>'array']);
            if ($validator->fails()) {
                return $this->sendResponse(400,['data'=>[],'message'=>$validator->errors()->first()]);
            }

            $user  = $this->getUser($request);
            $males = $request->males;
            $day   = UserDateTemp::where('id',$request->day_id)->where('user_id',$user->id)->first();

            if(isset($day->package_id)){
                $packId=$day->package_id;
            }else{
                $packId=$user->package_id;
            }

            $change=false;
            if(isset($day)){

                $today         = date("Y-m-d");
                $date          = strtotime("+3 day", strtotime($today));
                $firstValidDay = date("Y-m-d",$date);
                /*
                if($day->date < $firstValidDay){
                    return $this->sendResponse(400,['data'=>[],'message'=>trans('main.not_valid_change_order')]);

                }*/

                $unixTimestamp = strtotime($day->date);
                $dayOfWeek     = date("l", $unixTimestamp);
                $dayId         = Day::where('titleEn',$dayOfWeek)->first();

                DB::table('orders_temp')->where('user_id',$user->id)->where('date_id',$day->id)->delete();

				$this->logUserActivity("Temp orders added  userId==>".$user->id,null,$user->deviceType);

                foreach ($males as $maleArray) {
                    if(array_key_exists('male_id',$maleArray) && array_key_exists('category_id',$maleArray)&& array_key_exists('item_id',$maleArray) && array_key_exists('addons',$maleArray)){


                                $order=new OrderTemp();
                                $order->category_id = $maleArray['category_id'];
                                $order->item_id     = $maleArray['item_id'];
                                $order->meal_id     = $maleArray['male_id'];
                                $order->date_id     = $day->id;
                                $order->user_id     = $user->id;
                                $order->day_id      = optional($dayId)->id;

                                $portion= $this->selectPortion($user->id,$maleArray['male_id'],$packId);
                                if(isset($portion)){
                                $order->portion_id=$portion;
                                }


                                $order->save();
                                $orderId=$order->id;

                                $this->logUserActivity("set Temp order for day ".$day->date." userId==>".$user->id,null,$user->deviceType);

                                $this->updateAddons($orderId,$maleArray['addons']);

                                $change=true;

                    }
                }
                if($change){
                    $day->isMealSelected=1;
					$day->update_status='user';
                    $day->save();
                }

                $itemDay = $this->itemDay($user->id,$day->id);

                return  $this->sendResponse(200,['data'=>['dayItems'=>$itemDay],'message'=>"successful updated or created day items "]);
                //get day item
            }
            return  $this->sendResponse(205,['data'=>[],'message'=>trans("main.Day_Not_Found")]);

        }catch (\Exception $e){
            return $this->sendResponse(500,['data'=>[],'message'=>$e->getMessage()]);
        }
    }
	//
	 private function itemDay($userId,$dayId){
       return DB::table('orders_temp')->where('day_id',$dayId)->where('user_id',$userId)->select('*')->get()->toArray();

    }
	//
	public function selectPortion($userId,$mealId,$packageId)
    {
        $res= DB::table("portion_log")->where("package_id",$packageId)->where('user_id',$userId)->where('meal_id',$mealId)->select(['portion'])->first();
        return optional($res)->portion;
    }

	//
	private function updateAddons($orderId,$addons)
    {
        if(count($addons)>=1){
            foreach ($addons as $item) {
                DB::table('orders_addons_temp')->insert(['order_id'=>$orderId,'addon_id'=>$item]);
            }
        }
    }
	//
	public function getUserDays(Request $request)
    {
        $user=$this->getUser($request);
        $days = $this->getListUserDays($user->id,$user->membership_start) ;
        return  $this->sendResponse(200,['data'=>['user_days'=>$days],'message'=>""]);
    }

	private function getListUserDays($userId,$statingDate=null)
    {
        $res=DB::table('user_dates_temp')->select(['id','date','freeze','isMealSelected'])->where('user_id',$userId);
        if($statingDate!=null){
            $res=$res->where('date','>=',$statingDate);
        }
        return $res->get()->toArray();
    }

	 public function getOrderUser(Request $request)
    {
        $date     = $request->day;
        $user     = $this->getUser($request);
        $userDate = UserDateTemp::where("user_id",$user->id)->where("date",$date)->where('freeze',0)->orderBy('id','desc')->first();

        try{
            $arraYorder=[];
            if(isset($userDate)){
                $orders=OrderTemp::where('date_id',$userDate->id)->where('user_id',$user->id)->get();
                if(isset($orders)){
                    foreach ($orders as $order) {
                        $addOnIds=DB::table('orders_addons_temp')->where('order_id',$order->id)->pluck('addon_id');

                        $nOrder=OrderTemp::with(['meal.categories.items.addons','meal.categories'=>function($r)use($order){
                            $r->where('categories.id',$order->category_id);
                        },'meal.categories.items'=>function($e)use($order){
                            $e->where('items.id',$order->item_id);
                        },'meal.categories.items.addons'=>function($f)use($order,$addOnIds){
                            $f->whereIn('addons.id',$addOnIds);
                        },'meal'=>function($r){
                            $r->orderBy('meals.ordering','asc');
                        }])->where('id',$order->id)->first();
                        array_push($arraYorder,$nOrder->meal);

                    }
                }
                if(count($arraYorder)>=1){
                    $newArr=[];
                    foreach (collect($arraYorder)->sortBy('ordering')->all() as $item) {
                        array_push($newArr,$item);
                    }
                    return  $this->sendResponse(200,['data'=>['meals'=>$newArr],'message'=>""]);
                }
                return  $this->sendResponse(205,['data'=>[],'message'=>trans("main.empty_reserved_item_in_day")]);


            }

        }catch (\Exception $e){
            return $this->sendResponse(500,['data'=>[],'message'=>trans('main.Server_Error')]);

        }

    }


	 public function setDay(Request $request)
    {
        try{
            $validator = Validator::make($request->all(),['day' => 'required|date']);
            if ($validator->fails()) {
                return $this->sendResponse(400,['data'=>[],'message'=>$validator->errors()->first()]);
            }
            $user = $this->getUser($request);
            $day  = $request->day;
			$userDateInfo = UserDateTemp::where(['id'=>$request->old_day_id])->first();
            $userDate= UserDateTemp::firstOrNew(['user_id'=>$user->id,'date'=>$day]);
            if(is_null($userDate->id)){
			$this->logUserActivity("New Date is added for Temp Dates-->".$user->id.'('.$day.')',null,$user->deviceType);
			$userDate->package_id = $userDateInfo->package_id;
            $userDate->save();
            }
			//remove old date - imtiaz
			if(!empty($request->old_day_id)){
			UserDateTemp::where('user_id',$user->id)->where('id',$request->old_day_id)->delete();
			$this->logUserActivity("Old Temp Date is removed-->".$user->id,null,$user->deviceType);
			}

            return  $this->sendResponse(200,['data'=>['user_days'=>$this->getListUserDays($user->id),'day_id'=>$userDate->id],'message'=>trans('main.user_date_update_success')]);

        }catch (\Exception $e){

            return $this->sendResponse(500,['data'=>[],'message'=>trans('main.Server_Error')]);

        }


    }


	   /////////////////////////////////////////////////////////////////////Imtiaz////////////////////////////////////////////
   public function chooseRandomFoodByDate(Request $request)
    {

        try{
            $user=$this->getUser($request);
            $days=DB::table('user_dates_temp')->where('date','=',$request->day)->where('user_id',$user->id)->where('isMealSelected',0)->where('freeze',0)->select('*')->get();

			$userpkg=DB::table('user_dates_temp')->where('user_id',$user->id)->orderBy('date','asc')->first();

            $package=Package::where('id',$userpkg->package_id)->where('show_mobile',1)->where('active',1)->with(['meals'=>function($q){$q->where('active',1);}])->first();



            if(!isset($package) || empty($package)){
                return  $this->sendResponse(205,['data'=>[],'message'=>trans('main.user_not_subscribe')]);
            }

            foreach ($days as $dayItem) {

                $date = $dayItem;
                $unixTimestamp = strtotime($date->date);
                $dayOfWeek = date("l", $unixTimestamp);
                $dayNumber = date("d", $unixTimestamp);

                $validDayName=$this->selectWeek($userpkg->date,$dayOfWeek,$dayNumber);

                $day=$this->getDayId($validDayName);

                $mainDay=$this->getDayId($dayOfWeek);


                $userDate=UserDateTemp::find($dayItem->id);

                if(isset($dayItem->package_id)){
                    $packId=$dayItem->package_id;
                }else{
                    $packId=$user->package_id;
                }



                $cats=Package::with(["categories"=>function($r){
                    $r->where('active',1);
                }])->whereId($packId)->first();



                if(isset($cats)){
                    $catId=$cats->categories->pluck('id')->toArray();
                }else{
                    $catId=[];
                }

               // return $package->meals;

                if(isset($package->meals)){
                    foreach ($package->meals as $meal) {

                        $res= DB::table('meals')
                            ->join('categories','meals.id','=','categories.meal_id')
                            ->join('items','categories.id','=','items.category_id')
                            ->join('items_days','items.id','=','items_days.item_id')
                            ->where('items_days.day_id',$day->id)
                            ->where('categories.active',1)
                            ->where('items.active',1)
                            ->where('meals.id',$meal->id)
                            ->whereIn('categories.id',$catId)
                            ->orderBy('items.rating','desc')
                            ->select(['items.id as item_id','categories.id as category_id'])->first();

                        if(isset($res) && !empty($res)){
                            $order=OrderTemp::firstOrNew(['user_id'=>$user->id,'day_id'=>$mainDay->id,'meal_id'=>$meal->id,'category_id'=>$res->category_id,'item_id'=>$res->item_id,'date_id'=>$dayItem->id]);
                            $order->approved=1;
                            $order->day_id=$mainDay->id;
                            $order->user_id=$user->id;
                            $order->meal_id=$meal->id;
                            $order->category_id=$res->category_id;
                            $order->item_id=$res->item_id;
                            $order->date_id=$dayItem->id;

                            $portion= $this->selectPortion($user->id,$meal->id,$packId);
                            if(isset($portion)){
                                $order->portion_id=$portion;
                            }


                            $order->save();
                            $userDate->isMealSelected=1;
                            //update pkg id if empty
                            if(empty($userDate->package_id) && !empty($packId)){
                            $userDate->package_id=$packId;
                            }
                            //end
                            $userDate->save();

                            $this->logUserActivity("choseRandom food  for temp date ".$userDate->date." and meal==>".$meal->id."  userId==>".$user->id,null,$user->deviceType);



                        }else{
                            $res= DB::table('meals')
                                ->join('categories','meals.id','=','categories.meal_id')
                                ->join('items','categories.id','=','items.category_id')
                                ->join('items_days','items.id','=','items_days.item_id')
                                ->where('items_days.day_id',$mainDay->id)
                                ->where('categories.active',1)
                                ->where('items.active',1)
                                ->whereIn('categories.id',$catId)
                                ->orderBy('items.rating','desc')
                                ->select(['items.id as item_id','categories.id as category_id'])->first();

                            if(isset($res)){
                                $order=OrderTemp::firstOrNew(['user_id'=>$user->id,'day_id'=>$mainDay->id,'meal_id'=>$meal->id,'category_id'=>$res->category_id,'item_id'=>$res->item_id,'date_id'=>$dayItem->id]);
                                $order->approved=1;
                                $order->day_id=$mainDay->id;
                                $order->user_id=$user->id;
                                $order->meal_id=$meal->id;
                                $order->category_id=$res->category_id;
                                $order->item_id=$res->item_id;
                                $order->date_id=$dayItem->id;

                                $portion= $this->selectPortion($user->id,$meal->id,$packId);
                                if(isset($portion)){
                                    $order->portion_id=$portion;
                                }


                                $order->save();
                                $userDate->isMealSelected=1;
                                //update pkg id if empty
                                if(empty($userDate->package_id) && !empty($packId)){
                                $userDate->package_id=$packId;
                                }
                                //end
                                $userDate->save();
                                $this->logUserActivity("V2 : choseRandom food  for date ".$userDate->date." ,PackageID=".$userDate->package_id." and meal==>".$meal->id."  userId==>".$user->id,null,$user->deviceType);

                            }



                        }

                    }

                }



            }
            $days=$this->getListUserDays($user->id,$user->membership_start);
            return  $this->sendResponse(200,['data'=>['user_days'=>$days],'message'=>trans('main.user_date_update_success')]);

        }catch (\Exception $e){
            return $this->sendResponse(500,['data'=>[],'message'=>$e->getMessage()]);
        }

    }

   /////////////////////////////////////////////////////////////////////Imtiaz End///////////////////////////////////////////////
}
