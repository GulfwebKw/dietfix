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

class chooseRandomFood extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chooseRandomFood:client';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'chooseRandomFood for client';

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
        Log::info("run crone Job for auto save item ");
        $this->autoSaveItem();

    }
    public function autoSaveItem()
    {

        $validDay=$this->getValidDay();
       // Log::info("valid day date====> ".$validDay);
        $dates= UserDate::where('date','<=',$validDay)->where('isMealSelected',0)->with(['package.meals','user.package.meals'])->get();
        //Log::info("count valid day====> ".  $dates->count());


        foreach ($dates as $dayItem){
		    $validDay = $dayItem->date;
			
            $saved=false;
            $unixTimestamp = strtotime($validDay);
            $dayOfWeek = date("l", $unixTimestamp);
            $dayNumber = date("d", $unixTimestamp);
            if(isset($dayItem->user) ){
                
                if(isset($dayItem->user->package_id)  ){
                    $validDayName=$this->selectWeek($dayItem->user->membership_start,$dayOfWeek,$dayNumber);


                    if(isset($dayItem->package_id)){
                        $packId=$dayItem->package_id;
                    }else{
                        $packId=$dayItem->user->package_id;
                    }

                    $day      = $this->getDayId($validDayName);
                    $mainDay  = $this->getDayId($dayOfWeek);
                    $userDate = UserDate::find($dayItem->id);
					
                    $cats=Package::with(["categories"=>function($r){
                        $r->where('active',1);
                    }])->whereId($packId)->first();

                    //  return $user->package_id;
                    if(isset($cats)){
                        $catId=$cats->categories->pluck('id');
                    }else{
                        $catId=[];
                    }

                    $meals=!is_null($dayItem->package)?optional($dayItem->package)->meals:optional($dayItem->user->package)->meals;

                    if($meals){
                        foreach ($meals as $meal) {
                            $res= DB::table('meals')
                                ->join('categories','meals.id','=','categories.meal_id')
                                ->join('items','categories.id','=','items.category_id')
                                ->join('items_days','items.id','=','items_days.item_id')
                                ->where('items_days.day_id',$day->id)
                                ->where('categories.active',1)
                                ->where('items.active',1)
                                ->where('meals.id',$meal->id)
                                ->whereIn('categories.id',$catId)
                                ->orderBy('items.most_order','desc')
                                ->select(['items.id as item_id','categories.id as category_id','items.most_order'])
                                ->inRandomOrder()->first();

                            if(isset($res) && !empty($res)){

								Log::info("Most Order Item assigned - ID".$res->item_id.'--'.$res->most_order);
								
								
								$order = Order::where('user_id',$dayItem->user->id)
								                ->where('day_id',$mainDay->id)
												->where('meal_id',$meal->id)
												->where('category_id',$res->category_id)
												//->where('item_id',$res->item_id)
												->where('date_id',$dayItem->id)
												->first();
												
								if(empty($order->id)){
								$order = new Order;
								Log::info("New Order issue -1");
								}
								

								Log::info("Cron: Random selection - user_id=".$dayItem->user->id."-day_id=".$mainDay->id."-meal_id=".$meal->id."-category_id=".$res->category_id."-item_id=".$res->item_id."-date_id=".$dayItem->id);
									
								
                                $order->approved   = 1;
                                $order->day_id     = $mainDay->id;
                                $order->user_id    = $dayItem->user->id;
                                $order->meal_id    = $meal->id;
                                $order->category_id=$res->category_id;
                                $order->item_id    = $res->item_id;
                                $order->date_id    = $dayItem->id;

                                $portion= $this->selectPortion($dayItem->user->id,$meal->id,$packId);
                                if(isset($portion)){
                                $order->portion_id=$portion;
                                }
                                $order->save();


                                $userDate->isMealSelected=1;
                                $userDate->update_status='auto';
                                $userDate->created_at=date("Y-m-d H:i:s");
                                $userDate->save();
                                $saved=true;


                            }else{
                                $res= DB::table('meals')
                                    ->join('categories','meals.id','=','categories.meal_id')
                                    ->join('items','categories.id','=','items.category_id')
                                    ->join('items_days','items.id','=','items_days.item_id')
                                    ->where('items_days.day_id',$mainDay->id)
                                    ->where('categories.active',1)
                                    ->where('items.active',1)
                                    ->whereIn('categories.id',$catId)
                                    //->orderBy('items.most_order','desc')
                                    ->select(['items.id as item_id','categories.id as category_id','items.most_order'])
                                    ->inRandomOrder()
                                    ->first(); //

                                if(isset($res)){
                                    
                                    Log::info("Most Order Item assigned - ID".$res->item_id.'--'.$res->most_order);
                                    

									$order = Order::where('user_id',$dayItem->user->id)
								                ->where('day_id',$mainDay->id)
												->where('meal_id',$meal->id)
												->where('category_id',$res->category_id)
												//->where('item_id',$res->item_id)
												->where('date_id',$dayItem->id)
												->first();
												
									if(empty($order->id)){
									$order = new Order;
									Log::info("New Order issue -1");
									}
									
									
								    
									Log::info("Duplicate Order issue -2 user_id=".$dayItem->user->id."-day_id=".$mainDay->id."-meal_id=".$meal->id."-category_id=".$res->category_id."-item_id=".$res->item_id."-date_id=".$dayItem->id);
									
                                    $order->approved=1;
                                    $order->day_id=$mainDay->id;
                                    $order->user_id=$dayItem->user->id;
                                    $order->meal_id=$meal->id;
                                    $order->category_id=$res->category_id;
                                    $order->item_id=$res->item_id;
                                    $order->date_id=$dayItem->id;
                                    $portion= $this->selectPortion($dayItem->user->id,$meal->id,$packId);
                                    if(isset($portion)){
                                    $order->portion_id=$portion;
                                    }
                                    $order->save();
                               
                                    $userDate->isMealSelected=1;
                                    $userDate->update_status='auto';
                                    $userDate->created_at=date("Y-m-d H:i:s");
                                    $userDate->save();
                                    $saved=true;
                                }else{
                                    Log::warning("not found item for create order !. username-->".$dayItem->user->username);
                                }
                            }
                        }
                    }else{
                        Log::warning("not found meals for package user ".$dayItem->user->username);
                    }
                }else{
                    Log::critical("package or package duration not found for user ".$dayItem->user->username."   package_id====>".$dayItem->user->package_id."  package_duration_id".$dayItem->user->package_duration_id);
                }
                if($saved){
                    if(isset($dayItem->user->email)){
                        Log::info("saved foods ".$dayItem->user->username."   package_id====>".$dayItem->user->package_id."  package_duration_id".$dayItem->user->package_duration_id);

                        //   Mail::to($dayItem->user->email)->send(new \App\Mail\AutoSaveItemForUser($dayItem->user,$validDay));

                    }
                }

            }else{

                Log::warning("not found  user for day ".$dayItem->date."   id=====>".$dayItem->id);

            }
        }
    }
    public function getValidDay()
    {
        $today         = date("Y-m-d");
        $date          = strtotime("+3 day", strtotime($today));
        $firstValidDay = date("Y-m-d",$date);
        return $firstValidDay;
    }
    public function selectWeek($startingDay,$dayName,$dayNumber){
        $now=time();
        $your_date = strtotime($startingDay);
        $daysWeek1=[1,2,3,4,5,6,7,15,16,17,18,19,20,21,29,30];
        $daysWeek2=[8,9,10,11,12,13,14,22,23,24,25,26,27,28];
        if(in_array($dayNumber,$daysWeek1)){
            return $dayName;
        }else{
            return $dayName." 2";
        }
    }
    public function getDayId($dayName){
        return  DB::table("days")->where('titleEn',$dayName)->select(['id'])->first();
    }

    public function selectPortion($userId,$mealId,$packageId)
    {
        $res= DB::table("portion_log")->where("package_id",$packageId)->where('user_id',$userId)->where('meal_id',$mealId)->select(['portion'])->first();
        return optional($res)->portion;

    }

}
