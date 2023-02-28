<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 2:56 PM
 */

namespace App\Http\Controllers\Admin;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class MembershipSuspensionController extends AdminController
{

    public function __construct()

    {

        // The Model To Work With

        $this->model = User::class;

        $this->views = '';

        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'membership_suspension';



        // Human Name

//		$this->humanName = 'الاعدادات';



        // Fields for this table



        $this->fields[] = array('title' => trans('main.User'), 'name' => 'username', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.EndsBy'), 'name' => 'membership_end', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageAr'), 'name' => 'titleAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageEn'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.isActive'), 'name' => 'active', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.isSuspended'), 'name' => 'suspend', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
//		$this->fields[] = ['title' => trans('main.Mobile'), 'name' => 'mobile','type' => 'text','col' => 2];


        // Grid Buttons
        $this->buts = [];//['edit'] = ['name'=>'Edit', 'icon'=>'print', 'color' => 'blue']; // grid and record buttons
        $this->gridButs['suspend'] = array('name' =>'Suspend','icon' => 'stop', 'color' => 'red', "JShndlr"=>"showSuspensionPopup");  // record buttons
        $this->gridButs['resume'] = array('name' =>'Resume','icon' => 'continue', 'color' => 'green', "JShndlr"=>"showResume");  // record buttons
        $this->recordButs['Add'] = ['name'=>'Add', 'icon'=>'add', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only

        $this->routeParams["dataLoaderFunction"] = "";
        $this->customJS = "cpassets/js/membership_suspension.js?v=1";

        parent::__construct();
    }
    public function loadData()
    {


        $M = $this->model;
        $date = date("Y-m-d");
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('', '>',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.active')->get();
        $this->items = DB::select("SELECT users.id AS id,
                                   users.username AS username,
                                    packages.titleAr AS titleAr, 
                                    packages.titleEn AS titleEn,
                                     users.membership_end AS membership_end,
                                      users.phone AS phone,
                                      users.email AS email,
                                       roles.roleNameAr AS roleNameAr,
                                        roles.roleNameEn AS roleNameEn,
                                         (CASE users.active WHEN 1 THEN 'True' ELSE 'False' END) AS active, 
                                          (CASE suspension.suspend WHEN 1 THEN 'Yes' WHEN 2 THEN 'Suspension and Resume Date Set' WHEN 3 THEN 'Set Suspension Date' ELSE 'No' END) AS suspend  
                                           FROM users INNER JOIN packages ON users.package_id = packages.id 
                                           INNER JOIN roles ON users.role_id = roles.id 
                                           left join (
                                           select (
                                                      case when ((now() >= suspension_start)&&(suspension_end = '0000-00-00')) then 1 When ((now() <= suspension_start)&&(now() <= suspension_end)) THEN 2 ELSE 3 END) as suspend,
                                                      user_id,
                                                      created_at as created from membership_suspensions where   ((now() <= suspension_end) || (suspension_end = '0000-00-00')) ) as suspension ON suspension.user_id = users.id  WHERE membership_end != '0000-00-00' and role_id = 1 order by users.id");


//    	$this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//		var_dump($M);




    }
    function isRealDate($date)
    {
        if (false === strtotime($date))
        {
            return false;
        }
        else
        {
            list($year, $month, $day) = explode('-', $date);
            if (false === checkdate($month, $day, $year))
            {
                return false;
            }
        }
        return true;
    }

    public function suspendMembership()
    {

        $arr = Input::all();
        $suspendStartObj = new \DateTime($arr["start"]);
        $suspendStart = $suspendStartObj->format("Y-m-d");
        $userId = $arr["userid"];
        $lastSuspensionend = "somedate";

        $lastSuspension = DB::select("SELECT id,suspension_start,suspension_end FROM membership_suspensions WHERE user_id = ? ORDER BY id DESC LIMIT 1", array($userId));
        if($lastSuspension)
        {
            $lastSuspensionid = $lastSuspension[0]->id;
            $lastSuspensionstart = $lastSuspension[0]->suspension_start;
            $lastSuspensionend = $lastSuspension[0]->suspension_end;
        }
        $membershipIdArr = DB::select("SELECT id FROM customer_memberships WHERE user_id = ? AND active = 1 order by id desc limit 1", array($userId));
        if($membershipIdArr)
        {
            $membershipId = $membershipIdArr[0]->id;
            if($lastSuspensionend=="0000-00-00")
            {
                DB::update("UPDATE membership_suspensions SET suspension_start = ? WHERE user_id = ? AND membership_id = ? AND id = ?", array($suspendStart, $userId, $membershipId, $lastSuspensionid));
            }
            else
            {
                DB::insert("INSERT INTO membership_suspensions (suspension_start, user_id, membership_id, created_at) VALUES (?,?,?, NOW())", array($suspendStart, $userId, $membershipId));
            }
        }
        else
        {
            $membershipId = "";
            if($lastSuspensionend=="0000-00-00")
            {
                DB::update("UPDATE membership_suspensions SET suspension_start = ? WHERE user_id = ? AND id = ?", array($suspendStart, $userId, $lastSuspensionid));
            }
            else
            {
                DB::insert("INSERT INTO membership_suspensions (suspension_start, user_id, created_at) VALUES (?,?, NOW())", array($suspendStart, $userId));
            }
        }

        // edited by basila DB::statement("UPDATE users SET active = 0, updated_at= NOW() WHERE id = ".$userId);
        if($lastSuspensionend=="0000-00-00")
        {
            if($suspendStart>$lastSuspensionstart)
            {
                $begin = new \DateTime($lastSuspensionstart);
                $end = new \DateTime($arr["start"]);


                $interval = new DateInterval('P1D');
                $daterange = new DatePeriod($begin, $interval ,$end);

                foreach($daterange as $date){
                    DB::insert("INSERT INTO user_dates (user_id, date) VALUES (?, ?);", array($userId, $date->format("Y-m-d")));
                }

            }
        }
        DB::delete("DELETE FROM user_dates WHERE user_id = ? AND date >= ?", array($userId, $suspendStart));


        echo json_encode(["result"=>"SUCCESS"]);
    }


    /*	public function calcDaysLeft($userId) // always call after insert into customer_memberships
        {
            $effectiveDateArr = DB::select("SELECT id, membership_effective_date FROM  customer_memberships WHERE active = 1 AND user_id = ? order by id desc limit 1", array($userId));
                    if($effectiveDateArr)
            {
                       $membershipValidWithinDate = new DateTime($effectiveDateArr[0]->membership_effective_date);
               $membershipValidWithinDateFormatted = $membershipValidWithinDate->format("Y-m-d");
               $membershipId = $effectiveDateArr[0]->id;
            }
            else
            {
                       // taking the very first start date of membership
                       $startdateArr = DB::select("SELECT membership_start FROM  users WHERE id = ?", array($userId));
                       $membershipValidWithinDate = new DateTime($startdateArr [0]->membership_start);
               $membershipValidWithinDateFormatted = $membershipValidWithinDate->format("Y-m-d");
                       $membershipId = "";
            }
            $daysLeft = 28;
                    $dteDiff  = 0;
                    $suspenddays  = 0;
            $todaysDate = new DateTime();
            $todaysDateFormatted = $todaysDate->format("Y-m-d");

            if($membershipId!="")
            {
            $membershipsArr = DB::select("SELECT suspension_start, suspension_end FROM membership_suspensions WHERE user_id = ? AND membership_id = ? order by suspension_start ASC", array($userId, $membershipId));
            }
            else
            {
            $membershipsArr = DB::select("SELECT suspension_start, suspension_end FROM membership_suspensions WHERE user_id = ? AND membership_id is null order by suspension_start ASC", array($userId));
            }
            for($i = 0; $i < count($membershipsArr); $i++)
            {
                    $suspensionStartObj = new DateTime($membershipsArr[$i]->suspension_start);
                            if($i==count($membershipsArr)-1)
                            {

                               $dteDiff  = $membershipValidWithinDate->diff($suspensionStartObj)->format("%R%a");
                               $daysLeft = $daysLeft-($dteDiff - $suspenddays);
                            }
                            else
                            {

                                $suspensionStartObj = new DateTime($membershipsArr[$i]->suspension_start);
                        //$suspensionStart = $suspensionStartObj->format("Y-m-d");
                    $suspensionEndObj = new DateTime($membershipsArr[$i]->suspension_end);
                    //$suspensionEnd = $suspensionEndObj->format("Y-m-d");
                    $calcSuspensionDays = $suspensionEndObj->diff($suspensionStartObj, true)->format("%R%a");
                    $suspenddays = $suspenddays + $calcSuspensionDays ;
                }

            }

                    return $daysLeft;
        }
        */
    public function resumeMembership()
    {

        $arr = Input::all();
        $userId = $arr["userid"];
        $resumeDate = $arr["resume_date"];

        $lastMembershipSuspensionArr = DB::select("SELECT id,suspension_start,suspension_end FROM membership_suspensions WHERE user_id = ? ORDER BY id DESC LIMIT 1", array($userId));
        $lastMembershipSuspensionID = $lastMembershipSuspensionArr[0]->id;
        $suspendStartObj = new \DateTime($lastMembershipSuspensionArr[0]->suspension_start);
        $suspendStart = $suspendStartObj->format("Y-m-d");
        if($suspendStart<$resumeDate)
        {
            if($lastMembershipSuspensionArr[0]->suspension_end!="0000-00-00")
            {
                $previousSuspensionendObj = new \DateTime($lastMembershipSuspensionArr[0]->suspension_end);
                $previousSuspensionend = $previousSuspensionendObj->format("Y-m-d");
            }
            else
            {
                $previousSuspensionend = "notset";
            }


            $membershipIDArr = DB::select('SELECT id FROM customer_memberships WHERE user_id = ? AND active = 1', array($userId));
            if($membershipIDArr)
            {
                $membershipId = $membershipIDArr[0]->id;
                DB::update("UPDATE membership_suspensions SET suspension_end = ? WHERE user_id = ? AND membership_id = ? AND id = ?", array($resumeDate, $userId, $membershipId, $lastMembershipSuspensionID));
            }
            else
            {
                $membershipId = "";
                DB::update("UPDATE membership_suspensions SET suspension_end = ? WHERE user_id = ? AND id = ?", array($resumeDate, $userId, $lastMembershipSuspensionID));
            }

            $resumeDateObj = new \DateTime($resumeDate);
            $resumeDateFormatted = $resumeDateObj->format("Y-m-d");

            $resumeDateFormatted = date_create($resumeDateFormatted);
            $suspendStart = date_create($suspendStart);
            $toatlSuspendedDays = date_diff($suspendStart, $resumeDateFormatted);
            $toatlSuspendedDays = $toatlSuspendedDays->format("%a"); // find the total suspended days

            $enddateArr = DB::select("SELECT membership_end FROM  users WHERE id = ?", array($userId));
            $membershipValidWithinDate = new \DateTime($enddateArr[0]->membership_end);
            $membershipValid = $membershipValidWithinDate->modify("+ ".$toatlSuspendedDays." days");

            if($previousSuspensionend!="notset")
            {
                $previousSuspensionend = date_create($previousSuspensionend);
                $prevSuspendedDays = date_diff($suspendStart, $previousSuspensionend);
                $prevSuspendedDays = $prevSuspendedDays->format("%a"); // find the total previously suspended days
                $membershipValid = $membershipValidWithinDate->modify("- ".$prevSuspendedDays." days");
            }

            $membershipEndDate = $membershipValid->format("Y-m-d");


            $membershipEnd = date_create($membershipEndDate);
            $daysLeft =  $resumeDateFormatted->diff($membershipEnd, true)->format("%a");


            // set user active and update membership end
            DB::update("UPDATE users SET days_left = ?, active = 1, membership_end = ? WHERE id = ?", array($daysLeft, $membershipEndDate,$userId));

            //chance of multiple resume
            $suspendStart = $suspendStartObj->format("Y-m-d");
            DB::delete("DELETE FROM user_dates WHERE user_id = ? AND date >= ?", array($userId, $suspendStart));


            // moved the below for loop to below from above this section
            for($i = 0 ; $i <= $daysLeft; $i++)
            {
                $resumeDateObj = new \DateTime($resumeDate);
                $rsDateMod  = $resumeDateObj->modify("+ ".$i." days");
                $resumeDateFormatted  = $rsDateMod->format("Y-m-d");
                DB::insert("INSERT INTO user_dates (user_id, date) VALUES (?, ?);", array($userId, $resumeDateFormatted));
            }

            echo json_encode(["result"=>"SUCCESS"]);
        }
        else
        {
            echo json_encode(["result"=>"Change resume"]);
        }
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

        $users=User::whereHas("dates",function ($r){
            $r->where('freeze',1);
        })->with(["dates"=>function($r){
            $r->where("freeze",1)->orderBy('date','asc');
        }])->paginate(20);

      return view( 'admin.user.freezeUser',['_pageTitle'=>$this->humanName,'users'=>$users,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);

//        dd('ssds');
//
//        foreach ($this->fields as $f) {
//            if (isset($f['grid'])){
//                $this->gridFields[] = $f;
//                $this->gridFieldsName[] = $f['name'];
//            }
//        }
//
//        $this->gridFieldsName = implode(',', $this->gridFieldsName);
//        $this->url = request()->path();
//
//
//        $this->loadData();
//
//        $this->items = $this->filterGrid();
//
//
//        if(isset($this->isGrid))
//        {
//            if($this->isGrid == false)
//            {
//
//                return View::make($this->views.'.'.$this->indexViewFile);
//            }
//        }
//        return view( 'admin.gridWithAjax',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }

}
