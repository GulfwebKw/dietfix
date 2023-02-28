<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 2:56 PM
 */

namespace App\Http\Controllers\Admin;


use App\Models\FutureRenewalPackage;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\User;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class FuturePackageRenewalController extends AdminController
{

    public function __construct()

    {

        // The Model To Work With

        $this->model = FutureRenewalPackage::class;

        $this->views = '';

        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'future_pkg_subs';



        // Human Name

        $this->humanName = 'Future Renewal Subscriptions';



        // Fields for this table



        $this->fields[] = array('title' => trans('main.User'), 'name' => 'username', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        
        $this->fields[] = array('title' => trans('main.PackageAr'), 'name' => 'titleAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageEn'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);


        // Grid Buttons
        $this->buts = [];//['edit'] = ['name'=>'Edit', 'icon'=>'print', 'color' => 'blue']; // grid and record buttons
        $this->gridButs['resume'] = array('name' =>'Resume','icon' => 'continue', 'color' => 'green', "JShndlr"=>"showResume");  // record buttons

        $this->routeParams["dataLoaderFunction"] = "";
        $this->customJS = "cpassets/js/membership_suspension.js?v=1";

        parent::__construct();
    }
	
	
    public function loadData()
    {
    $M = $this->model;
  	$this->items = $M::with('users','package','packageduration')->orderBy('starting_date','asc')->get();
    }
	
	
   

    public function index()
    {
        $M = $this->model;

        $renewDatas=$M::with('users','package','packageduration','payment')->orderBy('starting_date','asc')->paginate(1);
    
      return view( 'admin.user.FutureRenewalPackage',['_pageTitle'=>$this->humanName,'renewDatas'=>$renewDatas,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }
	
	//manual renew the package
	public function future_pkg_subs_approve(Request $request){
	
	if(empty($request->renew_id)){
	session()->flash('message','ID is missing');
    session()->flash('status','danger');
	return redirect()->back()->with(['status'=>'error','message'=>'ID is missing']);   
	}
	$item = FutureRenewalPackage::where('id',$request->renew_id)->first();
	if(empty($item->id)){
	session()->flash('message','Record not found');
    session()->flash('status','danger');
	return redirect()->back();   
	}
	
	///
	        $package=Package::find($item->package_id);
            $packageDurationId=PackageDurations::find($item->package_duration_id);
           
            if(isset($package) ){
                $user=User::find($item->user_id);
                if(isset($user)){
                    $user->package_id          = $package->id;
                    $user->package_duration_id = $item->package_duration_id;
					$user->membership_start    = date("Y-m-d",strtotime($item->starting_date));
					$user->membership_end      = date("Y-m-d",strtotime('+360 days '.$item->starting_date));
                    $user->save();
                }
				//update dates
				$firstDate = date("Y-m-d",strtotime($item->starting_date));
				$packageId = $package->id;
				$userId    = $item->user_id;
				$countDay  = $packageDurationId->count_day;
				self::updateDates($firstDate,$countDay,$userId,$packageId);
				
                DB::table("user_week_progress")->where('user_id',$item->user_id)->delete();
                $countWeek = ceil($packageDurationId->count_day / 7);
                for ($i = 1; $i <= $countWeek; $i++) {
                    DB::table('user_week_progress')->insert(['user_id' => $item->user_id, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                }
            }
             DB::table("renew_future_package")->where("id",$item->id)->delete();
	///
	session()->flash('message','Subscription is successfully approved');
    session()->flash('status','success');
	return redirect()->back();   
	}
	
	
	public static function updateDates($firstDate,$countDay,$userId,$packageId){
	        $arrayDay=[];
            array_push($arrayDay,$firstDate);
            for($j=1;$j<$countDay;$j++){
                $nextDay= date('Y-m-d',strtotime("+$j day", strtotime($firstDate)));
                array_push($arrayDay,$nextDay);
            }

            foreach ($arrayDay as $item) {
                DB::table('user_dates')->insert(['date'=>$item,'user_id'=>$userId,'package_id'=>$packageId]);
            }

	}


}
