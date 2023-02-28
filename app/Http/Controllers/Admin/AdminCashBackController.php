<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/22/2019
 * Time: 4:18 PM
 */

namespace App\Http\Controllers\Admin;
use App\Models\CashBack;
use App\Models\Clinic\Clinic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class AdminCashBackController extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = CashBack::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'cash-back';

        // Human Name
        $this->humanName = 'النقدي الى الوراء';

        $this->sex = ['Male' => trans('main.Male'),'Female' => trans('main.Female')];
        $this->salt = ['No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt'),'More Salt' => trans('main.More Salt')];

        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.User Name'), 'name' => 'username','width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone No.'), 'name' => 'phone','width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active','width' => 3, 'grid' => true,'type' => 'div', 'col' => 2);
      //  $this->fields[] = array('title' => trans('main.Package'), 'name' => 'package_id','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Membership Start'), 'name' => 'membership_start','type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Membership End'), 'name' => 'membership_end','type' => 'div', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Amount'), 'name' => 'value','grid'=>true,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Status'), 'name' => 'status','grid'=>true,'type' => 'div', 'col' => 2);

        // $this->fields[] = array('title' => trans('main.Options'), 'name' => 'metas', 'type' => 'manyToOne', 'masterModel' => 'UserMeta');
        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);



        $this->buts=[];
            // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->recordButs['accept'] = array('name' =>'Accept','icon' => 'plus', 'color' => 'green');
        $this->recordButs['reject'] = array('name' =>'Reject','icon' => 'reject', 'color' => 'red');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        // The View Folder
        $this->views = 'menu';

        // Deleteable
        $this->deletable = false;

        $this->gridButs = [];
       // $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }
    public function getView($id)
    {



        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;
        $this->item = $M::where($this->_pk, $id)
            ->with('user')
            ->first();


        $menu = [];

        if(!$this->user->isEmpty()) {
            foreach (optional($this->item)->orders_sorted_by_dates as $order) {
                $menu[$order->day->{'title'.LANG}][optional($order->meal)->{'title'.LANG}] = [

                    'category' => optional($order->category)->{'title'.LANG},
                    'item' => optional($order->item)->{'title'.LANG},
                    'portion' => ($order->portion) ? $order->portion->{'title'.LANG} : false,
                    'addons' => $order->addons->toArray(),
                    'order' => $order->toArray(),
                ];
            }
        }



        $this->grabOtherTables();


        return View::make( 'admin.forms.'.$this->views.'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'user' , $this->item )
            ->with( 'menu' , $menu )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('user.package');

        if($this->searchItem!=null && $this->searchItem!=""){
            $this->items=$this->items->whereHas("user",function ($r){
                $r->where('username','like','%'.$this->searchItem.'%');
            });
        }
        $this->items = $this->items->paginate(20);


    }
    public function grabOtherTables()
    {
        $this->item->username = $this->item->user->username;
        $this->item->phone = $this->item->user->phone;
        $this->item->membership_star = $this->item->user->membership_star;
        $this->item->membership_end  = $this->item->user->membership_end;

        $this->item->active = ($this->item->user->active) ? trans('main.Yes') : trans('No');

        $this->item->status = ($this->item->user->active) ? trans('main.Yes') : trans('No');
     //   $this->item->package_id = optional(optional($this->item->user)->package)->{'title'.LANG};

//        $this->item->country_id = $this->item->country->{'title'.LANG};
//        $this->item->clinic_id = $this->item->clinic->{'title'.LANG};
        // $this->item->clinic_id = $this->item->clinic->{'title'.LANG};
    }
    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            // $newItems[$k]->role_id = $item->role->{'roleName'.LANG};
            $newItems[$k]->username = $item->user->username;
            $newItems[$k]->phone = $item->user->phone;

            $newItems[$k]->membership_star = $item->user->membership_star;
            $newItems[$k]->membership_end = $item->user->membership_end;
            $newItems[$k]->active = ($item->user->active) ? trans('main.Yes') : trans('No');

            if($newItems[$k]->status==0){
                $newItems[$k]->status ="Init Request";
            }elseif ($newItems[$k]->status==1){
                $newItems[$k]->status ="Accepted By Admin";
            }elseif ($newItems[$k]->status==2){
                $newItems[$k]->status ="Rejected By Admin";
            }elseif ($newItems[$k]->status==3){
                $newItems[$k]->status ="Cancel By User";
            }


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

        return view( 'admin.gridWithAjax',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }
    public function reject($id)
    {
        $rec=CashBack::where('id',$id)->with('user')->first();
        if($rec->status==0 && isset($rec->user)){
            $this->makeAdminLog("reject cash back user");

            $rec->status=2;
            $rec->save();
            $message=" reject cash back request successFully";
            $success=true;
            DB::table("notifications")->insert(['titleAr'=>'Cash Back Request','titleEn'=>'Cash Back Request','contentAr'=>'Reject Cash Back By Admin','contentEn'=>'Reject Cash Back By Admin','user_id'=>$rec->user_id]);

            if(isset($rec->user->email))
            $res=Mail::to($rec->user->email)->send(new \App\Mail\CashBackUser($rec->user,$rec,0));


        }else{
            $this->makeAdminLog(" request Cash back is not valid");
            $message=" request Cash back is not valid";
            $success=false;
        }
        session()->flash("message",$message);
        session()->flash("success",$success);
        return \redirect()->back();

    }
    public function accept($id)
    {

        $rec=CashBack::where('id',$id)->with('user')->first();
        if($rec->status==0){
            $rec->status=1;
            $rec->save();
            $sum=$rec->value;
            DB::table("credit_user")->insert(['user_id'=>$rec->user_id,'value'=>floatval($sum)*2,'operation'=>'decrement']);
            $message=" credit add to related user successFully";
            $success=true;
            $this->makeAdminLog(" accept cashBack user");

            DB::table("notifications")->insert(['titleAr'=>'Cash Back Request','titleEn'=>'Cash Back Request','contentAr'=>'Accept Cash Back By Admin','contentEn'=>'Accept Cash Back By Admin','user_id'=>$rec->user_id]);
            if(isset($rec->user->email))
                $res=Mail::to($rec->user->email)->send(new \App\Mail\CashBackUser($rec->user,$rec,1));


        }else{
            $this->makeAdminLog(" request Cash back is not valid");

            $message=" request Cash back is not valid";
            $success=false;
        }
        session()->flash("message",$message);
        session()->flash("success",$success);
        return \redirect()->back();

    }


}
