<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 2:48 PM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\UserDate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class MembershipDashboard extends AdminController
{

    public function __construct()

    {



        $this->model = User::class;

//		$this->views = 'm_dashboard';


        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'membership';



        // Human Name

        $this->humanName = 'الاعدادات';



        // Fields for this table



        $this->fields[] = array('title' => trans('main.User'), 'name' => 'username', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.EndsBy'), 'name' => 'membership_end', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageAr'), 'name' => 'titleAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageEn'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.RoleAr'), 'name' => 'roleNameAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.RoleEn'), 'name' => 'roleNameEn', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
//		$this->fields[] = ['title' => trans('main.Mobile'), 'name' => 'mobile','type' => 'text','col' => 2];


        // Grid Buttons
        $this->buts = [];//['edit'] = ['name'=>'Edit', 'icon'=>'print', 'color' => 'blue']; // grid and record buttons
        $this->gridButs['follow_up'] = array('name' =>'Follow ups','icon' => 'print', 'color' => 'green', 'JShndlr'=>'showPopup');  // record buttons
        $this->gridButs['email'] = ['name'=>'Email', 'icon'=>'email', 'color'=>'gray' , 'JShndlr'=>'sendEmail'];
        $this->recordButs['Add'] = ['name'=>'Add', 'icon'=>'add', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only
        $this->customJS = "cpassets/js/mebership_dashboard.js";
        parent::__construct();
    }
    public function getEndDahsFilterDate($date)
    {
       $first=User::with(['package','lastDay','role','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->whereHas('lastDay',function ($r)use($date){
            $r->where('date','=',$date);
        })->get();
       $arr=[];
        foreach ($first as $item) {
            if($item->lastDay->date==$date){
                array_push($arr,$item->id);
            }

        }

        $user= User::with(['package','lastDay','role','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->whereHas('lastDay',function ($r)use($date){
            $r->where('date','=',$date);
        })->whereIn('id',$arr)->get();
        return view( 'admin.user.endDashboard',['_pageTitle'=>$this->humanName,'users'=>$user,'date'=>$date]);



    }
    public function addNote()
    {
        $arr = Input::all();
        $note   = $arr["note"];
        $userId = $arr["userid"];
        $rs = DB::table('membership_followups')->insert(
            array('note' =>$note, 'user_id' => $userId, 'created_at'=>date('Y-m-d H:i:s'))
        );
        if($rs == true)
        {
            echo json_encode(["result"=>"SUCCESS"]);
        }
        else
        {
            echo json_encode(["result"=>"FAILURE"]);
        }
    }
    public function sendEmail($user_id)
    {
        $headers = array("From: mahmoud@grassrootskw.com",
            "Reply-To: mahmoud@grassrootskw.com",
            "X-Mailer: PHP/" . PHP_VERSION
        );
        $headers = implode("\r\n", $headers);

        $userTB = DB::table('users');
        $users = $userTB->where('id', '=', $user_id)->select('email', 'membership_end')->get();
        //var_dump($rs);
        $to;
        $endDate;
        foreach($users AS $user)
        {
            $to = $user->email;
            $endDate = $user->membership_end;
        }
        $subject = "Dietfix membership ending";
        $msg = "Hello,
		
		Your subscription with Dietfix ends on ".$endDate." .";

        // change the message header
        // add to follow ups the message sent
        // echo success
        if(mail($to, $subject, $msg, $headers))
        {
            echo json_encode(["result"=>"SUCCESS"]);
        }
        else
        {
            echo json_encode(["result"=>"FAILURE"]);
        }
    }
    public function loadData()
    {

        $M = $this->model;
        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 7, date('Y')));
        $sdate = Session::get('sdate');
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
        if(!empty($sdate)){
            $this->items = $M::with('lastDay')->where('membership_end', '=',$sdate)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.id');
        }else{
            $this->items = $M::with('lastDay')->where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.id');
        }

        if($this->searchItem!=null && $this->searchItem!=""){
            $this->items = $this->items->where('username','like','%'.$this->searchItem.'%')->orWhere('membership_end','like','%'.$this->searchItem.'%')->orWhere('phone','like','%'.$this->searchItem.'%');
            $this->items = $this->items->orderBy('membership_end', 'desc');
            //$this->items = $this->items->where('username','like','%'.$this->searchItem.'%')->orWhere('membership_end','like','%'.$this->searchItem.'%')->orWhere('phone','like','%'.$this->searchItem.'%')->orWhere('id','like','%'.$this->searchItem.'%')->orWhere('titleEn','like','%'.$this->searchItem.'%')->orWhere('titleAr','like','%'.$this->searchItem.'%');
        }


        $this->items = $this->items->paginate(20);



//    	$this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//		var_dump($M);
    }
    public function loadData3()
    {

        $M = $this->model;
        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 3, date('Y')));
        $this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn')->get();
    }
    public function loadData5()
    {

        $M = $this->model;
        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') + 5, date('Y')));
        $this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn')->get();
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
    public function loadNotes($user_id)
    {

        $notes =    DB::table('users')
            ->join('membership_followups','users.id','=','membership_followups.user_id')
            ->where('users.id',$user_id)
            ->select(['users.id as userid ','users.username as username','users.note','membership_followups.created_at as created_at'])->get();
//        $notes = DB::select('select users.id AS userid, users.username AS username, note, membership_followups.created_at AS created_at from users INNER JOIN membership_followups ON users.id = membership_followups.user_id WHERE user_id = ?', [$user_id]);
        echo json_encode(["result"=>"SUCCESS", "data"=>$notes]);
//	$this->items = $notes;
    }
    public function saveSession($sdate)
    {
        Session::put('sdate', $sdate);
        //echo json_encode(["result"=>"SUCCESS", "data"=>array("sdate"=>$sdate)]);
    }
    public function index()
    {


        $user= User::with(['package','lastDay','role','dates'=>function($r){
            $r->where('date','>=',date('Y-m-d'));
        }])->whereHas('dates',function ($r){
            $r->where('date','>=',date('Y-m-d'));
        })->get();
        return view( 'admin.user.endDashboard',['_pageTitle'=>$this->humanName,'users'=>$user]);

    }

}
