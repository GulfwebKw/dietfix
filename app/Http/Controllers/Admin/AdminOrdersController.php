<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/22/2019
 * Time: 4:18 PM
 */

namespace App\Http\Controllers\Admin;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\Order;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AdminOrdersController extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'orders';

        // Human Name
        $this->humanName = 'مينيو';

        $this->sex = ['Male' => trans('main.Male'),'Female' => trans('main.Female')];
        $this->salt = ['No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt'),'More Salt' => trans('main.More Salt')];

        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.User Name'), 'name' => 'username','width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        //$this->fields[] = array('title' => trans('main.Email Address'), 'name' => 'email','width' => 25, 'grid' => false ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone No.'), 'name' => 'phone','width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        // $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea');
        $this->fields[] = array('title' => trans('main.Province'), 'name' => 'province_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Area'), 'name' => 'area_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        //$this->fields[] = array('title' => trans('main.Country'), 'name' => 'country_id', 'grid' => false ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Clinic'), 'name' => 'clinic_id', 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active','width' => 3, 'grid' => true,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Group'), 'name' => 'role_id','type' => 'hidden','value' => 1);

        $this->fields[] = array('title' => trans('main.Sex'), 'name' => 'sex','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Salt'), 'name' => 'salt','type' => 'div', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Height'), 'name' => 'height','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Weight'), 'name' => 'weight','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Package'), 'name' => 'package_id','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.BMI'), 'name' => 'bmi','type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Membership Start'), 'name' => 'membership_start','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Membership End'), 'name' => 'membership_end','type' => 'div', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'div');
        // $this->fields[] = array('title' => trans('main.Options'), 'name' => 'metas', 'type' => 'manyToOne', 'masterModel' => 'UserMeta');
        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);



        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['view'] = array('name' =>'View','icon' => 'plus', 'color' => 'green');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        // The View Folder
        $this->views = 'menu';

        // Deleteable
        $this->deletable = false;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    public function getView($id)
    {



        if(!$id || !is_numeric($id))
            die();
        $m = $this->model;
        $user=user::find($id);
        $menu =[];
       if(isset($user->membership_start)){
           $userdate= db::table("user_dates")->where("date",">=",$user->membership_start)->orderby('date','asc')->where("freeze",0)->where('user_id',$user->id)->select(['*'])->get();
           $dateid= db::table("user_dates")->where("date",">=",$user->membership_start)->where("freeze",0)->where('user_id',$user->id)->pluck('id');


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






//        db::table("orders")
//            ->join('')
//
//
//            ->wherein('date_id',$dateid);


        $this->item = $m::where($this->_pk, $id)->first();


//        if($this->item->orders_sorted_by_dates->count()>=1) {
//            foreach (optional($this->item)->orders_sorted_by_dates as $order) {
//                $menu[optional($order->date)->date][optional($order->meal)->{'title'.lang}] = [
//                    'category' => optional($order->category)->{'title'.lang},
//                    'item' => optional($order->item)->{'title'.lang},
//                    'portion' => ($order->portion) ? $order->portion->{'title'.lang} : false,
//                    'addons' => $order->addons->toarray(),
//                    'order' => $order->toarray(),
//                ];
//            }
//        }


        $this->grabothertables();


        return view::make( 'admin.forms.'.$this->views.'form' )
            ->with( '_pageTitle' , trans('main.add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'user' , $this->item )
            ->with( 'menu' , $menu )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
    }

    public function selectOrder($dayId)
    {
//      $res=  DB::table("orders")->where('date_id',$dayId)
//            ->join("categories",'orders.category_id',"categories.id")
//            ->join("items",'orders.item_id',"items.id")
//            ->join("meals",'orders.meal_id',"meals.id")
//            ->select(["categories.titleEn as category","items.titleEn as item","meals.titleEn as meal","orders.meal_id",'items.id as item_id',"orders.id"])->get();
//        dd($res);
       return   Order::with(['day','date','meal','category','item','portion','addons'])->where('date_id',$dayId)->get();
    }

    public function postSave()
    {
        // Filter Clinic On Save
        if (Input::get($this->_pk)) {
            $current_clinic = Clinic::find(Auth::user()->clinic_id);
            $modifing_user = User::find(Input::get($this->_pk));

            // Filter Clinic On Grid
            if($current_clinic->can_see_others == 0 && Auth::user()->clinic_id != $modifing_user->clinic_id)
                return Redirect::to(ADMIN_FOLDER);
        }
        return parent::postSave();
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('role')->with('country')->with('clinic')->with('province')->with('area');

        $current_clinic = Clinic::find(Auth::user()->clinic_id);
        // Filter Clinic On Grid
        if($current_clinic->can_see_others == 0)
            $this->items = $this->items->where('clinic_id',$current_clinic->id);
        $this->items = $this->items->where('role_id',1);

        if($this->searchItem!=null && $this->searchItem!=""){
            $skey = $this->searchItem;
            $this->items=$this->items->where(function($sq) use($skey){
                $sq->orwhere('id','=',$skey)->orwhere('username','like','%'.$skey.'%')->orwhere('mobile_number','like','%'.$skey.'%');
                });
                
        }

        $this->items = $this->items->paginate(20);


    }
    public function grabOtherTables()
    {
        $this->item->province_id = $this->item->province->{'title'.LANG};
        $this->item->area_id = $this->item->area->{'title'.LANG};
        $this->item->country_id = $this->item->country->{'title'.LANG};
        $this->item->clinic_id = $this->item->clinic->{'title'.LANG};
        $this->item->package_id = optional(optional($this->item)->package)->{'title'.LANG};
        $this->item->active = ($this->item->active) ? trans('main.Yes') : trans('No');
        // $this->item->clinic_id = $this->item->clinic->{'title'.LANG};
    }
    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            // $newItems[$k]->role_id = $item->role->{'roleName'.LANG};
            $newItems[$k]->area_id = $item->area->{'title'.LANG};
            $newItems[$k]->province_id = $item->province->{'title'.LANG};
            $newItems[$k]->country_id = $item->country->{'title'.LANG};
            $newItems[$k]->clinic_id = $item->clinic->{'title'.LANG};
        }
        return $newItems;
    }
    public function getAjax(Request $request)
    {

        $this->searchItem=$request->search['value'];

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
                if ($b['name'] != 'Add') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';

                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
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
                    $butsCol .= '<a href="./orders/view/'.$item->{$this->_pk}.'" class="btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>';
                }
                if ($b['name'] == 'Invoice') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
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
        return view( 'admin.gridWithAjax',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }
}
