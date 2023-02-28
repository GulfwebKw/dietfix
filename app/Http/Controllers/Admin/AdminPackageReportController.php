<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:20 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\PackageReports;

class AdminPackageReportController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = PackageReports::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'packagereports';

        // Human Name
        $this->humanName = trans('main.Packages Reports');

        $this->con_delete = 1;

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Package Name'), 'name' => 'package_name', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Total Users'), 'name' => 'total_users', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        //$this->fields[] = array('title' => trans('main.Arabic Details'), 'name' => 'detailsAr', 'type' => 'textarea', 'col' => 2);
        //$this->fields[] = array('title' => trans('main.English Details'), 'name' => 'detailsEn', 'type' => 'textarea', 'col' => 2);

        //$this->fields[] = array('title' => trans('main.Users'), 'name' => 'Users','searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);


        //$this->fields[] = array('title' => trans('main.Active Users'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);

        //$this->fields[] = array('title' => trans('main.Meals'), 'name' => 'meals' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Meal::where('active',1)->get(),'notExist' => true);
        //$this->fields[] = array('title' => trans('main.Categories'), 'name' => 'categories' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Category::where('active',1)->get(),'notExist' => true);

        //$this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'type' => 'timestampDisplay', 'col' => 2);
        //$this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'type' => 'timestampDisplay', 'col' => 2);

        // Grid Buttons
        $this->buts =[];
        //$this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        //$this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        //$this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');


        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        // The View Folder
        // $this->views = 'teachers';

        // Deleteable
        $this->deletable = true;

        // Upload Files
        // protected $uploadable = true;


        // Sort Type
        // protected $sortType = 'desc';

        // Sort By
        // protected $sortBy = 'id';

        parent::__construct();
    }

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)
            ->get();
    }


}