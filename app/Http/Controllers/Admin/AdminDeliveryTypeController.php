<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:47 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Portion;
use App\Models\Delivery;

class AdminDeliveryTypeController extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = Delivery::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'delivery_type';

        // Human Name
        $this->humanName = 'Delivery Types';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'type_ar', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'type_en', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        // The View Folder
        // $this->views = 'admins';

        // Deleteable
        $this->deletable = true;

        // Upload Files
        // protected $uploadable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        // Sort Type
        // protected $sortType = 'desc';

        // Sort By
        // protected $sortBy = 'id';

        parent::__construct();
    }

}
