<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 3:00 PM
 */

namespace App\Http\Controllers\Admin;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class MembershipRenewalController extends AdminController
{



    public function __construct()

    {

        // The Model To Work With

        $this->model = User::class;

        $this->views = '';

        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'membership_renewal';



        // Human Name

//		$this->humanName = 'الاعدادات';



        // Fields for this table



        $this->fields[] = array('title' => trans('main.User'), 'name' => 'username', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.EndsBy'), 'name' => 'membership_end', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageAr'), 'name' => 'titleAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageEn'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.RoleAr'), 'name' => 'roleNameAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.RoleEn'), 'name' => 'roleNameEn', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Days left'), 'name' => 'days_left', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
//		$this->fields[] = ['title' => trans('main.Mobile'), 'name' => 'mobile','type' => 'text','col' => 2];



        // Grid Buttons
        $this->buts = [];//['edit'] = ['name'=>'Edit', 'icon'=>'print', 'color' => 'blue']; // grid and record buttons
        $this->gridButs['renew'] = array('name' =>'Renew/Add membership','icon' => 'print', 'color' => 'green', 'JShndlr'=>"showRenewPopup");  // record buttons
        $this->recordButs['Add'] = ['name'=>'Add', 'icon'=>'add', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only
        $this->customJS = "cpassets/js/membership_renewal.js?2";
        $this->routeParams = [];

        parent::__construct();
    }

    public function loadData()
    {

        $M = $this->model;
        $date = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d'), date('Y')));
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
        $this->items = $M::where('membership_end', '!=','0000-00-00')->where('role_id', '=', 1)->where('users.active', 1)->where('package_id', '!=', 33 )->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.id');
        if($this->searchItem!=null && $this->searchItem!=""){
            $this->items = $this->items->where('username','like','%'.$this->searchItem.'%');
        }

        $this->items=$this->items->paginate(20);


//    	$this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//		var_dump($M);
    }


    public function loadUserDetails($userid)
    {
        $userDetails = DB::select('SELECT username FROM users WHERE id = '.$userid);
        echo json_encode(["result"=>"SUCCESS", "data"=>$userDetails]);
    }


    public function loadPackages()
    {
        $packages = DB::select('SELECT * FROM packages;');
        echo json_encode(["result"=>"SUCCESS", "data"=>$packages]);
    }

    public function calcDaysLeft($userId,$renewDate)
    {
        $renewDateObj = new \DateTime($renewDate);
        $renewDateFormatted = $renewDateObj->format("Y-m-d");
        $membershipEndArr = DB::select("SELECT membership_end FROM users WHERE  id = ?",array($userId));
        $interval = 0;
        if(count($membershipEndArr))
        {


            $membershipEnd = new \DateTime($membershipEndArr[0]->membership_end);
            $membershipEndFormatted = $membershipEnd->format("Y-m-d");

            if($renewDateFormatted<$membershipEndFormatted)
            {
                $membershipEndFormatted = date_create($membershipEndFormatted);
                $renewDateFormatted = date_create($renewDateFormatted);
                $interval = date_diff($renewDateFormatted, $membershipEndFormatted);
                $interval = $interval->format('%a');

            }
        }

        return $interval;
    }
    /*	public function calcDaysLeft($userId) // always call after insert into customer_memberships
        {

            $effectiveDateArr = DB::select("SELECT id, membership_effective_date FROM  customer_memberships WHERE active = 1 AND user_id = ?", array($userId));

            if(count($effectiveDateArr))
            {
            $membershipValidWithinDate = new DateTime($effectiveDateArr[0]->membership_effective_date);
            $membershipValidWithinDateFormatted = $membershipValidWithinDate->format("Y-m-d");
            $membershipId = $effectiveDateArr[0]->id;
            $daysLeft = 28;
            $suspDays = 0;
            $membershipEndArr = DB::select("SELECT membership_end FROM users WHERE  id = ?",array($userId));
            if(count($membershipEndArr))
            {
                $membershipEnd = new DateTime($membershipEndArr[0]->membership_end);
            $membershipsArr = DB::select("SELECT suspension_start, suspension_end FROM membership_suspensions WHERE user_id = ? AND membership_id = ?", array($userId, $membershipId));
            for($i = 0; $i < count($membershipsArr); $i++)
            {
                $suspensionStartObj = new DateTime($membershipsArr[$i]->suspension_start);
                $suspensionStart = $suspensionStartObj->format("Y-m-d");
                $suspensionEndObj = new DateTime($membershipsArr[$i]->suspension_end);
                $suspensionEnd = $suspensionEndObj->format("Y-m-d");
                            // Edited by Basila to remove the problem regarding date
                            $suspensionEnd = date_create($suspensionEnd);
                            $suspensionStart = date_create($suspensionStart);
                            // end of edit
                $calcSuspensionDays = $suspensionEnd->diff($suspensionStart, true)->format("%R%a");
                $suspDays = $suspDays + $calcSuspensionDays;
            }

            return $daysLeft;
        }
        else
        {
            $calcDaysLeft = 0;
            $membershipEndArr = DB::select("SELECT membership_end FROM users WHERE  id = ?",array($userId));
            if(count($membershipEndArr))
            {
                $membershipEnd = new DateTime($membershipEndArr[0]->membership_end);
                $now = new DateTime();
                $diff = $membershipEnd->diff($now, true)->format("%R%a");
                $calcDaysLeft = $diff;
            }
            return $calcDaysLeft;
        }

        } */

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


    public function addMembership()
    {
        $arr = Input::all();
        $userId = $arr["user_id"];
        $renewDate = $arr["renew_date"];
        $renewDays = $arr["daysrenew"];
        $packageArr = DB::select("SELECT package_id, membership_end FROM users WHERE id = ?", array($userId));
        //$packageId = $arr["packege"];
        $packageId = $packageArr[0]->package_id;
        $membershipEndObj = new \DateTime($renewDate);
        $membershipEnd = $membershipEndObj->modify("+".$renewDays." days");
        $rs = DB::select("SELECT days_left FROM users WHERE id = ". $userId);
        $rss = $rs[0];

        $daysLeft = $this->calcDaysLeft($userId,$renewDate);
        $res = DB::insert('insert into members_packages (userid, package_id, active, active_from, created_at) values (?, ?, ?, ?,?)', array($userId, $packageId, 1, $renewDate, date("Y-m-d H:i:s")));
        if($res == 0)
        {
            echo json_encode(["result"=>"FAILURE"]);
            return;
        }

        $renewDateObj = new \DateTime($renewDate);
        $renewDateFormatted = $renewDateObj->format("Y-m-d");

        $isActive = true;
        for($i = 0; $i < $renewDays+$daysLeft; $i++)
        {

            $dateObj = new \DateTime($renewDate);
            $dateObjMod = $dateObj->modify('+'.$i.' days');
            $date = $dateObjMod->format('Y-m-d');
            DB::insert('insert into user_dates (date, user_id) VALUES (?, ?)', array($date, $userId));
        }

        $res .= DB::update('update users set days=0, week =0,membership_end = ?, days_left = ? where id = ?', array($date, $renewDays, $userId));


        $sql = DB::insert('INSERT INTO customer_memberships (user_id, days_left, membership_effective_date, active, package_id,created_at) VALUES (?, ? , ?, ?, ?, ?)', array($userId, $renewDays+$daysLeft, $renewDateFormatted, $isActive, $packageId, date("Y-m-d H:i:s")));



        echo json_encode(["result"=>"SUCCESS", "days_left"=>$daysLeft]);

    }


}
