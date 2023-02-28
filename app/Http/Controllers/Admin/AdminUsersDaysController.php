<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/23/2019
 * Time: 12:26 PM
 */

namespace App\Http\Controllers\Admin;

use App\Models\Clinic\UserDate;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Clinic\Clinic;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class AdminUsersDaysController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'users_days';

        // Human Name
        $this->humanName = 'ايام العملاء';


        $this->sex = ['Male' => trans('main.Male'),'Female' => trans('main.Female')];
        $this->salt = ['No Salt - local' => trans('main.No Salt - Local'),'Medium Salt - Local' => trans('main.Medium Salt - Local'), 'No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt')];

        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.User Name'), 'name' => 'username','width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Email Address'), 'name' => 'email','width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('Mobile'), 'name' => 'mobile_number','width' => 10, 'grid' => true ,'type' => 'div','noChosen' =>true, 'col' => 2);
        // $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea');
        $this->fields[] = array('title' => trans('main.Province'), 'name' => 'province_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Area'), 'name' => 'area_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Block'), 'name' => 'block', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Street'), 'name' => 'street', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Avenue'), 'name' => 'avenue', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('House Number'), 'name' => 'house_number', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('Address'), 'name' => 'address', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Country'), 'name' => 'country_id', 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Clinic'), 'name' => 'clinic_id', 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active','width' => 3, 'grid' => true,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Group'), 'name' => 'role_id','type' => 'hidden','value' => 1);

        $this->fields[] = array('title' => trans('main.Sex'), 'name' => 'sex','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Salt'), 'name' => 'salt','type' => 'div', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Height'), 'name' => 'height','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Weight'), 'name' => 'weight','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Package'), 'name' => 'package_id','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('Duration'), 'name' => 'package_duration_id','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.BMI'), 'name' => 'bmi','type' => 'text', 'col' => 2);

//        $this->fields[] = array('title' => trans('main.Membership Start'), 'name' => 'membership_start','type' => 'date', 'col' => 2);
//        $this->fields[] = array('title' => trans('main.Membership End'), 'name' => 'membership_end','type' => 'date', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'div');
        // $this->fields[] = array('title' => trans('main.Options'), 'name' => 'metas', 'type' => 'manyToOne', 'masterModel' => 'UserMeta');
        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);


        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        ///$this->buts['freez'] = array('name' =>'Freez','icon' => 'stop', 'color' => 'red');

        // The View Folder
        $this->views = 'users-days';


        $this->gridButs = [];
        $this->recordButs = [];
        //$this->recordButs['membership'] = array('name' =>'Renew-Or-AddMembership','icon' => 'print', 'color' => 'green');
        //$this->recordButs['freeze'] = array('name' =>'freeze','icon' => 'lock', 'color' =>'red');
        //$this->recordButs['unFreeze'] = array('name' =>'unFreeze','icon' => 'unlock', 'color' => 'green');

        $this->routeParams = [];
        $this->customJS = "";

        // Deleteable
        $this->deletable = true;

        parent::__construct();
    }
    public function store(Request $request)
    {


        $membership_start = Input::get('membership_start');
        $membership_end   = Input::get('membership_end');
        $id    = Input::get('id');
        $dates = Input::get('dates');
		
		$userDetails = User::find($id);
	
        if(!empty($userDetails->package_id)){
		
        $this->makeAdminLog("call store function in AdminUsersDaysController (edit Client Days)  for userId==>$id ",null,$id,"call store function in AdminUsersDaysController");

        $this->makeAdminLog("(edit Client Days) delete days where not in selected days collect  for userId==>$id ".json_encode($dates),null);

      if(is_array($dates)){
          UserDate::where('user_id',$id)->whereNotIn('date',$dates)->delete();
          foreach ($dates as $date) {
              $res= UserDate::where('user_id',$id)->where('date',$date)->first();
              if(!isset($res)){
                  $user_date = new UserDate;
                  $user_date->user_id       = $id;
                  $user_date->date          = $date;
				  $user_date->package_id    = $userDetails->package_id;
				  $user_date->update_status = 'admin';
                  $user_date->save();
              }
          }

        }
     
	  }
        if(Input::get('save_return'))
            return Redirect::to($this->menuUrl.'/edit/'.$id);
        return Redirect::to( $this->menuUrl);
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('role')->with('country')->with('clinic')->with('province')->with('area');
        $current_clinic = Clinic::find(Auth::user()->clinic_id);

        // Filter Clinic On Grid
        if($current_clinic->can_see_others == 0)
            $this->items = $this->items->where('clinic_id',$current_clinic->id);
        //$this->items = $this->items->where('role_id',1);


        if($this->searchItem!=null){
            if($this->is_number($this->searchItem)==true) {
                $this->items=$this->items->where('id','=',$this->searchUser);
                $this->items=$this->items->orwhere(function($sq){
				$sq->where('id','like',$this->searchItem)
				   ->orWhere('mobile_number',"like","%".$this->searchItem."%")
				   ->orWhere('phone',"like","%".$this->searchItem."%");
				});
            }else{
                $this->items=$this->items->where(function($sq){
				 $sq->where('mobile_number',"like","%".$this->searchItem."%")
					->orWhere('phone',"like","%".$this->searchItem."%")
					->orWhere("username","like","%".$this->searchItem."%")
					->orWhere("fullname","like","%".$this->searchItem."%");
				});
            }
        }

        $this->items = $this->items->orderBy('created_at','desc')->paginate(20);
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
    
    public function grabOtherTables()
    {

        $this->item->province_id = optional(optional($this->item)->province)->{'title'.LANG};
        $this->item->area_id = optional(optional($this->item)->area)->{'title'.LANG};
        $this->item->country_id = optional(optional($this->item)->country)->{'title'.LANG};
        $this->item->clinic_id =optional(optional($this->item)->clinic)->{'title'.LANG};
        $this->item->package_id = optional(optional($this->item)->package)->{'title'.LANG};
        $this->item->package_duration_id = optional(optional($this->item)->packageDuration)->{'title'.LANG};
        $this->item->active = ($this->item->active) ? trans('main.Yes') : trans('No');
        $this->item->dates_array = ($this->item->dates->isEmpty()) ? [] : $this->item->dates->pluck('date')->toArray();
        $days_deff = 0;


//        if ($this->item->membership_end) {
//            $membership_end = strtotime($this->item->membership_end);
//            $membership_start = strtotime($this->item->membership_start);
//            $days_deff = (floor(($membership_end - $membership_start)/(60*60*24))) + 1;
//        }
//        $this->item->days_deff = $days_deff;
//        $this->item->membership_start_time = strtotime($this->item->membership_start);
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
    public function edit($id)
    {

        // $id = Request::segment(4);


        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;

        $this->item = $M::where($this->_pk,$id)->first();
        $days_deff =0;
        if(isset($this->item->membership_start)){
            $first=strtotime($this->item->membership_start);

            $days=UserDate::where('date','>=',$this->item->membership_start)->where("freeze",0)->where('user_id',$this->item->id)->orderBy("date","asc")->get();

        }else{
            $days=collect([]);
            $first=null;

        }



        //$first=$days->first();
        $last=$days->last();

        if($days->count()>=1){
            $days_deff =intval((floor((strtotime($last->date)-$first)/(60*60*24))) + 1);
        }


       // dd($days_deff,$days);
        $this->grabOtherTables();

        $this->recordButs = [];
        $this->recordButs[] = array('name' =>'Renew-Or-AddMembership','icon' => 'print', 'color' => 'green','link'=>'renew-or-addmembership');
        $this->recordButs[] = array('name' =>'freeze','icon' => 'lock', 'color' =>'red','link'=>'freeze');
        $this->recordButs[] = array('name' =>'unFreeze','icon' => 'unlock', 'color' => 'green','link'=>'unfreeze');

        return View::make( 'admin.forms.' . $this->views . 'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields )
            ->with( 'days' ,$days->pluck('date')->toArray())
            ->with('first',$first)
            ->with("days_deff",$days_deff)
            ->with("recordButsx",$this->recordButs);


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
}
