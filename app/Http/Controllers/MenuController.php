<?php

namespace App\Http\Controllers;

use App\Mail\DeleteDay;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\Day;
use App\Models\Clinic\Item;
use App\Models\Clinic\Order;
use App\Models\Clinic\Package;
use App\Models\Clinic\Portion;
use App\Models\Clinic\UserDate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use test\Mockery\MockingParameterAndReturnTypesTest;
use function GuzzleHttp\Promise\all;

class MenuController extends MainController
{
    public function getIndex()
    {
        $id = Input::has('user') ? Input::get('user') : false;
        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }

        $days = Day::where('active',1)->where('visible_user',1)->orderBy('ordering','asc')->get();
        if($id)
            $user = User::find($id);
        else
            $user = Auth::user();
        $package = $user->package;

        if(isset(optional($user->package)->meals)){
            $count_meals = count(optional(optional(optional($user->package)->meals)->pluck('id'))->toArray());

        }else{
            $count_meals =0;
        }

        $filled_days = [];
        foreach ($days as $d) {
            $count_orders = Order::where('user_id',$user->id)
                ->where('day_id',$d->id)
                ->count();

            $filled_days[$d->id] = ($count_orders == $count_meals) ? true : false;
            //	var_dump(Order::where('user_id',$user->id)
            //						->where('day_id',$d->id)->get()->toArray());
            //	echo '<br/>';
        }
        //var_dump($filled_days);
        //echo <br/>;
        //var_dump($count_orders);
        //echo '<br/>';
        // var_dump($count_meals);
        //echo '<br/>';
        //var_dump($user->package->meals->toArray());
        return View::make('menu.order')
            ->with('days',$days)
            ->with('user',$user)
            ->with('package',$package)
            ->with('filled_days',$filled_days)
            ->withTitle(trans('main.Menu'));
    }

    public function getIndex2()
    {
        $id = Input::has('user') ? Input::get('user') : false;
        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }

        $days = Day::where('active',1)->where('visible_user',1)->orderBy('ordering','asc')->get();
        if($id)
            $user = User::find($id);
        else
            $user = Auth::user();
        $package = $user->package;

        if(isset(optional($user->package)->meals)){
            $count_meals = count(optional(optional(optional($user->package)->meals)->pluck('id'))->toArray());

        }else{
            $count_meals =0;
        }

        $filled_days = [];
        foreach ($days as $d) {
            $count_orders = Order::where('user_id',$user->id)
                ->where('day_id',$d->id)
                ->count();

            $filled_days[$d->id] = ($count_orders == $count_meals) ? true : false;
            //	var_dump(Order::where('user_id',$user->id)
            //						->where('day_id',$d->id)->get()->toArray());
            //	echo '<br/>';
        }
        //var_dump($filled_days);
        //echo <br/>;
        //var_dump($count_orders);
        //echo '<br/>';
        // var_dump($count_meals);
        //echo '<br/>';
        //var_dump($user->package->meals->toArray());
        return View::make('menu.fullCalendar')
            ->with('days',$days)
            ->with('user',$user)
            ->with('package',$package)
            ->with('filled_days',$filled_days)
            ->withTitle(trans('main.Menu'));
    }
    public function getApproveAll($id)
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }

        $orders = Order::where('user_id',$id)
            ->update(['approved' => 1]);

        return Redirect::to('menu/user-menu/'.$id);

    }
    public function getUserDays(Request $request)
    {
        if (!$this->notUser()) {
            $today=   date("Y-m-d");
            $date = strtotime("+3 day", strtotime($today));
            $firstValidDay=date("Y-m-d",$date);
            $days=UserDate::where('user_id',Auth::user()->id)->where('freeze',0)->where('date','>=',$firstValidDay)->get();
            //edited by imtiaz
            $days_off=UserDate::where('user_id',Auth::user()->id)->where('freeze',0)->where('date','<',$firstValidDay)->get();
            if($days->count()<=0){
                return response()->json(['result'=>false]);
            }else{
                $listDays=[];
                $listEvents=[];
                //show passed days
                foreach($days_off as $offday){
                    array_push($listDays,$offday->date);
                    $r=$offday->isMealSelected==1?"Meal is Selected":"Meal is Not Selected";
                    $event=['title'=>"ICON "."  ".$r,"start"=>$offday->date,'img'=>'arrows-alt',"backgroundColor"=>"#ff0000","editable"=>false];
                    array_push($listEvents,$event);
                }
                foreach ($days as $day) {
                    array_push($listDays,$day->date);
//                    $event=['title'=>"ICON "."  ".date("l",strtotime($day->date)),"start"=>$day->date,'img'=>'arrows-alt',"backgroundColor"=>$day->isMealSelected==1?"#1c9c5a":"#17a2b8"];
                    $r=$day->isMealSelected==1?"Meal is Selected":"Meal is Not Selected";
                    $event=['title'=>"ICON "."  ".$r,"start"=>$day->date,'img'=>'arrows-alt',"backgroundColor"=>$day->isMealSelected==1?"#1c9c5a":"#17a2b8","editable"=>true];
                    array_push($listEvents,$event);
                }
                return response()->json(['result'=>true,'listDays'=>$listDays,'listEvents'=>$listEvents]);
            }


        }



    }
    public function saveDays(Request $request)
    {

        $today             = date("Y-m-d");
        $date              = strtotime("+3 day", strtotime($today));
        $firstValidDay     = date("Y-m-d",$date);
        $arraySelectedDays = json_decode($request->selectedDays);

        $dayCount = UserDate::where('user_id',Auth::user()->id)->where('freeze',0)->where('date','>=',$firstValidDay)->count();
        $days     = UserDate::where('user_id',Auth::user()->id)->where('date','>=',$firstValidDay)->where('freeze',0)->whereNotIn('date',$arraySelectedDays)->get()->pluck('id')->toArray();
		
		$userDetails = User::find(Auth::user()->id);
        if(!empty($userDetails->package_id)){
		$packageid = $userDetails->package_id;
        if(count($days)>=1){
            $countV=$dayCount;

            if(count($arraySelectedDays)>=1 && $countV>=1){
                \Log::info("delete order and date by user ==>".Auth::user()->id,$days);
                Order::whereIn('date_id',$days)->where('user_id',Auth::user()->id)->delete();
                UserDate::whereIn('id',$days)->where('user_id',Auth::user()->id)->delete();
            }
            foreach ($arraySelectedDays as $day) {
                if($countV>=1) {
                    $newOrExistUserDate = UserDate::where('user_id', Auth::user()->id)->where('date', $day)->first();
                    if (is_null($newOrExistUserDate)) {
                        \Log::info("create day by user (change selected days) userId==>".Auth::user()->id." and newDate==>".$day);
                        //UserDate::create(['user_id' => Auth::user()->id, 'date' => $day ,'package_id'=>$userDetails->package_id,'update_status'=>'user']);
                        $userDate = new UserDate;
                        $userDate->user_id       = Auth::user()->id;
                        $userDate->date          = $day;
                        $userDate->package_id    = $packageid;
                        $userDate->update_status = 'user';
                        $userDate->save();
						
						$countV=$countV-1;
                    }
                }
            }

            return response()->json(['result'=>true,'change'=>true]);
          }
		
		}
        return response()->json(['result'=>true,'change'=>false]);

    }
	
	
    public function getOrderByDate($date)
    {

        $userDate=UserDate::find($date);
        $user=User::find(Auth::user()->id);
        $selectedItem=$this->getOrderUser(Auth::user()->id,$userDate->date);
        $validItems=$this->getMeals($user,$userDate->date);
        return View::make('menu.newIframe')->with(['validItems'=>$validItems,'selectedItem'=>$selectedItem,'user'=>$user,'dateId'=>$userDate->id]);


    }
    public function getListHtmlDoctor($userId,$orderId,$date)
    {
        $userDate     = UserDate::where('date',$date)->where('user_id',$userId)->first();
        $user         = User::find($userId);
        $order        = Order::where('id',$orderId)->where('user_id',$userId)->first();
        $selectedItem = $this->selectedSingleOrder($order);
        $validItems   = $this->getMeals($user,$userDate->date,$order->meal_id);
        $portions     = Portion::all()->toArray();

        return View::make('menu.newIframeDoctor')->with(['validItems'=>$validItems,'selectedItem'=>$selectedItem,'user'=>$user,'dateId'=>$userDate->id,'order'=>$order,'portions'=>$portions]);
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
    public function getMeals($user,$date,$mealId=null)
    {

        if(isset($date)){
            $unixTimestamp = strtotime($date);
            $dayOfWeek = date("l", $unixTimestamp);
            $dayNumber = intval(date("d", $unixTimestamp));
            $validDayName=$this->selectWeek($user->membership_start,$dayOfWeek,$dayNumber);

            $day=$this->getDayId($validDayName);
            if(isset($day)){
                $dayId=$day->id;
            }else{
                $dayId=null;
            }
        }else{
            $dayId=null;
        }

        $day=UserDate::where('date',$date)->where('user_id',$user->id)->first();


        if(isset($day)){
            if(isset($day->package_id)){
                $packId=$day->package_id;
            }else{
                $packId=$user->package_id;
            }
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

        $result=Package::with(['meals.categories.items.addons','meals'=>function($r)use($mealId){
            if($mealId!=null){
                $r->where('meals.active',1)->where('meal_id',$mealId);
            }else{
                $r->where('meals.active',1);
            }

        },'meals.categories'=>function($e)use($catId){
            $e->where('active',1)->whereIn('id',$catId);
        },'meals.categories.items'=>function($o)use($dayId){
            $o->whereHas('days',function ($r2)use($dayId){
                $r2->where('days.id',$dayId);
            })->where('active',1);
        },'meals.categories.items.addons'=>function($q){
            $q->where('active',1);
        }])->whereId($packId)->first();

        return $result->meals;

    }
    public function saveOrder(Request $request)
    {
        $total_meal=$request->total_meal;
        $dateId=$request->dateId;
        $items=$request->items;
        $user=User::find(Auth::user()->id);
        $userId=$user->id;

        $userDate=UserDate::find($dateId);
        //check total meal count is same or not - edited by imtiaz
        if(!empty($items) && $total_meal==count($items)){
            
            //check meals counts and user chosen meals count
            $totalMealsByPackage = DB::table('packages_meals')->where('package_id',$user->package_id)->get()->count();
            if (count($items) != $totalMealsByPackage) {
                return response()->json(['result'=>false]);
            }

            $order=Order::where('date_id',$dateId)->delete();
            $day=date('l',strtotime($userDate->date));
            $dayy=Day::where('titleEn',$day)->first();

            if(isset($items)){
                foreach ($items as $mealId=>$item) {
                    $arr=explode("|",$item['item']) ;
                    $categoryId=trim($arr[0]);
                    $itemId=trim($arr[1]);

                    $order=new Order();
                    $order->user_id=$userId;
                    $order->meal_id=trim($mealId);
                    $order->category_id=$categoryId;
                    $order->item_id=$itemId;
                    $order->date_id=$dateId;
                    $order->day_id=$dayy->id;
                    $order->approved=1;
                    $portion=$this->selectPortion($userId,trim($mealId),$user->package_id);
                    if(isset($portion)){
                        $order->portion_id=$portion;
                    }

                    $order->save();
                    if(array_key_exists('addons',$item)){
                        $newArr=[];
                        foreach ($item['addons'] as $addOns) {
                            array_push($newArr,trim($addOns));
                        }
                        $order->addons()->attach($newArr);
                    }

                }
                $userDate->isMealSelected=1;
                $userDate->update_status='user';
                $userDate->save();
                \Log::info("User save order for day ====>".$userDate->date."  UserId===>".$userId);
            }

            return response()->json(['result'=>true]);
        }else{
            return response()->json(['result'=>false]);
        }

    }
    public function selectPortion($userId,$mealId,$packageId)
    {
        $res= DB::table("portion_log")->where("package_id",$packageId)->where('user_id',$userId)->where('meal_id',$mealId)->select(['portion'])->first();
        return optional($res)->portion;
    }
    public function saveOrderByDoctor(Request $request)
    {
        $portion_save = false; //edited by imtiaz

        $dateId=$request->dateId;
        $items=$request->items;
        $user=User::find($request->user_id);
        $userId=$user->id;

        $portion_id=$request->portion_id;
        //edited by imtiaz
        if(!empty($portion_id)){
            $portion_save = true;
        }
        //end edited by imtiaz

        $userDate=UserDate::find($dateId);

        Order::where('id',$request->order_id)->delete();
        $day=date('l',strtotime($userDate->date));
        $dayy=Day::where('titleEn',$day)->first();

        if(isset($items)){
            foreach ($items as $mealId=>$item) {
                $arr=explode("|",$item['item']) ;
                $categoryId=trim($arr[0]);
                $itemId=trim($arr[1]);

                $order=new Order();
                $order->user_id=$userId;
                $order->meal_id=trim($mealId);
                $order->category_id=$categoryId;
                $order->item_id=$itemId;
                $order->date_id=$dateId;
                $order->day_id=$dayy->id;
                $order->approved=1;
                $order->portion_id=$portion_id;
                $order->save();

                if($portion_save) {
                    $this->portionLog($userId,$itemId,$portion_id,$mealId,$user->package_id);
                    Order::where('user_id',$userId)
                        ->where('meal_id',trim($mealId))
                        ->update([
                            'portion_id' =>$portion_id
                        ]);
                }


                if(array_key_exists('addons',$item)){
                    $newArr=[];
                    foreach ($item['addons'] as $addOns) {
                        array_push($newArr,trim($addOns));
                    }
                    $order->addons()->attach($newArr);
                }

            }
            $userDate->isMealSelected=1;
            $userDate->update_status='dietitian';
            $userDate->save();
        }
        return response()->json(['result'=>true]);
    }
    private function portionLog($userId,$itemId,$portion,$mealId,$packageId){
        $res= DB::table("portion_log")->where('user_id',$userId)->where('meal_id',$mealId)->where('package_id',$packageId)->select(['id'])->first();
        if(isset($res)){
            DB::table("portion_log")->where('user_id',$userId)->where('meal_id',$mealId)->where('package_id',$packageId)->update(['portion'=>$portion]);
        }else{
            DB::table("portion_log")->insert(['user_id'=>$userId,'meal_id'=>$mealId,'item_id'=>$itemId,'portion'=>$portion,'package_id'=>$packageId]);
        }
    }
    private function getDayId($dayName){
        return  DB::table("days")->where('titleEn',$dayName)->select(['id'])->first();
    }
    public function selectedSingleOrder($order)
    {
        $arr=[];
        if(isset($order)){
            $addOnIds=DB::table('orders_addons')->where('order_id',$order->id)->pluck('addon_id');

            $nOrder=Order::with(['meal.categories.items.addons','meal.categories'=>function($r)use($order){
                $r->where('categories.id',$order->category_id);
            },'meal.categories.items'=>function($e)use($order){
                $e->where('items.id',$order->item_id);
            },'meal.categories.items.addons'=>function($f)use($order,$addOnIds){
                $f->whereIn('addons.id',$addOnIds);
            }])->where('id',$order->id)->first();
            if(isset($nOrder)){
                $arr[$nOrder->meal->id]=['catId'=>$nOrder->meal->categories[0]->id,'itemId'=>$nOrder->meal->categories[0]->items[0]->id,'addons'=>$nOrder->meal->categories[0]->items[0]->addons->pluck('id')];
            }

        }
        return $arr;
    }
    public function getOrderUser($userId,$date)
    {
        $userDate=UserDate::where("user_id",$userId)->where("date",$date)->where('freeze',0)->orderBy('id','desc')->first();

        try{
            $arraYorder=[];
            $arr=[];
            if(isset($userDate)){
                $orders=Order::where('date_id',$userDate->id)->where('user_id',$userId)->get();
                if(isset($orders)){
                    foreach ($orders as $order) {
                        $addOnIds=DB::table('orders_addons')->where('order_id',$order->id)->pluck('addon_id');

                        $nOrder=Order::with(['meal.categories.items.addons','meal.categories'=>function($r)use($order){
                            $r->where('categories.id',$order->category_id);
                        },'meal.categories.items'=>function($e)use($order){
                            $e->where('items.id',$order->item_id);
                        },'meal.categories.items.addons'=>function($f)use($order,$addOnIds){
                            $f->whereIn('addons.id',$addOnIds);
                        }])->where('id',$order->id)->first();
                        if(isset($nOrder)){
                            $arr[$nOrder->meal->id]=['catId'=>$nOrder->meal->categories[0]->id,'itemId'=>$nOrder->meal->categories[0]->items[0]->id,'addons'=>$nOrder->meal->categories[0]->items[0]->addons->pluck('id')];
                        }
                        // $nOrder->meal
                        //array_push($arraYorder,$arr);
                    }
                }
                return $arr;
            }

        }catch (\Exception $e){
            return [];

        }

    }
    public function listDays()
    {
        if ($this->notUser()) {
            return $this->dontAllow();
        }

        $today=   date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=date("Y-m-d",$date);
        $days=UserDate::where('user_id',Auth::user()->id)->where('date','>=',$firstValidDay)->orderBy('date','asc')->where('freeze',0)->get()->chunk(4);
        return \view("menu.listDays")->with(['userDate'=>$days]);


    }
    public function getUserMenu($user_id)
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }

//        $user = User::where('id',$user_id)
////            ->with(['orders_sorted_by_dates' => function ($q) {
////                $q->orderBy('day_id','asc');
////            }])
////            ->with('orders_sorted_by_dates.meal')
////            ->with('orders_sorted_by_dates.category')
////            ->with('orders_sorted_by_dates.item')
////            ->with('orders_sorted_by_dates.portion')
////            ->with('orders_sorted_by_dates.addons')
////            ->with('orders_sorted_by_dates.date')
////            ->with('package')
////            ->where('active',1)
////            ->first();
////
////        if(!$user)
////            return Redirect::to('/')->withMessage([trans('main.No User Has Been Found')]);
////
////        $days = Day::where('active',1)->orderBy('ordering','asc')->get();
////        $package = $user->package;
////        $count_meals = count($user->package->meals->pluck('id')->toArray());
////        $filled_days = [];
////        foreach ($days as $d) {
////            $count_orders = Order::where('user_id',$user->id)
////                ->where('day_id',$d->id)
////                ->count();
////            $filled_days[$d->{'title'.LANG}] = ($count_orders == $count_meals) ? true : false;
////        }
////
////
////        $menu = [];
////
////        if(!$user->orders_sorted_by_dates->isEmpty()) {
////            foreach ($user->orders_sorted_by_dates as $order) {
////
////                $menu[$order->date->date][$order->meal->{'title'.LANG}] = [
////                    'category' => $order->category->{'title'.LANG},
////                    'item' => $order->item->{'title'.LANG},
////                    'portion' => ($order->portion) ? $order->portion->{'title'.LANG} : false,
////                    'addons' => $order->addons->toArray(),
////                    'order' => $order->toArray(),
////                ];
////            }
////        }

        $today=   date("Y-m-d");
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=date("Y-m-d",$date);

        $user=user::find($user_id);
        $menu =[];

        if(isset($user->membership_start)){

            $userdate= DB::table("user_dates")->where("date",">=",$firstValidDay)->orderby('date','asc')->where("freeze",0)->where('user_id',$user->id)->select(['*'])->get();
            $dateid= DB::table("user_dates")->where("date",">=",$firstValidDay)->where("freeze",0)->where('user_id',$user->id)->pluck('id');


            $new=[];
            foreach ($userdate as $item) {
                $new[$item->date]=$this->selectorder($item->id);
            }


            $new2=[];
            foreach ($new as $day=>$orders) {
                foreach ($orders as $order) {
                    if(isset($order)){
                        $new2[$day][optional($order->meal)->titleEn]=[
                            'category' => optional($order->category)->titleEn,
                            'item' => optional($order->item)->titleEn,
                            'portion' => ($order->portion) ? $order->portion->titleEn : false,
                            'addons' => $order->addons->toarray(),
                            'order' => $order->toarray(),
                        ];
                    }
                }
            }
            $menu =$new2;


        }

        return View::make('menu.user')
            ->with('menu',$menu)
            ->with('user',$user)
            ->with('filled_days',[])
            ->withTitle(trans('main.Menu'));


    }
    public function selectOrder($dayId)
    {
        return   Order::with(['day','date','meal','category','item','portion','addons'])->where('date_id',$dayId)->orderBy('meal_id','asc')->get();
    }
    public function getEditOrder($id)
    {
        $order = Order::with('user')
            ->with('user.package')
            ->with('user.package.categories.items')
            ->with('user.package.categories.items.addons')
            ->with('portion')
            ->with('day')
            ->with('day.items')
            ->with('meal')
            ->with('meal.categories')
            ->with('category')
            ->with('addons')
            ->with('item')
            ->find($id);

        if(!$order)
            return ['result' => false];

        $day_items = $order->day->items->pluck('id')->toArray();
        $meal_categories =$order->meal->categories->pluck('id')->toArray();
        // dd($day_items);
        $cats = [];
        $items_addons = [];
        foreach ($order->user->package->categories as $category) {
            if (in_array($category->id, $meal_categories)) {
                $items = [];
                foreach ($category->items as $item) {
                    if (in_array($item->id, $day_items)) {
                        $items[] = $item;
                    }
                    // if ($order->day->items->contains($item->id)) {
                    // 	$items[] = $item;
                    // }
                }
                unset($category->items);
                $category->items = $items;
                $cats[] = $category;
            }
        }

        foreach ($order->addons as $addon) {
            $items_addons[$order->item->id][] = $addon->id;
        }
        unset($order->meal);
        unset($order->user);
        $order->menu = $cats;
        $order->items_addons = $items_addons;
        $order->title = $order->day->{'title'.LANG} . ' ' . $order->meal->{'title'.LANG};
        $order->portions = Portion::all()->toArray();

        return $order;
    }

    public function getApproveOrder($id)
    {
        $order = Order::find($id);

        if(!$order)
            return ['result' => false];


        $order->approved = 1;
        $order->save();

        return $order;
    }

    public function postSaveItem()
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }


        \Log::info("Item Save posted by dietitian ==>".Auth::user()->id.",Day==>".Input::get('day_id').",Meal==>".Input::get('meal_id').", for user ==>".Input::get('user_id'));
		
        $portion_save = false;
        $info = Input::get('items');
        $info['item'] = explode('|', $info['item']);

        $data['day_id'] = Input::get('day_id');
        $data['meal_id'] = Input::get('meal_id');
        if (Input::get('portion_id') != '') {
            $portion_save = true;
            $data['portion_id'] = Input::get('portion_id');
        }
        $data['user_id'] = Input::get('user_id');
        $data['category_id'] = $info['item'][0];
        $data['item_id'] = $info['item'][1];
        $data['approved'] = 1;
        $data['updated_on'] = date("Y-m-d H:i:s");

        $orderDel = Order::where('day_id',$data['day_id'])
            ->where('user_id',$data['user_id'])
            ->where('meal_id',$data['meal_id'])
            ->delete();
			
		\Log::info("order auto removed for editing by dietitian ==>".Auth::user()->id.",Day==>".$data['day_id'].",Meal==>".$data['meal_id'].", for user ==>".$data['user_id']);	

        $order = new Order;
        $order->fill($data);
        $result = $order->save();
        
		\Log::info("Meal edited and saved by dietitian ==>".Auth::user()->id.",result=".json_encode($result));	
		
        if(isset($info['addons']))
            $order->addons()->sync($info['addons']);

        if($portion_save) {
            Order::where('user_id',$data['user_id'])
                ->where('meal_id',$data['meal_id'])
                ->update([
                    'portion_id' => $data['portion_id']
                ]);
        }


        return ['result' => $result];
    }

    public function setSessionFilterUser(Request $request)
    {
        session()->put('typeFilterUser',true);
        return \redirect()->route('list_users');
    }

    public function getUsers()
    {
        if ($this->notDoctor()) {
            return $this->dontAllow();
        }

        $currentClinic = Clinic::find(Auth::user()->clinic_id);

        $users = User::where('role_id',1)
            ->where('active',1)
            ->with(['orders' => function($query) {
                $query->where('approved', 0);
            }])
            //->where('autoapprove_menus', '=', 0)
            // ->where('membership_start','<=',date('Y-m-d'))
           // ->where('membership_end','>=',date('Y-m-d'))
            ->orderBy('created_at','desc');
        //var_dump($users);
        // dd(Input::except('page'));
        if (count(Input::except('page'))) {
            foreach (Input::except('page') as $k => $v) {
                if($k!='filterAllUser')
                $users = $users->whereRaw($k . " LIKE '%" . $v . "%'");
            }
        }


        if(\request()->filterAllUser==null){
            if(count(\request()->all())==1){
                  if(!session()->get('typeFilterUser',false)){
                      session()->put('typeFilterUser',false);
                      $users=$users->whereHas("dates",function ($r){
                          $r->where("date",">=",date("Y-m-d"));
                      });
                  }
            }else{
                session()->put('typeFilterUser',false);
                $users=$users->whereHas("dates",function ($r){
                    $r->where("date",">=",date("Y-m-d"));
                });
            }
           
        }else{
            session()->put('typeFilterUser',true);
        }


        if($currentClinic->can_see_others == 0) {
            $users = $users->where('clinic_id',Auth::user()->clinic_id);
        }
        $users = $users->paginate(10);

        $i = 1;
        foreach ($users as $user)
        {
            $days = Day::where('active',1)->orderBy('ordering','asc')->get();
            $package = $user->package;
            if($user->package != "")
            {
                $count_meals = count($user->package->meals->pluck('id')->toArray());
                $filled_days = [];
                $user_error_mark = 0;
                foreach ($days as $d) {
                    $count_orders = Order::where('user_id',$user->id)
                        ->where('day_id',$d->id)
                        ->count();
                    $count_not_approved = Order::where('user_id',$user->id)
                        ->where('day_id',$d->id)
                        ->where('approved',0)
                        ->count();
                    $filled_days[$d->id] = ($count_orders == $count_meals) ? true : false;
                    if(($filled_days[$d->id]==true)&&($count_not_approved>0))
                    {
                        $user_error_mark = 1;
                    }
                }
            } else { $user_error_mark = 0; }
            if($user_error_mark == 1)
            {
                $user->error_mark = 1;
            }
            else
            {
                $user->error_mark = 0;
            }

        }




        // dd($this->queries());
        return View::make('user.grid')
            ->with('users',$users)
            ->withTitle(trans('main.Users'));

    }

    function autoApproveMenu()
    {
        if(Input::get("isChecked") == 'true')
        {
            echo json_encode(["result"=>"SUCCESS", "res"=>"TRUE"]);
            DB::update("UPDATE users SET autoapprove_menus = 1 WHERE id = ?", array(Input::get("userid")));
            $this->getApproveAll(Input::get("userid"));
        }
        else if(Input::get("isChecked") != 'true')
        {
            echo json_encode(["result"=>"SUCCESS", "res"=>"FALSE"]);
            DB::update("UPDATE users SET autoapprove_menus = 0 WHERE id = ?", array(Input::get("userid")));
        }


    }


    public function postSave()
    {

        if ($this->notUser() && $this->notDoctor()) {
            return $this->dontAllow();
        }
        $data['day_id']     = Input::get('day');
        $package            = Input::get('package');
        $data['user_id']    = Input::has('user') ? Input::get('user') : Auth::user()->id;
        $data['approved']   = 0;
        $data['updated_on'] = date("Y-m-d H:i:s");

        $items = Input::get('items');
        if($items==null){
            return ['result' => true];
        }

        if (count($items) == 0) {
            return ['result' => true];
        }
        foreach ($items as $meal => $info) {
            $data['meal_id'] = $meal;
            $info['item'] = explode('|', $info['item']);

            // foreach ($categories as $category => $info) {
            $data['category_id'] = $info['item'][0];
            $data['item_id'] = $info['item'][1];


            // Has Current Order
            $order = Order::where('day_id',$data['day_id'])
                ->where('user_id',$data['user_id'])
                ->where('meal_id',$data['meal_id'])
                ->first();
            // Changes
            if ($order) {
                // Save Current Portion If Not Changed
                // if ($order['item_id'] == $data['item_id']) {
                $data['portion_id'] = $order['portion_id'];
                // }

            }

            // Delete Same Day Same User Meal Choice
            Order::where('day_id',$data['day_id'])
                ->where('user_id',$data['user_id'])
                ->where('meal_id',$data['meal_id'])
                ->delete();

            \Log::info("order removed for editing by dietitian ==>".Auth::user()->id.",Day==>".$data['day_id'].",Meal==>".$data['meal_id'].", for user ==>".$data['user_id']);	
            // Doctor Auto Approved
            if (Auth::user()->role_id == 2) {
                $data['approved'] = 1;
            }


            $order = new Order;
            $order->fill($data);
            $result = $order->save();

            \Log::info("order added for editing by dietitian ==>".Auth::user()->id.",Response==>".json_encode($result));	

            /*
            B. MH
            */
            // DB::update("UPDATE users SET /*standard_menu_id = ?,*/ autoapprove_menus = 0 WHERE id = ?", array(/*2,*/ $data['user_id']));
            /*
            E. MH
            */


            // dd($order);
            if(isset($info['addons']))
                $order->addons()->sync($info['addons']);
            // foreach ($info['addons'] as $addon) {
            // 	$data2['order_id'] = $order->id;
            // 	$data2['addon_id'] = $addon;
            // }
            // dd($data2);

            // }
        }
        return ['result' => $result];
    }

    public function getPackageDay($package,$day_id,$user_id)
    {

        // Get Day & It's Items
        $day = Day::with('items')->find($day_id);
        // Get Current Items Ids
        $dayItems = $day->items->pluck('id')->toArray();
        // $dayItems = array_fetch($dayItems,'id');
        // Bring Up Package
        $packageObj = Package::where('id',$package)
            ->with(['meals' => function ($q)
            {
                return $q->orderBy('ordering','asc');
            }])
            ->with('categories')
            ->with('meals.categories')
            ->first();
        // Get Current Categories Ids
        $packageCategories = $packageObj->categories->pluck('id')->toArray();
        // $packageCategories = array_fetch($packageCategories,'id');
        // Set Package To Array
        $package = $packageObj->toArray();

        $userOrdersObj = Order::with('addons')->where('user_id',$user_id)->where('day_id',$day_id)->get();
        $userOrders = [];
        foreach ($userOrdersObj as $order) {
            $userOrders[$order->meal_id][$order->category_id][$order->item_id] = $order->id;
        }


        // Loop Through Package Meals & Categories & Set Current Items & Filter Meals Categories Based On Package
        foreach ($package['meals'] as $k => $meal) {
            // Save Categories For The Meal
            $mealCategories = $meal['categories'];
            // Unset The Old Categories For Reassign
            unset($package['meals'][$k]['categories']);

            // foreach ($mealCategories as $k2 => $category) {
            // 	if(in_array($category['id'], $packageCategories)) {
            // 		$items = Item::where('category_id',$category['id'])->with('addons')->get()->toArray();

            // 		foreach ($items as $itemKey => $item) {
            // 			if (in_array($item['id'], $dayItems)) {
            // 				$currentSelection = @(in_array($item['id'], array_keys($userOrders[$meal['id']][$category['id']])));
            // 				if ($currentSelection)
            // 					$item['selected'] = true;
            // 				else
            // 					$item['selected'] = false;
            // 				foreach ($item['addons'] as $kkk => $addon) {
            // 					if ($currentSelection) {
            // 						$order_id = $userOrders[$meal['id']][$category['id']][$item['id']];
            // 						$addonsOrdered = $userOrdersObj->find($order_id)->addons->toArray();
            // 						$addonsOrderedIds = array_fetch($addonsOrdered,'id');
            // 						if(in_array($addon['id'], $addonsOrderedIds)) {
            // 							$itemAddons[$kkk]['selected'] = true;
            // 						} else
            // 							$item['addons'][$kkk]['selected'] = false;
            // 					} else
            // 							$item['addons'][$kkk]['selected'] = false;
            // 				}
            // 				$category['items'][] = $item;
            // 			}
            // 		}
            // 		$package['meals'][$k]['categories'][] = $category;
            // 	}
            // }
            foreach ($mealCategories as $k2 => $category) {
                if(in_array($category['id'], $packageCategories)) {
                    $package['meals'][$k]['categories'][$category['id']] = $category;
                    $items = Item::where('category_id',$category['id'])->where('active',1)->with('addons')->get()->toArray();

                    foreach ($items as $itemKey => $item) {
                        if (in_array($item['id'], $dayItems)) {
                            $currentSelection = @(in_array($item['id'], array_keys($userOrders[$meal['id']][$category['id']])));
                            if ($currentSelection)
                                $item['selected'] = true;
                            else
                                $item['selected'] = false;
                            foreach ($item['addons'] as $kkk => $addon) {
                                if ($currentSelection) {
                                    $order_id = $userOrders[$meal['id']][$category['id']][$item['id']];
                                    $addonsOrdered = $userOrdersObj->find($order_id)->addons->pluck('id')->toArray();
                                    $addonsOrderedIds = $addonsOrdered;
                                    // dd($addonsOrdered);
                                    if(@(in_array($addon['id'], $addonsOrderedIds))) {
                                        $item['addons'][$kkk]['selected'] = true;
                                    } else
                                        $item['addons'][$kkk]['selected'] = false;
                                } else
                                    $item['addons'][$kkk]['selected'] = false;
                            }
                            $package['meals'][$k]['categories'][$category['id']]['items'][$item['id']] = $item;
                        }
                    }

                }
            }
        }
        // dd($package);
        unset($package['categories']);

        $day = $day->toArray();
        unset($day['items']);
        $package['day'] = $day;
        return $package;

    }

    public function getPackageDayHtml($package,$day_id,$user_id)
    {

        // Get Day & It's Items
        $day = Day::with('items')->find($day_id);
        // Get Current Items Ids
        $dayItems = $day->items->pluck('id')->toArray();
        //$dayItems = array_fetch($dayItems,'id');
        // Bring Up Package
        $packageObj = Package::where('id',$package)
            ->with(['meals' => function ($q)
            {
                return $q->orderBy('ordering','asc');
            }])
            ->with('categories')
            ->with('meals.categories')
            ->first();
        // Get Current Categories Ids
        $packageCategories = $packageObj->categories->pluck('id')->toArray();
        // $packageCategories = array_fetch($packageCategories,'id');
        // Set Package To Array
        $package = $packageObj->toArray();

        $userOrdersObj = Order::with('addons')->where('user_id',$user_id)->where('day_id',$day_id)->get();
        $userOrders = [];
        foreach ($userOrdersObj as $order) {
            $userOrders[$order->meal_id][$order->category_id][$order->item_id] = $order->id;
        }


        // Loop Through Package Meals & Categories & Set Current Items & Filter Meals Categories Based On Package
        foreach ($package['meals'] as $k => $meal) {
            // Save Categories For The Meal
            $mealCategories = $meal['categories'];
            // Unset The Old Categories For Reassign
            unset($package['meals'][$k]['categories']);

            // foreach ($mealCategories as $k2 => $category) {
            // 	if(in_array($category['id'], $packageCategories)) {
            // 		$items = Item::where('category_id',$category['id'])->with('addons')->get()->toArray();

            // 		foreach ($items as $itemKey => $item) {
            // 			if (in_array($item['id'], $dayItems)) {
            // 				$currentSelection = @(in_array($item['id'], array_keys($userOrders[$meal['id']][$category['id']])));
            // 				if ($currentSelection)
            // 					$item['selected'] = true;
            // 				else
            // 					$item['selected'] = false;
            // 				foreach ($item['addons'] as $kkk => $addon) {
            // 					if ($currentSelection) {
            // 						$order_id = $userOrders[$meal['id']][$category['id']][$item['id']];
            // 						$addonsOrdered = $userOrdersObj->find($order_id)->addons->toArray();
            // 						$addonsOrderedIds = array_fetch($addonsOrdered,'id');
            // 						if(in_array($addon['id'], $addonsOrderedIds)) {
            // 							$itemAddons[$kkk]['selected'] = true;
            // 						} else
            // 							$item['addons'][$kkk]['selected'] = false;
            // 					} else
            // 							$item['addons'][$kkk]['selected'] = false;
            // 				}
            // 				$category['items'][] = $item;
            // 			}
            // 		}
            // 		$package['meals'][$k]['categories'][] = $category;
            // 	}
            // }
            foreach ($mealCategories as $k2 => $category) {
                if(in_array($category['id'], $packageCategories)) {
                    $package['meals'][$k]['categories'][$category['id']] = $category;
                    $items = Item::where('category_id',$category['id'])->where('active',1)->with('addons')->get()->toArray();

                    foreach ($items as $itemKey => $item) {
                        if (in_array($item['id'], $dayItems)) {
                            $currentSelection = @(in_array($item['id'], array_keys($userOrders[$meal['id']][$category['id']])));
                            if ($currentSelection)
                                $item['selected'] = true;
                            else
                                $item['selected'] = false;
                            foreach ($item['addons'] as $kkk => $addon) {
                                if ($currentSelection) {
                                    $order_id = $userOrders[$meal['id']][$category['id']][$item['id']];
                                    $addonsOrdered = $userOrdersObj->find($order_id)->addons->pluck('id')->toArray();
                                    $addonsOrderedIds = $addonsOrdered;
                                    // dd($addonsOrdered);
                                    if(@(in_array($addon['id'], $addonsOrderedIds))) {
                                        $item['addons'][$kkk]['selected'] = true;
                                    } else
                                        $item['addons'][$kkk]['selected'] = false;
                                } else
                                    $item['addons'][$kkk]['selected'] = false;
                            }
                            $package['meals'][$k]['categories'][$category['id']]['items'][$item['id']] = $item;
                        }
                    }

                    if(empty($package['meals'][$k]['categories'][$category['id']]['items'])) {
                        unset($package['meals'][$k]['categories'][$category['id']]);
                    }
                }
            }
            if(empty($package['meals'][$k]['categories'])) {
                unset($package['meals'][$k]);
            }

        }
        // dd($package);
        unset($package['categories']);

        $day = $day->toArray();
        unset($day['items']);
        $package['day'] = $day;
        // dd($package);
        return View::make('menu.iframe')
            ->with('package',$package);

    }
}
