<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 4:20 PM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Country;

class AdminCountriesController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = Country::class;

        // Sort By
        $this->sortBy='id';


        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'countries';

        // Human Name
        $this->humanName = 'الدول';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 40, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 40, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Name'), 'name' => 'alpha_2', 'searched' => true, 'width' => 40, 'grid' => true ,'type' => 'text');

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');

        // The View Folder
        // $this->views = 'users';

        // Deleteable
        $this->deletable = true;
        // Uploadable
        $this->uploadable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    
}