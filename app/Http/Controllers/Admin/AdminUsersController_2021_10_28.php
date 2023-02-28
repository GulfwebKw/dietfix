<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/23/2019
 * Time: 3:03 PM
 */

namespace App\Http\Controllers\Admin;

use App\Models\App\UserWeekProgress;
use App\Models\Clinic\Day;
use App\Models\Clinic\Item;
use App\Models\Clinic\Order;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Payment;
use App\Models\Clinic\UserDate;
use App\Models\ReferralUser;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clinic\Area;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\Package;
use App\Models\Clinic\Province;
use App\Models\Country;
use App\Models\StandardMenu;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class AdminUsersController extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'users';

        // Human Name
        $this->humanName = 'الاعضاء';

        // $roles = Role::lists('roleName'.LANG, 'id');
        $this->provinces = Province::select(['title'.LANG, 'id'])->get();
        $this->areas = Area::select(['title'.LANG, 'id'])->get();
        $this->countries = Country::select(['title'.LANG, 'id'])->get();
        $this->standard_menus = StandardMenu::select(['title'.LANG, 'id'])->get();
        $this->clinics = Clinic::select(['title'.LANG, 'id'])->get();
        $this->packages = Package::select('title'.LANG, 'id');
        $this->doctors = User::where('role_id',2)->select(['username', 'id'])->get();
        $this->sex = ['Male' => trans('main.Male'),'Female' => trans('main.Female')];
        //$this->salt = ['No Salt - Local' => trans('main.No Salt - Local'),'Medium Salt - Local' => trans('main.Medium Salt - Local'),'No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt')];

        $this->salt = ['No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt')];

        $this->delivery = ['Morning' =>trans('main.Morning'),'After Noon' => trans('main.After Noon'),'Evening' => trans('main.Evening')];

        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.User Name'), 'name' => 'username','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Email Address'), 'name' => 'email','width' => 25,'type' => 'email', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone No.'), 'name' => 'phone','width' => 10, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Mobile'), 'name' => 'mobile_number','width' => 10, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Password'), 'name' => 'password' ,'type' => 'password', 'col' => 2);

        // $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea');

//            $this->fields[] = array('title' => trans('main.MainAddress'), 'name' => 'wrap0','type' => 'wrap','fields'=>[
//                    array('title' => trans('main.Province'), 'name' => 'province_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select', 'data' => $this->provinces, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//                    array('title' => trans('main.Country'), 'name' => 'country_id' , 'data' => $this->countries,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//                    array('title' => trans('main.Area'), 'name' => 'area_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select', 'data' => $this->areas, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//                    array('title' => trans('main.Avenue'), 'name' => 'avenue','width' => 15,'type' => 'text', 'col' => 2),
//                    array('title' => trans('main.Block'), 'name' => 'block','width' => 15,'type' => 'text', 'col' => 2),
//                    array('title' => trans('main.HouseNumber'), 'name' => 'house_number','width' => 15,'type' => 'text', 'col' => 2),
//                    array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea','col'=>1),
//           ]);

        $this->fields[] = array('title' => trans('main.Province'), 'name' => 'province_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select', 'data' => $this->provinces, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Country'), 'name' => 'country_id' , 'data' => $this->countries,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Area'), 'name' => 'area_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select', 'data' => $this->areas, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Avenue'), 'name' => 'avenue','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Block'), 'name' => 'block','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.street'), 'name' => 'street','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.HouseNumber'), 'name' => 'house_number','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea','col'=>1);


//        $this->fields[] = array('title' => trans('main.WorkAddress'), 'name' => 'wrap1','type' => 'wrap','fields'=>[
//
//             array('title' => trans('main.Province(Work)'), 'name' => 'province_work_id', 'searched' => true, 'width' => 15 ,'type' => 'select', 'data' => $this->provinces, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//             array('title' => trans('main.Country (Work)'), 'name' => 'country_work_id' , 'data' => $this->countries,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//             array('title' => trans('main.Area (Work)'), 'name' => 'area_work_id', 'searched' => true, 'width' => 15 ,'type' => 'select', 'data' => $this->areas, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG),
//             array('title' => trans('main.Avenue(Work)'), 'name' => 'avenue_work','width' => 15,'type' => 'text', 'col' => 2),
//             array('title' => trans('main.Block(Work)'), 'name' => 'block_work','width' => 15,'type' => 'text', 'col' => 2),
//             array('title' => trans('main.HouseNumber(Work)'), 'name' => 'house_number_work','width' => 15,'type' => 'text', 'col' => 2),
//             array('title' => trans('main.Address Work'), 'name' => 'address_work','type' => 'textarea','col'=>1),
//
//        ]);

        $this->fields[] = array('title' => trans('main.Province(Work)'), 'name' => 'province_work_id', 'searched' => true, 'width' => 15 ,'type' => 'select', 'data' => $this->provinces, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Country (Work)'), 'name' => 'country_work_id' , 'data' => $this->countries,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Area (Work)'), 'name' => 'area_work_id', 'searched' => true, 'width' => 15 ,'type' => 'select', 'data' => $this->areas, 'noChosen' =>true, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Avenue(Work)'), 'name' => 'avenue_work','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Block(Work)'), 'name' => 'block_work','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.street_work'), 'name' => 'street_work','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.HouseNumber(Work)'), 'name' => 'house_number_work','width' => 15,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Address Work'), 'name' => 'address_work','type' => 'textarea','col'=>1);


        $this->fields[] = array('title' => trans('main.Group'), 'name' => 'role_id','type' => 'hidden','value' => 1);


        $this->fields[] = array('title' => trans('main.BM'), 'name' => 'bm','type' => 'text', 'col' =>2);
        $this->fields[] = array('title' => trans('main.Clinic'), 'name' => 'clinic_id', 'grid' => true , 'data' => $this->clinics,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Doctor'), 'name' => 'doctor_id', 'data' => $this->doctors,'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'username');
        $this->fields[] = array('title' => trans('main.Sex'), 'name' => 'sex','type' => 'select', 'data' => $this->sex, 'col' => 2,'valOptions'=>'otherType');
        $this->fields[] = array('title' => trans('main.Salt'), 'name' => 'salt','type' => 'select', 'data' => $this->salt, 'value' => 'Medium Salt', 'col' => 2,'valOptions'=>'otherType');
        $this->fields[] = array('title' => trans('main.Delivery'), 'name' => 'delivery', 'width' => 10, 'type' => 'select', 'col' =>2, 'data'=> $this->delivery,'valOptions'=>'otherType');

        $this->fields[] = array('title' => trans('main.Height'), 'name' => 'height','type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Weight'), 'name' => 'weight','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Package'), 'name' => 'package_id','type' => 'select', 'data' => $this->packages, 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.BMI'), 'name' => 'bmi','type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Date'), 'name' => 'date_t','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Loss'), 'name' => 'loss','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Total_Loss'), 'name' => 'total_loss','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Water_Consumption'), 'name' => 'water_consumption','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.exercise'), 'name' => 'exercise','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.fat_analysis_test'), 'name' => 'fat_analysis_test','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.fat'), 'name' => 'fat','type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.muscle'), 'name' => 'muscle','type' => 'text', 'col' => 2);
//
//
////        $this->fields[] = array('title' => trans('main.Membership Start'), 'name' => 'membership_start','type' => 'date', 'col' => 2);
////        $this->fields[] = array('title' => trans('main.Membership End'), 'name' => 'membership_end','type' => 'date', 'col' => 2);
//
//
//        $this->fields[] = array('title' => trans('main.Lat'), 'name' => 'latitud','width' => 15,'type' => 'text', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Long'), 'name' => 'longitud','width' => 15,'type' => 'text', 'col' => 2);


        // $this->fields[] = array('title' => trans('main.Options'), 'name' => 'metas', 'type' => 'manyToOne', 'masterModel' => 'UserMeta');
        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Standard Menu'), 'name' => 'standard_menu_id', 'width' => 10, 'grid' => true, 'type' => 'select', 'col' => 2, 'data'=> $this->standard_menus,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG);
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active','width' => 3, 'grid' => true,'type' => 'switcher', 'col' => 2);
        $this->fields[] = array('title' => 'Priority In Ordering', 'name' => 'priority_in_ordering','width' => 3, 'grid' =>false,'type' => 'switcher', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Note'), 'name' => 'note','type' => 'textarea','col'=>1);
        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        // $this->buts['menu'] = array('name' =>'Menu','icon' => 'list', 'color' => 'blue');

        $this->gridButs = [];

        //$this->recordButs = [];
//        $this->gridButs['renew'] = array('name' =>'Renew/Add membership','icon' => 'print', 'color' => 'green', 'JShndlr'=>"showRenewPopup");  // record buttons

        $this->recordButs['menu'] = array('name' =>'Menu','icon' => 'list', 'color' => 'blue');
        $this->recordButs['membership'] = array('name' =>'Renew-Or-AddMembership','icon' => 'print', 'color' => 'green');
        $this->recordButs['progress'] = array('name' =>'progress','icon' => 'print', 'color' => 'green');
        $this->recordButs['orders'] = array('name' =>'Orders','icon' => 'print', 'color' => 'red');
        $this->recordButs['transactions'] = array('name' =>'Transactions','icon' => 'list', 'color' => 'blue');
        $this->recordButs['points'] = array('name' =>'Points','icon' => 'list', 'color' => 'blue');
        $this->recordButs['invoice'] = array('name' =>'Invoice','icon' => 'file-o', 'color' => 'blue');
        $this->recordButs['freeze'] = array('name' =>'freeze','icon' => 'lock', 'color' =>'red');
        $this->recordButs['unFreeze'] = array('name' =>'unFreeze','icon' => 'unlock', 'color' => 'green');

        $this->customJS = "";
        ///$this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        // The View Folder
        $this->views = 'users';

        // Deleteable
        $this->deletable = true;

        parent::__construct();
    }

    public function addDay(Request $request)
    {
        $countDay=intval($request->countDay);
        $userId=$request->userId;
        if($countDay>=1 && $userId>=1){
            $this->logicAddDay($userId, $countDay);
            return response()->json(['result'=>true]);

        }else{
            return response()->json(['result'=>false]);
        }



    }


    public function renewOrAddMemberShip($userId)
    {

        $today=date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=date("Y-m-d",$date);

        $remind_day= UserDate::where('user_id',$userId)->where('date','>=',$today)->count();



        $user=User::with(['package','packageDuration'])->whereId($userId)->first();
        $packages=Package::where('active',1)->get();
        $packageDuration=PackageDurations::whereHas('package')->where('active',1)->get();
        //  return view( 'admin.userGrid',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);
        return view('admin.user.renewOrAddMemberShip',['_pageTitle'=>$this->humanName,'items'=>null,'url'=>$this->url,'fields'=>[],'gridFields'=>[],'gridFieldsName'=>[],'buts'=>[],'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'packages'=>$packages,'packageDuration'=>$packageDuration,'user'=>$user,'remind_day'=>$remind_day]);
    }

    public function deleteUser($id)
    {

        $this->makeAdminLog(" delete user userId ===>$id",null,$id,'delete user');


        $user=   User::find($id);
        ReferralUser::where('referral_mobile_number',$user->mobile_number)->delete();

        $res = User::destroy($id);
        UserDate::where("user_id",$id)->delete();
        Order::where('user_id',$id)->delete();
        UserWeekProgress::where('user_id')->delete();
        return \redirect()->back();

    }

    public function postDeleteUser(Request $request)
    {
        $id=$request->id;
        $this->makeAdminLog(" delete user userId ===>$id",null,$id,'delete user');

        $user=   User::find($id);
        ReferralUser::where('referral_mobile_number',$user->mobile_number)->delete();
        $res = User::destroy($id);
        UserDate::where("user_id",$id)->delete();
        Order::where('user_id',$id)->delete();
        UserWeekProgress::where('user_id')->delete();
       return response()->json(['success'=>true]);
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

    public function getOrder($packageId,$dateId,$userId)
    {
        $userDate=UserDate::with(["package"])->find($dateId);
        $user=User::find($userId);
        $selectedItem=$this->getOrderUser($userId,$userDate->date);
        $validItems=$this->getMeals($user,$userDate->date);
        return View::make('admin.user.iframe')->with(['validItems'=>$validItems,'selectedItem'=>$selectedItem,'user'=>$user,'userDate'=>$userDate,'dateId'=>$userDate->id]);

    }

    public function getMeals($user,$date)
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

        $result=Package::with(['meals.categories.items.addons','meals'=>function($r){
            $r->where('meals.active',1);
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
        $dateId=$request->dateId;
        $items=$request->items;
        $userId=$request->user;




        $user=User::find($userId);
        $userDate=UserDate::find($dateId);
        $order=Order::where('date_id',$dateId)->delete();
        $day=date('l',strtotime($userDate->date));
        $dayy=Day::where('titleEn',$day)->first();

        if(isset($userDate->package_id)){
            $packId=$userDate->package_id;
        }else{
            $packId=$user->package_id;
        }



        if(isset($items)){


            $dd=$userDate->date;
            $this->makeAdminLog("select and save item for user  ===>$userId , and selected date  $dd",null,$user->id,'select food');



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

                $portion= $this->selectPortion($userId,$mealId,$packId);
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
            $userDate->save();
        }
        return response()->json(['result'=>true]);

    }

    public function getAutoSaveResult()
    {
        $today=   date("Y-m-d");
        $date1 = strtotime("+1 day", strtotime($today));
        $date2 = strtotime("+2 day", strtotime($today));
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=date("Y-m-d",$date);
        $firstValidDay2=date("Y-m-d",$date1);
        $firstValidDay3=date("Y-m-d",$date2);
        $res=UserDate::whereIn('date',[$today,$firstValidDay2,$firstValidDay3,$firstValidDay])->with(['user.package.meals','orders'])->orderBy('date','asc')->get();
        return \view('admin.autoSaveResult',['result'=>$res,'_pageTitle'=>$this->humanName,'items'=>[],'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);

    }
    private function getDayId($dayName){
        return  DB::table("days")->where('titleEn',$dayName)->select(['id'])->first();
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

//        $datediff = $now - $your_date;
//        $countDay= round($datediff / (60 * 60 * 24));
//        if($countDay<=7){
//            return $dayName;
//        }else{
//            $val=ceil($countDay/7);
//            if($val%2==0){
//               return $dayName." 2";
//            }
//            return $dayName;
//        }

    }
    public function getOrders($userId)
    {
        $user=User::with(['package','packageDuration'])->whereId($userId)->first();
        if(isset($user->membership_start)){
            $userDate= UserDate::where('user_id',$user->id)->where('freeze',0)->where('date','>=',$user->membership_start)->orderBy('date','asc')->get()->chunk(4);

        }else{
            $userDate= UserDate::where('user_id',$user->id)->where('freeze',0)->orderBy('date','asc')->get()->chunk(4);
        }
        $today=date('Y-m-d');
        // $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=$today;

        return view('admin.user.orders',['_pageTitle'=>$this->humanName,'items'=>null,'url'=>$this->url,'fields'=>[],'gridFields'=>[],'gridFieldsName'=>[],'buts'=>[],'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'userDate'=>$userDate,'user'=>$user,'firstValidDay'=>$firstValidDay]);

    }
    public function selectPortion($userId,$mealId,$packageId)
    {
        $res= DB::table("portion_log")->where("package_id",$packageId)->where('user_id',$userId)->where('meal_id',$mealId)->select(['portion'])->first();
        return optional($res)->portion;

    }
    public function cancelSingleDays($id,$date)
    {

        $lastDay= $userDate=UserDate::where('user_id',$id)->orderBy('date','desc')->first();
        $userDate=UserDate::where('date',$date)->where('user_id',$id)->first();
        $order=Order::where('date_id',$userDate->id)->delete();
        UserDate::where('date',$date)->where('user_id',$id)->delete();
        $date2=date("Y-m-d",strtotime("+1 day",strtotime($lastDay->date)));
        UserDate::create(['date'=>$date2,'user_id'=>$id]);
        $this->makeAdminLog("remove day $date for user   ===>$id , and create  new day   $date2",null,$id,'remove and create day');

        return response()->json(['success'=>true]);

    }

    public function cancelDays($userId)
    {
        $user=User::find($userId);
        if(isset($user)){
            $this->makeAdminLog(" remove all  day user  userId ===>$userId ",null,$userId,"remove all  day user ");

           // $userDate=UserDate::where('user_id',$userId)->where('date','>=',$user->membership_start)->select('id')->get()->toArray();
            $userDate=UserDate::where('user_id',$userId)->where('date','>=',date('Y-m-d'))->select('id')->get()->toArray();
            $user->membership_start=null;
            $user->save();
            Order::whereIn('date_id',$userDate)->where('user_id',$userId)->delete();
            UserDate::whereIn('id',$userDate)->where('user_id',$userId)->delete();
            return response()->json(['success'=>true]);

        }
    }

    public function cancelActiveDays($userId)
    {
        $user=User::find($userId);
        if(isset($user)){
            $this->makeAdminLog(" remove all active  day user  userId ===>$userId ");

            $today=date('Y-m-d');
            $date = strtotime("+3 day", strtotime($today));
            $firstValidDay=date("Y-m-d",$date);
            $userDate=UserDate::where('user_id',$userId)->where('date','>=',$firstValidDay)->where('date','>=',$user->membership_start)->select('id')->get()->toArray();
            Order::whereIn('date_id',$userDate)->where('user_id',$userId)->delete();
            UserDate::whereIn('id',$userDate)->where('user_id',$userId)->delete();
            return response()->json(['success'=>true]);

        }
    }

    public function getProgress($id)
    {
        $weeks= UserWeekProgress::where('user_id',$id)->orderBy('id','asc')->get()->chunk(3);
        return \view("admin.user.userWeekProgress",['weeks'=>$weeks,'_pageTitle'=>$this->humanName]);


    }
    public function saveRenewOrAddMemberShip(Request $request)
    {

        $user=User::find($request->user_id);
        $today=   date("Y-m-d");

        $firstDate=$today;
       
        $res= $this->handelAddOrNewMembership($request,$user);
        if(!$res){
            return Redirect::back()->withErrors(['starting_date'=>'Your package has not expired yet'])->withInput();
        }

        session()->flash('message','successfully rew new subscribe');
        session()->flash('status','success');
        return Redirect::back();

    }


    private function handelAddOrNewMembership($request,$user){



        try {
            $uId=$user->id;
            $this->makeAdminLog("Admin: call handelAddOrNewMembership for userId==>$uId ",null,$uId,"call handelAddOrNewMembership");


            $today=   date("Y-m-d");
            $firstDate=$today;
            if(isset($request->package_duration_id) ){
                $packageDuration=PackageDurations::find($request->package_duration_id);
            }
            if(isset($request->package_id)){
                $package=Package::find($request->package_id);
            }
            $forFuture= $this->forFuture($user->id);
            $this->makeAdminLog("Admin: in handelAddOrNewMembership forFuture==? $forFuture   userId==>$uId ",null,$uId,"handelAddOrNewMembership forFuture");



            $updateDate=false;

            if(isset($request->starting_date)){
                $updateDate=true;
                $firstDate=date("Y-m-d",strtotime($request->starting_date));
            }else{
                $lastDayReservation=UserDate::where('user_id',$user->id)->where('freeze',0)->orderBy('date','desc')->select(['date'])->first();
                if(isset($lastDayReservation)){

                    if($lastDayReservation->date <= date('Y-m-d')){
                        $updateDate=true;
                    }

                    $firstDate=strtotime("+1 day",strtotime($lastDayReservation->date));
                    $firstDate=date("Y-m-d",$firstDate);
                }
            }

            if(isset($packageDuration)){
                $countDay=$packageDuration->count_day;
            }else{
                $countDay=0;
            }



            if($user->package_id!=$request->package_id){
                $this->makeAdminLog("Admin: in handelAddOrNewMembership user package_id != request package_id   userId==>$uId ",null,$uId,"handelAddOrNewMembership");


                if($forFuture && $countDay>0){
                    return false;
                }else{
                    if(isset($packageDuration)){
                        $user->package_duration_id=$packageDuration->id;
                    }
                    if(isset($package)){
                        $user->package_id=$package->id;
                    }else{
                        $user->package_id=null;
                    }

                    $user->is_subscription=1;
                    $user->is_verify_mobile=1;
                    $end = new DateTime($firstDate);
                    $end->modify('+30 day');
                    $end= $end->format('Y-m-d');
                    $user->membership_end=$end;
                    if(!empty($updateDate) || empty($user->membership_start)){
                    $user->membership_start=$firstDate;
                    }
					
					
					
                    $user->save();


                    if(isset($request->attach_day)){
                        $this->makeAdminLog("Admin: in handelAddOrNewMembership select attach days option  count days ==>$countDay   userId==>$uId ",null,$uId," handelAddOrNewMembership");

                        $this->clearUserDay($user->id);


                        $arrayDay=[];
                        array_push($arrayDay,$firstDate);
                        if($countDay>0){
                            for($j=1;$j<$countDay;$j++){
                                $nextDay= date('Y-m-d',strtotime("+$j day", strtotime($firstDate)));
                                array_push($arrayDay,$nextDay);
                            }
                            foreach ($arrayDay as $item) {
                                \DB::table('user_dates')->insert([
                                    'date'=>$item,
                                    'user_id'=>$user->id,
                                    'package_id'=>$package->id,
                                    'update_status'=>'admin',
                                    'created_at'=>date("Y-m-d H:i:s"),
                                    'freeze'=>0,
                                    'isMealSelected'=>0
                                ]);
                            }
                            DB::table('renew_future')->insert(['user_id'=>$user->id,'package_id'=>$package->id,'package_duration_id'=>$packageDuration->id,'starting_date'=>$request->starting_date,'pay_type'=>'admin']);
                        }
                    }
                }
            }else{
                $this->makeAdminLog("Admin: in handelAddOrNewMembership user package_id == request package_id   userId==>$uId ",null,$uId,"handelAddOrNewMembership");

                if(isset($request->attach_day)){
                    $arrayDay=[];
                    array_push($arrayDay,$firstDate);

                    if($countDay>0){
                        for($j=1;$j<$countDay;$j++){
                            $nextDay= date('Y-m-d',strtotime("+$j day", strtotime($firstDate)));
                            array_push($arrayDay,$nextDay);
                        }


                        foreach ($arrayDay as $item) {
                            \DB::table('user_dates')->insert([
                                'date'=>$item,
                                'user_id'=>$user->id,
                                'package_id'=>$package->id,
                                'update_status'=>'admin',
                                'created_at'=>date("Y-m-d H:i:s"),
                                'freeze'=>0,
                                'isMealSelected'=>0
                            ]);
                        }


                        if($forFuture==0){
                            DB::table("user_week_progress")->where('user_id',$user->id)->delete();
                            $countWeek = ceil($countDay / 7);

                            $end = new DateTime($firstDate);
                            $end->modify('+60 day');
                            $end                    = $end->format('Y-m-d');
                            $user->membership_end   = $end;
                            $user->is_subscription  = 1;
                            $user->is_verify_mobile = 1;
							
                            $user->save();

                            for ($i = 1; $i <= $countWeek; $i++) {
                                \DB::table('user_week_progress')->insert(['user_id' => $user->id, 'titleEn' => ' Week ' . $i . ' Progress', 'titleAr' => 'Week ' . $i . ' Progress', 'water' => 0, 'commitment' => 0, 'exercise' => 0]);
                            }
                        }else{
                            if(isset($request->starting_date)){
                                DB::table('renew_future')->insert(['user_id'=>$user->id,'package_id'=>$package->id,'package_duration_id'=>$packageDuration->id,'starting_date'=>$request->starting_date,'pay_type'=>'admin']);
                            }
                        }

                    }
                }

                if(isset($packageDuration)){
                    $user->package_duration_id=$packageDuration->id;
                }else{
                    $user->package_duration_id=null;
                }
                $user->save();

            }
            return true;

        }catch (\Exception $e){
            return false;
        }


    }

    public function clearUserDay($userId)
    {
        $t=date("Y-m-d");
        $this->makeAdminLog(" remove  days  user from  $t   (function clearUserDay )   userId ===>$userId ",null,$userId," remove  days  user");

        $re= DB::table("user_dates")->where("date",'>=',$t)->where('user_id',$userId)->select(['id'])->get()->toArray();
        DB::table('orders')->whereIn('date_id',$re)->where('user_id',$userId)->delete();
        DB::table("user_dates")->where("date",'>=',date("Y-m-d"))->where('user_id',$userId)->delete();

    }

    private function forFuture($userId){
        $today=date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDayForEdit=date("Y-m-d", $date);

        $res=  \DB::table('user_dates')->where('user_id',$userId)->where('date','>=',$firstValidDayForEdit)->count();
        if($res>=1){
            return 1;
        }
        return 0;
    }

    public function store(Request $request)
    {


        // Filter Clinic On Save
        if (Input::get($this->_pk)) {

            $i=$this->_pk;



            $current_clinic = Clinic::find(Auth::user()->clinic_id);
            $modifing_user = User::find(Input::get($this->_pk));
            if(isset($modifing_user)){
                $this->makeAdminLog("update user  userId===> ",null,$modifing_user->id," update user");

            }

            // Filter Clinic On Grid
            if($current_clinic->can_see_others == 0 && Auth::user()->clinic_id != $modifing_user->clinic_id)
                return Redirect::to(ADMIN_FOLDER);

            $current_package = $modifing_user->package_id;
            session()->put('old_package',$current_package);
        }



        $many_relations = [];
        //var_dump($this->item);
        foreach ($this->fields as $field) {
            if ($field['type'] == 'many2many')
                $many_relations[] = $field;
            if (isset($field['notExist']))
                continue;
            $val = trim(Input::get($field['name']));


//try{
            if (!empty($val) && property_exists("User", $field["name"])) {
                $this->item->{$field['name']} = $val; // Assign Field
            } else if (isset($field['nullable'])) {
                $this->item->{$field['name']} = NULL; // Clear Field
            }
            else if(!property_exists("User", $field["name"]))
            {
                //dd($field["name"]);
                //dd($val);
            }
            else {
                $this->item->{$field['name']} = ''; // Clear Field
            }
            /*}
            catch(ErrorException $e)
            {
                //print("Caught the error: ".$e->getMessage().'	'.$val."<br />\r\n" );
                continue;
            }*/
            // Handle Password
            if($field['type'] == 'password' && !empty($val) && property_exists("User", $field["name"]))
                $this->item->{$field['name']} = Hash::make(Input::get($field['name'])); // Hash Password
            elseif ($field['type'] == 'password' && empty($val))
                unset($this->item->{$field['name']});

            // Remove Time Stamps
            if($field['type'] == 'timestampDisplay')
                unset($this->item->{$field['name']});
            // Remove Time Stamps
            if($field['type'] == 'datestampDisplay')
                unset($this->item->{$field['name']});

        }

        $user_id ;
        $package_id;
        if($request->id!=null) {
            $user_id = $request->id;
            $package_id = $request->package_id;
        }
        else
        {
            if(isset($this->item->id))
            {
                $user_id = $this->item->id;
                $package_id = $this->item->package_id;
            }


        }
        return  parent::store($request);


    }
    public function saveOther()
    {
        if (Session::has('old_package')) {
            $old_package = Session::get('old_package');
            if ($old_package != $this->item->package_id) {
                Session::forget('old_package');
                Order::where('user_id',$this->item->id)->delete();
            }
        }
        if (isset($this->item->weight) && isset($this->item->height) && is_numeric($this->item->weight) && is_numeric($this->item->height) ) {
            $this->item->bmi = intval($this->item->weight / (($this->item->height/100) * ($this->item->height/100)));
        }

        $d=\request()->get('starting_date');
        if(isset($d) && is_null($this->item->membership_start)){
            $this->item->membership_start=$d;
        }

        $isDemo=request()->get('is_demo');
        if(isset($isDemo)){
            if($isDemo==1){
                $this->item->role_id=20;
            }else{
                $this->item->role_id=1;
            }
        }
        $this->item->save();
        $res= $this->handelAddOrNewMembership(\request(),$this->item);
        $this->logicAddDay($this->item->id,intval(\request()->count_day),str_replace("/","-",\request()->starting_date));


    }
    
    
    public function is_number($var)
	{
		if ($var == (string) (float) $var) {
			return (bool) is_numeric($var);
		}
		if ($var >= 0 && is_string($var) && !is_float($var)) {
			return (bool) ctype_digit($var);
		}
		return (bool) is_numeric($var);
	}

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with(['role','country','clinic','province','area','standard_menu']);
        //var_dump($this->items);

        $current_clinic = Clinic::find(Auth::user()->clinic_id);

        // Filter Clinic On Grid
        if($current_clinic->can_see_others == 0)
            $this->items = $this->items->where('clinic_id',$current_clinic->id);
        //$this->items = DB::select(DB::raw("SELECT users.*, country.*, roles.*, clinics.*, provinces.*, areas.* FROM users INNER JOIN country ON users.country_id = country.id INNER JOIN roles ON users.role_id = roles.id INNER JOIN clinics ON users.clinic_id =clinics.id INNER JOIN provinces ON users.province_id = provinces.id INNER JOIN areas ON users.area_id = areas.id WHERE users.clinic_id = :clinicId", array("clinicId"=>$current_clinic->id)));

        //$this->items = $this->items->where('role_id',1);
        if($this->searchUser!=null){
            if($this->is_number($this->searchUser)==true) {
                $this->items=$this->items->where(function($sq){
				$sq->where('id','like',$this->searchUser)
				   ->orWhere('mobile_number',"like","%".$this->searchUser."%")
				   ->orWhere('phone',"like","%".$this->searchUser."%");
				});
            }else{
                $this->items=$this->items->where(function($sq){
				 $sq->where('mobile_number',"like","%".$this->searchUser."%")
					->orWhere('phone',"like","%".$this->searchUser."%")
					->orWhere("username","like","%".$this->searchUser."%")
					->orWhere("fullname","like","%".$this->searchUser."%");
				});
            }
        }
        //$this->items = DB::select(DB::raw("SELECT users.*, country.*, roles.*, clinics.*, provinces.*, areas.* FROM users INNER JOIN country ON users.country_id = country.id INNER JOIN roles ON users.role_id = roles.id INNER JOIN clinics ON users.clinic_id =clinics.id INNER JOIN provinces ON users.province_id = provinces.id INNER JOIN areas ON users.area_id = areas.id WHERE users.role_id = 1"));
        $this->items = $this->items->orderBy('id','desc')->paginate(50);
    }
    //edited by imtiaz
    public function loadDataActiveUser()
    {
        $M = $this->model;
        $this->items = $M::with(['role','country','clinic','province','area','standard_menu','package']);
        //var_dump($this->items);

        $current_clinic = Clinic::find(Auth::user()->clinic_id);

        // Filter Clinic On Grid
        if($current_clinic->can_see_others == 0)
            $this->items = $this->items->where('clinic_id',$current_clinic->id);
        //$this->items = DB::select(DB::raw("SELECT users.*, country.*, roles.*, clinics.*, provinces.*, areas.* FROM users INNER JOIN country ON users.country_id = country.id INNER JOIN roles ON users.role_id = roles.id INNER JOIN clinics ON users.clinic_id =clinics.id INNER JOIN provinces ON users.province_id = provinces.id INNER JOIN areas ON users.area_id = areas.id WHERE users.clinic_id = :clinicId", array("clinicId"=>$current_clinic->id)));

        $this->items = $this->items->where('role_id',1);

        $this->items = $this->items->with(['package','dates'=>function($r){$r->where('date','>=',date('Y-m-d'));}])->where('membership_end','>=',date('Y-m-d'));

        if($this->searchUser!=null){
            if($this->is_number($this->searchUser)==true) {
                $this->items=$this->items->where(function($sq){
				$sq->where('id','like',$this->searchUser)
				   ->orWhere('mobile_number',"like","%".$this->searchUser."%")
				   ->orWhere('phone',"like","%".$this->searchUser."%");
				});
            }else{
                $this->items=$this->items->where(function($sq){
				 $sq->where('mobile_number',"like","%".$this->searchUser."%")
					->orWhere('phone',"like","%".$this->searchUser."%")
					->orWhere("username","like","%".$this->searchUser."%")
					->orWhere("fullname","like","%".$this->searchUser."%");
				});
            }
        }


        //$this->items = DB::select(DB::raw("SELECT users.*, country.*, roles.*, clinics.*, provinces.*, areas.* FROM users INNER JOIN country ON users.country_id = country.id INNER JOIN roles ON users.role_id = roles.id INNER JOIN clinics ON users.clinic_id =clinics.id INNER JOIN provinces ON users.province_id = provinces.id INNER JOIN areas ON users.area_id = areas.id WHERE users.role_id = 1"));
        $this->items = $this->items->orderBy('id','asc')->paginate(50);
    }



    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {

            // $newItems[$k]->role_id = $item->role->{'roleName'.LANG};
            $newItems[$k]->area_id = optional($item->area)->{'title'.LANG};
            //$newItems[$k]->area_id = DB::select(DB::raw('SELECT title'.LANG' FROM users INNER JOIN areas ON users.area_id  = areas.id WHERE users.id = :userId', array("userId"=>$item["users.id"])));
            //$newItems[$k]->area_id = $item["area"]["title".LANG];
            $newItems[$k]->province_id = optional($item->province)->{'title'.LANG};
            //$newItems[$k]->province_id =  DB::select(DB::raw('SELECT title'.LANG' FROM users INNER JOIN provinces ON users.province_id  = provinces.id WHERE users.id = :userId', array("userId"=>$item["users.id"])));
            //$newItems[$k]->province_id = $item["province"]["title". LANG];
            $newItems[$k]->country_id = optional($item->country)->{'title'.LANG};
            //$newItems[$k]->country_id =   DB::select(DB::raw('SELECT title'.LANG' FROM users INNER JOIN country ON users.country_id  = country.id WHERE users.id = :userId', array("userId"=>$item["users.id"])));
            //$newItems[$k]->country_id = $item["country"]["title".LANG];
            $newItems[$k]->clinic_id = optional($item->clinic)->{'title'.LANG};
            $newItems[$k]->standard_menu_id = isset(optional($item->standard_menu)->{'title'.LANG}) ? $item->standard_menu->{'title'.LANG}:"";
            //$newItems[$k]->clinic_id =  DB::select(DB::raw('SELECT title'.LANG'  FROM users INNER JOIN clinics ON users.clinic_id  = clinics.id WHERE users.id = :userId', array("userId"=>$item["users.id"])));
            //$newItems[$k]->clinic_id = $item["clinic"]["title".LANG];
        }
        return $newItems;
    }
    public function index()
    {

        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();


        if(isset($this->isGrid))
        {
            if($this->isGrid == false)
            {

                return View::make($this->views.'.'.$this->indexViewFile);
            }
        }
        return view( 'admin.userGrid',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }

    public function indexActive()
    {



        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();


        if(isset($this->isGrid))
        {
            if($this->isGrid == false)
            {

                return View::make($this->views.'.'.$this->indexViewFile);
            }
        }
        return view( 'admin.activeUsers',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }

    public function getAjax(Request $request)
    {
        $this->searchUser=$request->search['value'];


        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();

        $result=[];
        $record=[];
        foreach ($this->items as $item) {
            $record = array();
            $record['checkboxCol'] = '<input type="checkbox" class="checkboxes" value="'. $item->{$this->_pk} .'"/>';
            $record[$this->_pk] = $item->{$this->_pk};

            foreach ($this->fields as $field) {
                if($field['type'] == 'datestampDisplay' || $field['type'] == 'timestampDisplay') {
                    $type = $field['type'];
                    $temp = json_decode(json_encode($item->{$field['name']}));
                    if(isset($temp->date))
                        ${$field['name']} = (string) json_decode(json_encode($item->{$field['name']}))->date;
                    else
                        ${$field['name']} = (string) $temp;
                    if($type == 'datestampDisplay')
                        ${$field['name']} = date('Y-m-d',strtotime(${$field['name']}));
                    else if($type == 'timestampDisplay')
                        ${$field['name']} = date('Y-m-d H:i',strtotime(${$field['name']}));
                    $record[$field['name']] = ${$field['name']};
                } else if ($field['type'] == 'switcher')
                    $record[$field['name']] = ($item->{$field['name']} == 1) ? trans('main.Yes') :  trans('main.No');
                else
                    $record[$field['name']] = $item->{$field['name']};
            }
            $butsCol = '';

            foreach ($this->buts as $b) {
                if ($b['name'] != 'Add' && $b['name']!='Delete') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';

                }
                if($b['name']=='Delete'){
                    $i=$item->{$this->_pk};
                    $butsCol .= '<span  onclick="deleteUser('.$i.')"  class="nwrap btn btn-xs '. $b['color'] .' btn-block " ><i  class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</span>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="nwrap btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }

            }

            /*
            B MH
            */

            $mainGridButsCol = '';
            foreach ($this->gridButs as $b) {
                $butsCol .= '<button onclick="'.$b['JShndlr'].'('.$item->{$this->_pk}.')" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                $butsCol .= '">'. trans('main.'.$b['name']) .'</button>';
            }

            $recordButsCol = '';
            foreach ($this->recordButs as $b) {
                if ($b['name'] != 'Add' && $b['name'] != 'Menu' && $b['name'] != 'Invoice') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<a href="./orders/view/'.$item->{$this->_pk}.'" class="nwrap btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>';
                }
                if ($b['name'] == 'Invoice') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="nwrap btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }
            }
            /*
            E MH
            */
            $record['recordButsCol'] = $recordButsCol; // MH
            $record['mainGridButsCol'] = $mainGridButsCol; //MH
            $record['butsCol'] = $butsCol;

            $result[]=$record;
        }

        return response()->json(['data'=>$result]);
    }

    //edited by imtiaz
    public function getAjaxActiveUsers(Request $request)
    {
        $this->searchUser=$request->search['value'];


        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadDataActiveUser();

        $this->items = $this->filterGrid();

        $result=[];
        $record=[];
        foreach ($this->items as $item) {
            $record = array();
            $record['checkboxCol'] = '<input type="checkbox" class="checkboxes" value="'. $item->{$this->_pk} .'"/>';
            $record[$this->_pk] = $item->{$this->_pk};

            foreach ($this->fields as $field) {
                if($field['type'] == 'datestampDisplay' || $field['type'] == 'timestampDisplay') {
                    $type = $field['type'];
                    $temp = json_decode(json_encode($item->{$field['name']}));
                    if(isset($temp->date))
                        ${$field['name']} = (string) json_decode(json_encode($item->{$field['name']}))->date;
                    else
                        ${$field['name']} = (string) $temp;
                    if($type == 'datestampDisplay')
                        ${$field['name']} = date('Y-m-d',strtotime(${$field['name']}));
                    else if($type == 'timestampDisplay')
                        ${$field['name']} = date('Y-m-d H:i',strtotime(${$field['name']}));
                    $record[$field['name']] = ${$field['name']};
                } else if ($field['type'] == 'switcher')
                    $record[$field['name']] = ($item->{$field['name']} == 1) ? trans('main.Yes') :  trans('main.No');
                else
                    $record[$field['name']] = $item->{$field['name']};
            }
            $butsCol = '';

            foreach ($this->buts as $b) {
                if ($b['name'] != 'Add') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';

                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="nwrap btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }

            }

            /*
            B MH
            */

            $mainGridButsCol = '';
            foreach ($this->gridButs as $b) {
                $butsCol .= '<button onclick="'.$b['JShndlr'].'('.$item->{$this->_pk}.')" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                $butsCol .= '">'. trans('main.'.$b['name']) .'</button>';
            }

            $recordButsCol = '';
            foreach ($this->recordButs as $b) {
                if ($b['name'] != 'Add' && $b['name'] != 'Menu' && $b['name'] != 'Invoice') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';

                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<a href="./orders/view/'.$item->{$this->_pk}.'" class="nwrap btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>';
                }
                if ($b['name'] == 'Invoice') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="nwrap btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }
            }
            /*
            E MH
            */
            $record['recordButsCol'] = $recordButsCol; // MH
            $record['mainGridButsCol'] = $mainGridButsCol; //MH
            $record['butsCol'] = $butsCol;

            $result[]=$record;
        }

        return response()->json(['data'=>$result]);
    }

    public function create()
    {
        $packageDuration=PackageDurations::whereHas('package')->where('active',1)->get();
        $this->grabOtherTables();
        return View::make('admin.user.add')
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'standard_menus' , $this->standard_menus)
            ->with( 'clinics' , $this->clinics)
            ->with( 'areas' , $this->areas)
            ->with( 'doctors' , $this->doctors)
            ->with('countries',$this->countries)
            ->with('fields',[])
            ->with('sex',$this->sex)
            ->with('salt',$this->salt)
            ->with('delivery',$this->delivery)
            ->with('packages',$this->packages->get())
            ->with('packageDuration',$packageDuration)
            ->with( 'provinces',$this->provinces );



    }
    public function edit($id)
    {

        // $id = Request::segment(4);


        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;

        $this->item = $M::where($this->_pk, $id)->first();
        $packageDuration=PackageDurations::whereHas('package')->where('active',1)->get();
        $this->grabOtherTables();
        return View::make('admin.user.edit')
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'standard_menus' , $this->standard_menus)
            ->with( 'clinics' , $this->clinics)
            ->with( 'areas' , $this->areas)
            ->with( 'doctors' , $this->doctors)
            ->with('countries',$this->countries)
            ->with('fields',[])
            ->with('sex',$this->sex)
            ->with('salt',$this->salt)
            ->with('delivery',$this->delivery)
            ->with('packages',$this->packages->get())
            ->with('packageDuration',$packageDuration)
            ->with( 'provinces',$this->provinces );
    }
    public function getTransactionsUser($id)
    {

        $payment=Payment::where('user_id',$id)->orderBy('id','desc')->paginate(20);
        return view( 'admin.userTransaction',['_pageTitle'=>$this->humanName,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'payments'=>$payment]);

    }
    public function getPointList($id)
    {
        $points=DB::table("credit_user")->orderBy('id','desc')->where('user_id',$id)->paginate(20);
        return view( 'admin.userPoints',['_pageTitle'=>$this->humanName,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'points'=>$points]);

    }

    public function getActiveUser()
    {
        $user= User::with(['package','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->orderBy('created_at','desc')->where('membership_end','>=',date('Y-m-d'))->get();
        return view( 'admin.activeUsers',['_pageTitle'=>$this->humanName,'users'=>$user]);


    }

    public function getDemoUser()
    {
        $user= User::with(['package','dates'])->where('role_id',20)->orderBy('created_at','desc')->where('membership_end','>=',date('Y-m-d'))->get();
        return view( 'admin.activeUsers',['_pageTitle'=>$this->humanName,'users'=>$user]);

    }

    public function getFreezeView($id)
    {
        $user=User::find($id);
        $lastDay=UserDate::where('date','>=',date('Y-m-d'))->where('user_id',$id)->where('freeze',0)->orderBy('date','desc')->first();
        $countRemDay=UserDate::orderBy('id','desc')->where('user_id',$user->id)->where('date','>=',date('Y-m-d'))->where('freeze',0)->count();
        return view("admin.user.freezeDay",['user'=>$user,'_pageTitle'=>"Freeze Days",'url'=>$this->url,'fields'=>[],'lastDay'=>$lastDay,'countRemDay'=>$countRemDay]);

    }

    public function getUnFreezeView($id)
    {
        $user=User::find($id);
        $countExistFreeze=UserDate::where('user_id',$user->id)->where('date','>=',$user->membership_start)->where('freeze',1)->count();
        return view("admin.user.unFreezeDay",['user'=>$user,'_pageTitle'=>"UnFreeze Days",'url'=>$this->url,'fields'=>[],'countFreeze'=>$countExistFreeze]);
    }


    public function freezeDays(Request $request)
    {
        $starting_date=$request->starting_date;
        $userId=$request->user_id;
        $user=User::find($userId);
        $today=   date("Y-m-d");
        $firstDate=$today;
        $validator = Validator::make(Input::all(),['starting_date'=>"required|date|date_format:Y-m-d|after_or_equal:$firstDate"]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }
        $count= UserDate::where('user_id',$user->id)->where('date','>=',$starting_date)->count();
        if($count<=0){
            session()->flash('message',"Not Found Days");
            session()->flash('status','danger');
            return Redirect::back();
        }

        $id=$user->id;
        $this->makeAdminLog("freeze user days from $starting_date  userId===>$id ",null,$id,"freeze user days");

        $res= UserDate::where('user_id',$user->id)->where('date','>=',$starting_date)->update(['freeze'=>1]);
        $daysId= UserDate::where('user_id',$user->id)->where('date','>=',$starting_date)->where('freeze',1)->pluck('id');
        Order::whereIn('date_id',$daysId)->where('user_id',$user->id)->update(['freeze'=>1]);
        if($res){
            session()->flash('message','successfully freeze days ');
            session()->flash('status','success');
            return Redirect::back();
        }


        session()->flash('message','Woops! ');
        session()->flash('status','danger');
        return Redirect::back();



    }

    public function unFreezeDays(Request $request)
    {
        $starting_date = $request->starting_date;
        $userId        = $request->user_id;
        $today         = date("Y-m-d");
        $firstDate     = $today;
		
        $validator     = Validator::make(Input::all(),['starting_date'=>"required|date|date_format:Y-m-d|after_or_equal:$firstDate"]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }

        $firstValidDay=$request->starting_date;
        $user=User::whereId($userId)->first();
		
		//check resume date should not override the available date
		
			$resumeDay = UserDate::where('user_id',$user->id)->where('freeze',1)->orderBy('date','asc')->first();
            if(!empty($resumeDay->id)){
			$date1  = Carbon::createFromFormat('Y-m-d', $resumeDay->date);
            $date2  = Carbon::createFromFormat('Y-m-d', $firstValidDay);
            $reDate = $date1->lte($date2);
			if(empty($reDate)){
			session()->flash('message','Invalid resume date');
            session()->flash('status','danger');
            return Redirect::back();
			}
			}
			

        $countExistFreeze=UserDate::where('user_id',$userId)->where('date','>=',$user->membership_start)->where('freeze',1)->get();

        $count=$countExistFreeze->count();
		
        if($count<=0){
            session()->flash('message',trans('main.Not_Found_Freeze_Day'));
            session()->flash('status','danger');
            return Redirect::back();
        }

        $idS=$countExistFreeze->pluck('id')->toArray();
        UserDate::where('user_id',$userId)->where('date','>=',$user->membership_start)->where('freeze',1)->delete();
        Order::where('user_id',$userId)->whereIn('date_id',$idS)->delete();

        $this->makeAdminLog(" unfreeze days  and attach days  from $firstValidDay  for user ===>$userId",null,$userId,"unfreeze days  and attach days");

        /*for ($i=0;$i<$count;$i++){

                $c=$i;
                $newDay= date("Y-m-d",strtotime("+$c day", strtotime($firstValidDay)));

                $existDay=UserDate::where('user_id',$userId)->where('date',$newDay)->first();
                if(isset($existDay)){
                    $existDay->freeze=0;
                    $existDay->isMealSelected=0;
                    $existDay->save();
                }else{
                    UserDate::create(['user_id'=>$userId,'date'=>$newDay]);
                }
        }*/
		
		for ($i=0;$i<$count;$i++){
                    $c=$i;
                    $newDay= date("Y-m-d",strtotime("+$c day", strtotime($firstValidDay)));
                    $existDay=UserDate::where('user_id',$user->id)->where('date',$newDay)->first();
                    if(!empty($existDay->id)){
					
                        $existDay->freeze=0;
                        $existDay->isMealSelected=0;
						$existDay->package_id=$user->package_id;
                        $existDay->save();
						
                        
						$this->makeAdminLog("update day status and  cancel freeze after cancelFreeze day   date==>".$existDay->date."  userId==>".$user->id,null,$userId,"unfreeze days  and attach days");

                    }else{
                        $uDate = new UserDate;
						$uDate->user_id    = $user->id;
						$uDate->date       = $newDay;
						$uDate->package_id = $user->package_id;
						$uDate->save();
						
						$this->makeAdminLog("create new day after cancelFreeze day   date==>".$newDay." userId==>".$user->id,null,$userId,"unfreeze days  and attach days");

                    }
            }
		
		
        session()->flash('message','successfully UnFreeze days ');
        session()->flash('status','success');
        return Redirect::back();
    }

    /**
     * @param $userId
     * @param int $countDay
     */
    private function logicAddDay($userId, int $countDay,$dateT=null): void
    {
        $today = date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay = date("Y-m-d", $date);
        $last = UserDate::where('user_id', $userId)->orderBy('date', 'desc')->first();

        if (optional($last)->date >= $firstValidDay) {
            $start = $last->date;
        } else {
            $start = date("Y-m-d", strtotime("+2 day", strtotime($today)));
        }
        if($dateT!=null){
            $start=$dateT;
        }

         $this->makeAdminLog(" attach $countDay days for user ===>$userId",null,$userId,"attach days");


        for ($i = 0; $i < $countDay; $i++) {
            $d = date("Y-m-d", strtotime("+$i day", strtotime($start)));
            $exist=UserDate::where('date',$d)->where('user_id',$userId)->count();
            if($exist==0){
                UserDate::create(['user_id' => $userId, 'date' => $d,'created_at'=>date("Y-m-d H:i:s"),'update_status'=>'admin']);
            }

        }
    }


}
