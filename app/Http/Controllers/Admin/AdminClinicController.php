<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:08 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Clinic;

class AdminClinicController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Clinic::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'clinics';

        // Human Name
        $this->humanName = trans('main.Clinics');

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Logo'), 'name' => 'logo' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'clinics', 'width' => 20, 'grid' => true);

        $this->fields[] = array('title' => trans('main.Email'), 'name' => 'email', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Phone'), 'name' => 'phone', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Contact Info'), 'name' => 'contact_info', 'type' => 'textarea');

        $this->fields[] = array('title' => trans('main.Can See Others'), 'name' => 'can_see_others' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Can Take Appointment'), 'name' => 'can_appointment' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        // $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        // The View Folder
        // $this->views = 'shops';

        // Deleteable
        // $this->deletable = true;

        // Upload Files
        // protected $uploadable = true;


        // Sort Type
        // protected $sortType = 'desc';

        // Sort By
        // protected $sortBy = 'id';

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)->get();
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->logo= ($item->logo) ? '<img src="' . url('resize?w=50&h=50&c=1&r=1&src=media/clinics/'.$item->logo) . '" alt="">' : '-';
            // $newItems[$k]->active= ($item->active == '1') ? trans('main.Yes') :  trans('main.No');
        }
        return $newItems;
    }
}