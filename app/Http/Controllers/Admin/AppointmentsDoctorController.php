<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 11:05 AM
 */

namespace App\Http\Controllers\Admin;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppointmentsDoctorController extends AdminController
{


    public function __construct()
    {
        $this->model = User::class;

        $this->_pk = 'id';

        $this->humanName = '';
        $this->menuUrl = 'appointments';

        $this->buts = [];
        $this->gridView = 'appointments.doctor_selection';

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        $this->indexViewFile = 'doctor_selection';
        // The View Folder
        $this->views = 'appointments';
        $this->isGrid = false;
        parent::__construct();
    }

    public function loadData()
    {

        $M = $this->model;
        $date = date("Y-m-d");
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('', '>',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.active')->get();
        $this->items = DB::select("SELECT users.id AS id, users.username AS username, packages.titleAr AS titleAr, packages.titleEn AS titleEn, users.membership_end AS membership_end, users.phone AS phone,users.email AS email, roles.roleNameAr AS roleNameAr, roles.roleNameEn AS roleNameEn, (CASE users.active WHEN 1 THEN 'True' ELSE 'False' END) AS active FROM users INNER JOIN packages ON users.package_id = packages.id INNER JOIN roles ON users.role_id = roles.id WHERE membership_end > ?", [$date]);
//    	$this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//		var_dump($M);

    }
    public function index()
    {

        $dieticians = DB::select("SELECT id, username FROM users WHERE role_id = 2");
        return View::make('appointments.doctor_selection')
            ->with('dieticians', $dieticians);
    }

    public function getAppt(Request $request)
    {
        $dieticianId = trim($request->dietician_id);
        $apptsDate = str_replace('/', '-', trim($request->appts_date));
        //$date = DateTime::createFromFormat('m-d-Y', $apptsDate);
        //echo $date;
        //ob_flush();
        //echo $apptsDate;
        //$apptsDate = $date->format('Y-m-d H:i:s');
        $apptsDate .= ' 00:00:00';

        //ob_flush();
        //echo $apptsDate;

        $appointments =DB::select( DB::raw("SELECT appointments.id AS id, users.username AS username, clinics.titleAr AS clinicAr, clinics.titleEn AS clinicEn, 
appointments.date AS ApptDate, appointments.hour AS hour, notes, confirmed, doctors.username AS doctorName, users.membership_end AS membership_end  FROM appointments 
INNER JOIN users ON appointments.user_id =  users.id 
INNER JOIN clinics ON appointments.clinic_id = clinics.id 
INNER JOIN users AS doctors ON appointments.doctor_id = doctors.id WHERE appointments.doctor_id = :dieticianId AND appointments.date = :apptsDate"), array('dieticianId'=>$dieticianId, 'apptsDate'=>$apptsDate) );
        /*var_dump(DB::raw("SELECT appointments.id AS id, users.username AS username, clinics.titleAr AS clinicAr, clinics.titleEn AS clinicEn,
    appointments.date AS ApptDate, appointments.hour AS hour, notes, confirmed, doctors.username AS doctorName FROM appointments
    INNER JOIN users ON appointments.user_id =  users.id
    INNER JOIN clinics ON appointments.clinic_id = clinics.id
    INNER JOIN users AS doctors ON appointments.doctor_id = doctors.id WHERE appointments.doctor_id = :dieticianId AND appointments.date = :apptsDate"), array('dieticianId'=>$dieticianId, 'apptsDate'=>$apptsDate));*/

        echo json_encode(["result"=>"SUCCESS", "data"=>$appointments]);
    }

}