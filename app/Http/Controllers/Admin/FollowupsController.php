<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 3:04 PM
 */

namespace App\Http\Controllers\Admin;


use App\Models\MembershipFollowups;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class FollowupsController extends AdminController
{


    public function __construct()

    {
        // The Model To Work With
        $this->model = MembershipFollowups::class;
        $this->views = '';

        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = '';



        // Human Name

        $this->humanName = 'Follow up notes';



        // Fields for this table



        $this->fields[] = array('title' => trans('main.User'), 'name' => 'username', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.EndsBy'), 'name' => 'membership_end', 'searched' => true, 'width' => 5, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageAr'), 'name' => 'titleAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.PackageEn'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Email'), 'name' => 'email', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Note'), 'name' => 'note', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.CreatedAt'), 'name' => 'created_at', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'div', 'col' => 2);


        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->gridButs = [];
        $this->recordButs= [];
        $this->customJS = '';
        $this->routeParams = 'loadData';

        parent::__construct();
    }


    public function loadData()
    {
//		$M = $this->model;
        $userId = Input::get('user_id');
        $M = DB::table('membership_followups');
        $this->items = $M->where('user_id', '=',$userId )->join('users', 'membership_followups.user_id','=', 'users.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'users.id', 'membership_followups.note', 'membership_followups.created_at')->get();
//    	var_dump($this->items);
    }



}