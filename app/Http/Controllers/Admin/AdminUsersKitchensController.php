<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 11:36 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Area;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\Province;
use App\Models\Country;
use App\Models\Role;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;

class AdminUsersKitchensController extends AdminController
{

    public function __construct()
    {
        // The Model To Wor With
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'kitchen_users';

        // Human Name
        $this->humanName = 'المطبخ';

        $roles = Role::select(['roleName'.LANG, 'id'])->get();
        $this->provinces = Province::select(['title'.LANG, 'id'])->get();
        $this->areas = Area::select(['title'.LANG, 'id'])->get();
        $this->countries = Country::select(['title'.LANG, 'id'])->get();
        $this->clinics = Clinic::select(['title'.LANG, 'id'])->get();

        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.User Name'), 'name' => 'username','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Email Address'), 'name' => 'email','width' => 25, 'grid' => true ,'type' => 'email', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone No.'), 'name' => 'phone','width' => 10, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Password'), 'name' => 'password' ,'type' => 'password', 'col' => 2);
        // $this->fields[] = array('title' => trans('main.Address'), 'name' => 'address','type' => 'textarea');
        $this->fields[] = array('title' => trans('main.Province'), 'name' => 'province_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select', 'valOptions'=>"id",'keyOptionsSelect'=>'title'.LANG_SHORT,'data' => $this->provinces, 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Area'), 'name' => 'area_id', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'select','valOptions'=>"id",'keyOptionsSelect'=>'title'.LANG_SHORT, 'data' => $this->areas, 'noChosen' =>true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Country'), 'name' => 'country_id', 'grid' => true ,'valOptions'=>"id",'keyOptionsSelect'=>'title'.LANG_SHORT, 'data' => $this->countries,'type' => 'select', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Clinic'), 'name' => 'clinic_id', 'grid' => true , 'valOptions'=>"id",'keyOptionsSelect'=>'title'.LANG_SHORT,'data' => $this->clinics,'type' => 'select', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Group'), 'name' => 'role_id', 'value' => 3,'type' => 'hidden');
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active','width' => 3, 'grid' => true,'type' => 'switcher');

        // $this->fields[] = array('title' => trans('main.Options'), 'name' => 'metas', 'type' => 'manyToOne', 'masterModel' => 'UserMeta');
        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'timestampDisplay', 'col' => 2);


        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        // The View Folder
        $this->views = 'users';

        // Deleteable
        $this->deletable = true;

        $this->buts = [];
        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
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
        $this->items = $this->items->where('role_id',3);

        $this->items = $this->items->get();
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

}