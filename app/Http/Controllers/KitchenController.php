<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Day;
use App\Models\Clinic\Order;
use App\Models\Clinic\UserDate;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class KitchenController extends MainController
{

    public function duplicated()

    {


        //   $results = DB::table('orders')->select(['user_id','day_id','date_id','meal_id',DB::row('count(id) as cid')])->groupBy(['user_id', 'date_id', 'meal_id'])->having('*', '>', 0)->get();




//        $results = DB::select('select user_id, day_id,date_id,meal_id, count(id) as cid
//								from orders
//								group by user_id, day_id, meal_id
//
//								HAVING count(*) > 1'
//
//        );

        //  dd($results);


//        $all_orders = [];
//
//        foreach ($results as $row) {
//
//            $limit = $row->cid - 1;
//
//            $orders = Order::where('user_id',$row->user_id)
//                ->where('day_id',$row->day_id)
//                ->where('meal_id',$row->meal_id)
//                ->take($limit)
//                ->pluck('id')
//                ->get()
//                ->toArray();
//
//
//            $all_orders = array_merge($all_orders,$orders);
//        }
//        if(count($all_orders) > 0) {
//            Order::destroy($all_orders);
//
//        }

    }
    public function getPortioning()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','portioning')

            ->with('isAuthPrepSummary', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Portioning'));

    }
    public function getDelivery()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        //$isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','delivery')

            //			->with('isAuthPrepSummary', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Delivery'));

    }
    public function postGetDelivery()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        if (!$orders->isEmpty()) {

            $users = [];

            foreach ($orders as $order) {

                $users[$order->user_id]['user'] = $order->user;

                $users[$order->user_id]['orders'][] = $order;

            }

            $orders->users = $users;

        }



        return View::make('kitchen.delivery')

            ->with('orders',$orders)

            ->with('type','delivery')

            ->withTitle(trans('main.Delivery'));

    }



    public function getGetDelivery()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        if (!$orders->isEmpty()) {

            $users = [];

            foreach ($orders as $order) {

                $users[$order->user->province_id][$order->user->area_id][$order->user->id] = $order->user;

            }

            $orders->users = $users;

        }



        $weekEndAddress=false;
        $nameOfDay = date('l', strtotime(\request()->date));
        if($nameOfDay=="Saturday" || $nameOfDay=="Friday"){
            $weekEndAddress=true;
        }


        return View::make('kitchen.delivery')
            ->with('orders',$orders)
            ->with('type','delivery')
            ->with('weekEndAddress',$weekEndAddress)
            ->withTitle(trans('main.Delivery'));

    }



    public function postGetPortioning()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        if (!$orders->isEmpty()) {

            $meals = [];

            foreach ($orders as $order) {

                $meals[$order->meal_id]['meal'] = $order->meal;

                $meals[$order->meal_id]['orders'][] = $order;

            }

            $orders->meals = $meals;

        }



        // dd($orders);



        return View::make('kitchen.portioning')

            ->with('orders',$orders)

            ->with('type','portioning')

            ->withTitle(trans('main.Portioning'));

    }



    public function getGetPortioning()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }


        $orders = $this->get_current_orders();


        if (!$orders->isEmpty()) {

            $meals = [];

            foreach ($orders as $order) {
                $meals[$order->meal_id]['meal'] = $order->meal;
                $meals[$order->meal_id]['orders'][] = $order;
            }
            $orders->meals = $meals;
        }



        // dd($orders);



        return View::make('kitchen.portioning')

            ->with('orders',$orders)

            ->with('type','portioning')

            ->withTitle(trans('main.Portioning'));

    }

    public function getPreparation()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','preparation')

            ->with('isAuthPrepSummary', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Preparation'));

    }



    public function postGetPreparation()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();

        $meals = [];

        if(!$orders->isEmpty()) {

            $meals = [];

            foreach ($orders as $order) {

                $meals[$order->meal_id]['meal'] = $order->meal->{'title'.LANG};



                $key = $order->item_id.'_'.$order->portion_id.'_'.$order->user->salt;

                foreach ($order->addons as $addon) {

                    $key .= '_'.$addon->id;

                }



                $meals[$order->meal_id]['orders'][$key]['title'] = $order->category->{'title'.LANG} . ' ' .$order->item->{'title'.LANG};

                $meals[$order->meal_id]['orders'][$key]['portion'] = ($order->portion) ? $order->portion->{'title'.LANG} : false;

                $addons = '';

                foreach ($order->addons as $addon) {

                    $addons .= ' ' . $addon->{'title'.LANG};

                }

                $meals[$order->meal_id]['orders'][$key]['addons'] = $addons;

                $meals[$order->meal_id]['orders'][$key]['salt'] = $order->user->salt;

                @$meals[$order->meal_id]['orders'][$key]['qty']++;

            }

        }

        $isKitchenUser = $this->notKitchen();

        return View::make('kitchen.preparation')

            ->with('orders',$meals)

            ->with('type','preparation')

            ->with('isKitchenUser', $isKitchenUser)

            ->withTitle(trans('main.Preparation'));

    }

    public function getGetPreparation()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();

        $meals = [];

        if(!$orders->isEmpty()) {



            foreach ($orders as $order) {

                $meals[$order->meal_id]['meal'] = $order->meal->{'title'.LANG};



                $key = $order->item_id.'_'.$order->portion_id.'_'.$order->user->salt;

                foreach ($order->addons as $addon) {

                    $key .= '_'.$addon->id;

                }



                $meals[$order->meal_id]['orders'][$key]['title'] = $order->category->{'title'.LANG} . ' ' .$order->item->{'title'.LANG};

                $meals[$order->meal_id]['orders'][$key]['portion'] = ($order->portion) ? $order->portion->{'title'.LANG} : false;

                $addons = '';

                foreach ($order->addons as $addon) {

                    $addons .= ' ' . $addon->{'title'.LANG};

                }

                $meals[$order->meal_id]['orders'][$key]['addons'] = $addons;

                $meals[$order->meal_id]['orders'][$key]['salt'] = $order->user->salt;

                @$meals[$order->meal_id]['orders'][$key]['qty']++;

            }

        }



        return View::make('kitchen.preparation')

            ->with('orders',$meals)

            ->with('type','preparation')

            ->withTitle(trans('main.Preparation'));

    }



    public function getLabeling()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','labeling')
            ->with('isAuthPrepSummary', $isPrepSummaryAuthorized)
            ->withTitle(trans('main.Labeling'));

    }
    public function getLabeling2()
    {
        if ($this->notKitchen()) {
            return $this->dontAllow();
        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')
            ->with('type','labeling2')
            ->with('isAuthPrepSummary', $isPrepSummaryAuthorized)
            ->withTitle(trans('main.Labeling'));
    }

    public function getCities()
    {
        if ($this->notKitchen()) {
            return $this->dontAllow();
        }
        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')
            ->with('type','countWithCities')
            ->with('isAuthPrepSummary', $isPrepSummaryAuthorized)
            ->withTitle(trans('main.Labeling'));

    }

    public function getCountWithCities()
    {
        if ($this->notKitchen()) {
            return $this->dontAllow();
        }
       $result=[];
        $orders = $this->get_current_orders();
        foreach ($orders as $order) {
            if(array_key_exists(optional($order->user->area)->titleEn,$result)){
                $count=$result[optional($order->user->area)->titleEn];
                $result[optional($order->user->area)->titleEn]=$count+1;
            }else{
                $result[optional($order->user->area)->titleEn]=1;
            }
        }
        $orders=$result;
        return View::make('kitchen.areaReport')
            ->with('orders',$orders)
            ->withTitle(trans('Area Report'));
    }

    public function getGetLabeling2()
    {
        if ($this->notKitchen()) {
            return $this->dontAllow();
        }
        $orders = $this->get_current_orders();
        $data['orders'] = $orders;

        $pdf=\PDF2::loadView('kitchen.temp',array("data"=>$data));
        $pdf->setPaper(array(0,0,114,172), 'landscape');
        return $pdf->stream('test_pdf.pdf');

        //        $pdf->setPaper(array(0,0,283,378), 'landscape');

    }

    public function postGetLabeling()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        // if (!$orders->isEmpty()) {

        // 	$users = [];

        // 	foreach ($orders as $order) {

        // 		$users[$order->user_id]['user'] = $order->user;

        // 		$users[$order->user_id]['orders'][] = $order;

        // 	}

        // 	$orders->users = $users;

        // }

        $data['orders'] = $orders;



        $pdf = PDF::loadView('kitchen.labeling', $data);

        return $pdf->download('labeling-' . Input::get('date') . '.pdf');



        return View::make('kitchen.labeling')

            ->with('orders',$orders)

            ->with('type','labeling')

            ->withTitle(trans('main.Labeling'));

    }



    public function getGetLabeling()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        // if (!$orders->isEmpty()) {

        // 	$users = [];

        // 	foreach ($orders as $order) {

        // 		$users[$order->user_id]['user'] = $order->user;

        // 		$users[$order->user_id]['orders'][] = $order;

        // 	}

        // 	$orders->users = $users;

        // }



        $data['orders'] = $orders;



        // $pdf = PDF::loadView('kitchen.labeling', $data);

        // return $pdf->download('labeling-' . Input::get('date') . '.pdf');



        PDF::SetTitle('Labeling');

        PDF::setCellPaddings(1, 1, 1, 1);

        PDF::setCellMargins(0, 0, 0, 0);

        PDF::SetFillColor(255, 255, 255);

        PDF::SetFont('helvetica', '', 8);

        PDF::SetMargins(7,9,true);

        PDF::SetAutoPageBreak(TRUE,0);

        ///PDF::SetFooterMargin(2);
       //  PDF::SetFont('cid0jp','B', 8);

        PDF::AddPage();

        $i = 0;

        $ln = 1;

        $w = 48;

        $h = 25.1;

        foreach ($data['orders'] as $order) {

            $txt  = '<br>';
            //if(strlen($order->category->{'title'.LANG} . ' ' . $order->item->{'title'.LANG})<=29){
            //$txt .= '<br>';
            //}
            $txt  .=  $order->category->{'title'.LANG} . ' ' . $order->item->{'title'.LANG};
            $txt .= '<br>';
            $txt .= ($order->portion) ? $order->portion->{'title'.LANG} : '';
            $txt .= ' ' . Input::get('date');
            $txt .= '<br>';
            $txt .= $order->user->salt;
            $txt .= '<br>';
            $txt .= ' ID:'.$order->user->id;
            $txt .= '<br>';

            if (!$order->addons->isEmpty()) {

                foreach ($order->addons as $addon) {

                    $txt .= $addon->{'title'.LANG}.',';

                }

            }



            $i++;

            $ln = ($i % 4 != 0) ? 0 : 1;

            // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

            // PDF::writeHTMLCell ($w, $h, '','', $txt, 'LTRB',$ln, 0, $ln, '', '', false, 0, true, true, 0, 'M', true);

            // MultiCell($w,$h,$txt,$border = 0,$align = 'J',$fill = false,$ln = 1,$x = '',$y = '',$reseth = true,$stretch = 0,$ishtml = false,$autopadding = true,$maxh = 0,$valign = 'M',$fitcell = false )

            PDF::MultiCell ($w, $h, $txt, 'LTRB', 'C', false, $ln, '', '', true, 0, true, true, $h, 'M', true);



            if ($i % 44 == 0) {

                PDF::AddPage();

            }

        }





        PDF::Output('labeling-' . Input::get('date') . '.pdf');



        return View::make('kitchen.labeling')

            ->with('orders',$orders)

            ->with('type','labeling')

            ->withTitle(trans('main.Labeling'));

    }



    /* Edited by Basila */



    public function getPkreport()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','pkreport')

            ->with('isKitchenUser', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Package Report'));

    }

    public function getGetPkreport()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_package();
        $repForPack=[];
        foreach ($orders as $order) {
            $repForPack[$order->package][]=$order;
        }
        return View::make('kitchen.pkreport')
            ->with('orders',$orders)
            ->with('repForPack',$repForPack)
            ->with('type','pkreport')

            ->withTitle(trans('main.Package Report'));


    }



    public function getPackaging()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','packaging')

            ->with('isKitchenUser', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Packaging'));

    }

    public function getPackaging2()
    {
        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $isPrepSummaryAuthorized = $this->isAdmin();

        return View::make('kitchen.select_date')

            ->with('type','packaging2')

            ->with('isKitchenUser', $isPrepSummaryAuthorized)

            ->withTitle(trans('main.Packaging'));
    }

    public function getPackagingPdf ()
    {


        if ($this->notKitchen()) {
            return $this->dontAllow();
        }

        $orders = $this->get_current_orders();


        if (!$orders->isEmpty()) {
            $users = [];
            foreach ($orders as $order) {
                $users[$order->user_id]['user'] = $order->user;
                $users[$order->user_id]['orders'][] = $order;
            }
            $orders->users = $users;
        }


        $weekEndAddress=false;
        $nameOfDay = date('l', strtotime(\request()->date));
        if($nameOfDay=="Saturday" || $nameOfDay=="Friday"){
            $weekEndAddress=true;
        }
        $pdf=\PDF2::loadView('kitchen.packaging2',array("orders"=>$orders,'weekEndAddress'=>$weekEndAddress));
        $pdf->setPaper(array(0,0,281,350), 'landscape');
        return $pdf->stream('packaging.pdf');

    }


    public function postGetPackaging()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        if (!$orders->isEmpty()) {

            $users = [];

            foreach ($orders as $order) {

                $users[$order->user_id]['user'] = $order->user;

                $users[$order->user_id]['orders'][] = $order;

            }

            $orders->users = $users;

        }



        return View::make('kitchen.packaging')

            ->with('orders',$orders)

            ->with('type','packaging')

            ->withTitle(trans('main.Packaging'));

    }



    public function getGetPackaging()

    {

        if ($this->notKitchen()) {

            return $this->dontAllow();

        }

        $orders = $this->get_current_orders();



        if (!$orders->isEmpty()) {

            $users = [];

            foreach ($orders as $order) {

                $users[$order->user_id]['user'] = $order->user;

                $users[$order->user_id]['orders'][] = $order;

            }

            $orders->users = $users;

        }


        $weekEndAddress=false;
        $nameOfDay = date('l', strtotime(\request()->date));
        if($nameOfDay=="Saturday" || $nameOfDay=="Friday"){
            $weekEndAddress=true;
        }

        return View::make('kitchen.packaging')
            ->with('orders',$orders)
            ->with('type','packaging')
            ->with('weekEndAddress',$weekEndAddress)
            ->withTitle(trans('main.Packaging'));

    }





    public function get_current_package($id = null)

    {



        $date = Input::has('date') ? Input::get('date') : $id;

        if(\Lang::getLocale()=="en")

        {

            $package = DB::select("select users.id,username,phone,mobile_number,titleEn as package from users join packages on users.package_id = packages.id join (select * from user_dates where date = '$date' and user_dates.freeze=0 ) as user_dates on users.id = user_dates.user_id where users.active=1 and  users.role_id = 1");

        }

        else

        {

            $package = DB::select("select users.id,username,phone,titleAr as package from users join packages on users.package_id = packages.id join (select * from user_dates where date = '$date' and user_dates.freeze=0) as user_dates on users.id = user_dates.user_id where users.active=1 and users.role_id = 1");

        }



        //  var_dump($package);

        // dd($this->queries());



        return $package;

    }







    public function get_current_orders($id = null)

    {



        $this->duplicated();

        $date = isset(request()->date) ? request()->date : $id;




        $users_dates = UserDate::with('user')
            ->where('date',$date)
            ->where('isMealSelected',1)
            ->where('freeze',0)
            ->orderBy('user_id','asc')
            ->get();




        $userIDs = [];
        $dateId=[];

        foreach ($users_dates as $user) {
            //$user->user->membership_end >= date('Y-m-d') &&
            if( $user->user->role_id == 1 && $user->user->active == 1){
                array_push($dateId,$user->id);
                array_push($userIDs,$user->user_id);
            }

        }


//        $dayOfWeek = date('l',strtotime($date));
//
//        $dayRow = Day::where('titleEn',$dayOfWeek)->first();
//        $day_id = $dayRow->id;



          if(\request()->route()->getName()!="kitchen.getPreparation" && \request()->route()->getName()!="kitchen.getLabeling" && \request()->route()->getName()!= "kitchen.getLabeling2"){
              $orders = Order::whereIn('date_id',$dateId)
                  ->join('users', 'orders.user_id', '=', 'users.id')
                  ->whereIn('user_id',$userIDs)
                  ->where('approved',1)
                  ->with('item')
                  ->with('date')
                  ->with('addons')
                  ->with('meal')
                  ->with('category')
                  ->with('portion')
                  ->with('user')
                  ->with('user.area.province')
                  ->with('user.country')
                  ->with('user.countryWeekends')
                  ->with('user.province')
                  ->with('user.provinceWeekends')
                  ->with('user.area')
                  ->with('user.areaWeekends')
                  ->with('user.packageone')
                  ->orderBy('users.priority_in_ordering','desc')
                  ->orderBy('user_id','ASC')
                  ->get();
          }else{
              $orders = Order::whereIn('date_id',$dateId)
                  ->whereIn('user_id',$userIDs)
                  ->where('approved',1)
                  ->with('item')
                  ->with('date')
                  ->with('addons')
                  ->with('meal')
                  ->with('category')
                  ->with('portion')
                  ->with('user')
                  ->with('user.area.province')
                  ->with('user.country')
                  ->with('user.countryWeekends')
                  ->with('user.province')
                  ->with('user.provinceWeekends')
                  ->with('user.area')
                  ->with('user.areaWeekends')
                  ->with('user.packageone')
                  ->orderBy('category_id','ASC')
                  ->orderBy('item_id','ASC')
                  ->get();
          }









        // dd($this->queries());



        return $orders;

    }



    public function getIndex()

    {

        $id = Input::has('user') ? Input::get('user') : false;

        if ($this->notUser() && $this->notDoctor()) {

            return $this->dontAllow();

        }



        $days = Day::where('active',1)->orderBy('ordering','asc')->get();

        if($id)

            $user = User::find($id);

        else

            $user = Auth::user();

        $package = $user->package;



        return View::make('menu.order')

            ->with('days',$days)

            ->with('user',$user)

            ->with('package',$package)

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



    public function getUserMenu($user_id)

    {

        if ($this->notDoctor()) {

            return $this->dontAllow();

        }



        $user = User::where('id',$user_id)

            ->with(['orders_sorted_by_dates' => function ($q)

            {

                $q->orderBy('day_id','asc');

            }])

            ->with('orders_sorted_by_dates.day')

            ->with('orders_sorted_by_dates.meal')

            ->with('orders_sorted_by_dates.category')

            ->with('orders_sorted_by_dates.item')

            ->with('orders_sorted_by_dates.portion')

            ->with('orders_sorted_by_dates.addons')

            ->with('package')

            ->where('active',1)

            ->first();





        if(!$user)

            return Redirect::to('/')->withMessage([trans('main.No User Has Been Found')]);



        $menu = [];



        if(!$user->orders_sorted_by_dates->isEmpty()) {

            foreach ($user->orders_sorted_by_dates as $order) {

                $menu[$order->day->{'title'.LANG}][$order->meal->{'title'.LANG}] = [

                    'category' => $order->category->{'title'.LANG},

                    'item' => $order->item->{'title'.LANG},

                    'portion' => ($order->portion) ? $order->portion->{'title'.LANG} : false,

                    'addons' => $order->addons->toArray(),

                    'order' => $order->toArray(),

                ];

            }

        }

        return View::make('menu.user')

            ->with('menu',$menu)

            ->with('user',$user)

            ->withTitle(trans('main.Menu'));

        return $menu;

        return $user;

        dd($menu);

    }



    public function getEditOrder($id)

    {

        $order = Order::with('user')

            ->with('user.package')

            ->with('user.package.categories.items')

            ->with('user.package.categories.items.addons')

            ->with('portion')

            ->with('day')

            ->with('meal')

            ->with('meal.categories')

            ->with('category')

            ->with('addons')

            ->with('item')

            ->find($id);



        if(!$order)

            return ['result' => false];



        $cats = [];

        $items_addons = [];

        foreach ($order->user->package->categories as $category) {

            if ($order->meal->categories->contains($category->id)) {

                $items = [];

                foreach ($category->items as $item) {

                    if (in_array($item->id,$order->day->items->pluck('id')->toArray())) {
                        $items[] = $item;
                    }

                }

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

        $info = Input::get('items');

        $info['item'] = explode('|', $info['item']);



        $data['day_id'] = Input::get('day_id');

        $data['meal_id'] = Input::get('meal_id');

        if (Input::get('portion_id') != '')

            $data['portion_id'] = Input::get('portion_id');

        $data['user_id'] = Input::get('user_id');

        $data['category_id'] = $info['item'][0];

        $data['item_id'] = $info['item'][1];

        $data['approvec'] = 1;



        // return $data;

        $order = Order::where('day_id',$data['day_id'])

            ->where('user_id',$data['user_id'])

            ->where('meal_id',$data['meal_id'])

            ->delete();

        $order = new Order;

        $order->fill($data);

        $result = $order->save();



        if(isset($info['addons']))

            $order->addons()->sync($info['addons']);





        return ['result' => $result];

    }



    public function getUsers()

    {

        if ($this->notDoctor()) {

            return $this->dontAllow();

        }



        $currentClinic = Clinic::find(Auth::user()->clinic_id);



        $users = User::where('role_id',1)

            ->where('active',1)

            ->where('membership_start','<=',date('Y-m-d'))

            ->where('membership_end','>=',date('Y-m-d'))

            ->orderBy('created_at','desc');



        if (count(Input::all())>=1) {

            foreach (Input::all() as $k => $v) {

                $users = $users->whereRaw($k . " LIKE '%" . $v . "%'");

            }

        }



        if($currentClinic->can_see_others == 0) {

            $users = $users->where('clinic_id',Auth::user()->clinic_id);

        }



        $users = $users->paginate(10);



        // dd($this->queries());

        return View::make('user.grid')

            ->with('users',$users)

            ->withTitle(trans('main.Users'));



    }



    public function postSave()

    {

        if ($this->notUser() && $this->notDoctor()) {

            return $this->dontAllow();

        }

        $data['day_id'] = Input::get('day');

        $package = Input::get('package');

        $data['user_id'] = Input::has('user') ? Input::get('user') : Auth::user()->id;

        $data['approved'] = 0;

        $items = Input::get('items');

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

                if ($order['item_id'] == $data['item_id']) {

                    $data['portion_id'] = $order['portion_id'];

                }



            }



            // Delete Same Day Same User Meal Choice

            Order::where('day_id',$data['day_id'])

                ->where('user_id',$data['user_id'])

                ->where('meal_id',$data['meal_id'])

                ->delete();





            // Doctor Auto Approved

            if (Auth::user()->role_id == 2) {

                $data['approved'] = 1;

            }



            $order = new Order;

            $order->fill($data);

            $result = $order->save();



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

        //  $dayItems = array_fetch($dayItems,'id');

        // Bring Up Package

        $packageObj = Package::where('id',$package)->with('meals')->with('categories')->with('meals.categories')->first();

        // Get Current Categories Ids

        $packageCategories = $packageObj->categories->pluck('id')->toArray();

        //$packageCategories = array_fetch($packageCategories,'id');

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

                    $items = Item::where('category_id',$category['id'])->with('addons')->get()->toArray();



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

                                    //$addonsOrderedIds = array_fetch($addonsOrdered,'id');

                                    // dd($addonsOrdered);

                                    if(@(in_array($addon['id'], $addonsOrdered))) {

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
}
