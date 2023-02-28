<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 12:21 PM
 */

namespace App\Http\Controllers\Admin;


use App\User;
use Illuminate\Support\Facades\DB;

class DAppointmentsController extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'd_appointments';

        // Human Name
        $this->humanName = '';


        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields = [];


        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        $this->gridButs['export'] = ['name' =>'Renew/Add membership','icon' => 'print', 'color' => 'green', 'JShndlr'=>"showRenewPopup"];
        $this->recordButs['Add to pdf test'] = ['name'=>'Add pdf', 'icon'=>'', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only

        $this->routeParams = [];
        $this->customJS = "cpassets/js/d_appointments.js";
        // The View Folder
        $this->views = '';


        parent::__construct();
    }

    public function loadDieticians()
    {
        $dieticians = DB::select('SELECT id, username FROM users WHERE role_id = (SELECT id FROM roles WHERE roleNameEn LIKE "Nutritions")');
        echo json_encode(["result"=>"SUCCESS", 'data'=>$dieticians]);
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

}