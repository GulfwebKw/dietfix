<?php

namespace App\Console\Commands;

use App\Models\Clinic\Order;
use App\Models\Clinic\Item;
use App\Models\Clinic\Category;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

use App\Models\Clinic\UserDate;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Progress;

class CheckItemStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'CheckItemStatus:items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CheckItemStatus for items';

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
        
        $this->itemstatus();
        $this->updatephone();
        $this->getprogress();
    }
    
    
    public function getprogress(){
       // Log::info("run crone Job to check progress ");
        $res = UserDate::select('user_dates.user_id','users.id','users.package_duration_id','package_duration.id','package_duration.count_day')
                       ->join('users','users.id','=','user_dates.user_id')
                       ->join('package_duration','package_duration.id','=','users.package_duration_id')
                       ->whereNOTNULL('users.package_duration_id')
                       ->distinct()->get();
        foreach($res as $list){
           if(!empty($list->package_duration_id)){
            $progressweek    = Progress::where('user_id',$list->user_id)->get()->count();
            $packageDuration = PackageDurations::where('id',$list->package_duration_id)->first();
            if(empty($progressweek) && !empty($packageDuration->count_day)){
                $this->addprogress($list->user_id,$packageDuration->count_day);
                //echo $list->user_id; echo "<br>";
            }
           }
        }
    }

public function addprogress($userId,$countday){
    $countWeek = ceil($countday/7);
    for ($i = 1; $i <= $countWeek; $i++) {
        $progressweek    = new Progress;
        $progressweek->user_id = $userId;
        $progressweek->titleEn = ' Week ' . $i . ' Progress';
        $progressweek->titleAr = ' Week ' . $i . ' Progress';
        $progressweek->water      = 0;
        $progressweek->commitment = 0;
        $progressweek->exercise   = 0;
        $progressweek->save();
    }
}
	
	public function updatephone(){
        //Log::info("run crone Job to check user phone null ");
        $user = User::whereNull('phone')->orwhere('phone',NULL)->orwhere('phone','')->get();
        if(!empty($user) && count($user)>0){
            foreach($user as $list){
                $newuser = User::where('id',$list->id)->first();
                $newuser->phone = $list->mobile_number;
                $newuser->save();
            }
        }
        
        ///
        //
        $username = User::whereNull('username')->get();
        if(!empty($username) && count($username)>0){
            foreach($username as $list){
                $newuser = User::where('id',$list->id)->first();
                $newuser->username = $list->mobile_number;
                $newuser->save();
            }
        }
    }
    
    
   public function itemstatus(){
   //Log::info("run crone Job to check item status ");
   $itemLists = Item::where('active',1)->get();
   if(!empty($itemLists) && count($itemLists)>0){
   foreach($itemLists as $itemList){
   $this->updatecatName($itemList->category_id,$itemList->id);    
   $newitem = Item::find($itemList->id);
   if(!empty($itemList->protien) && $itemList->protien >=10){
   $newitem->is_high_protien=1;
   }else{
   $newitem->is_high_protien=0;
   }
   
   ///Log::info("seller- ".$this->itemorder($itemList->id));
   
   if(!empty($this->itemorder($itemList->id)) && $this->itemorder($itemList->id) >= 5000){
   $newitem->is_best_seller=1;
   }else{
   $newitem->is_best_seller=0;
   }
   
   if(!empty($itemList->created_at) && date('Y-m-d',strtotime($itemList->created_at))==date('Y-m-d') ){
   $newitem->is_new=1;
   }else{
   $newitem->is_new=0;
   }
   $newitem->save();
   }   
   }   
   }
 
 /*
  public function updatecatName($catid,$itemid){
  $catedetails = Category::find($catid);
  
  $mostorder   = Order::where('item_id',$itemid)->whereDate('updated_on', Carbon::today())->get()->count();
  if(!empty($catedetails->id)){
  $item = Item::find($itemid);
  $item->category_name_en = $catedetails->titleEn??'no name';
  $item->category_name_ar = $catedetails->titleAr??'no name';
  $item->most_order       = $mostorder;
  $item->save();
  Log::info('most-order = '.$mostorder.'-'.Carbon::today().'ID='.$item->id);
  }
  } 
  */
  
  public function updatecatName($catid,$itemid){
  $catedetails = Category::find($catid);
  $orderdate   = $this->getValidDay();
  $lastdates    = UserDate::where('date',$orderdate)->pluck('id')->toArray();
  if(!empty($lastdates)){
  $mostorder   = Order::where('item_id',$itemid)->whereIn('date_id', $lastdates)->get()->count();
  if(!empty($catedetails->id)){
  $item = Item::find($itemid);
  $item->category_name_en = $catedetails->titleEn??'no name';
  $item->category_name_ar = $catedetails->titleAr??'no name';
  $item->most_order       = $mostorder;
  $item->save();
  //Log::info('most-order = '.$mostorder.'-'.Carbon::today().'ID='.$item->id);
  }
  }
  //Log::info('date:'.json_encode($lastdates));
  } 

  public function getValidDay()
    {
        $today         = date("Y-m-d");
        $date          = strtotime("+3 day", strtotime($today));
        $firstValidDay = date("Y-m-d",$date);
        return $firstValidDay;
    }
    
  public function itemorder($item_id){
  return Order::where('item_id',$item_id)->get()->count();
  }
}
