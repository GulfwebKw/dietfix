<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:24 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Meal;

class AdminMealController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Meal::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'meals';

        // Human Name
        $this->humanName = trans('main.Meals');

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Ordering'), 'name' => 'ordering', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        // The View Folder
        // $this->views = 'shops';

        // Deleteable
        $this->deletable = true;

        // Upload Files
        // protected $uploadable = true;


        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        // Sort Type
        $this->sortType = 'asc';

        // Sort By
        $this->sortBy = 'ordering';

        parent::__construct();
    }

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)->get();
    }

}