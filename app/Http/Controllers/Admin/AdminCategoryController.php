<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:32 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Category;
use App\Models\Clinic\Meal;

class AdminCategoryController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Category::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'categories';

        // Human Name
        $this->humanName = trans('main.Categories');

        $this->meals = Meal::orderBy('ordering','asc')->select(['title'.LANG,'id'])->get();

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Meal'), 'name' => 'meal_id', 'searched' => true, 'width' => 20, 'grid' => true ,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG,'type' => 'select', 'data' => $this->meals);
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


        // Sort Type
        $this->sortType = 'asc';

        // Sort By
        $this->sortBy = 'ordering';


        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('meal')->orderBy($this->sortBy, $this->sortType)->get();
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            // $newItems[$k]->logo= ($item->logo) ? '<img src="' . url('resize?w=50&h=50&c=1&r=1&src=media/clinics/'.$item->logo) . '" alt="">' : '-';
            $newItems[$k]->meal_id= $item->meal->{'title'.LANG};
        }
        return $newItems;
    }
}
