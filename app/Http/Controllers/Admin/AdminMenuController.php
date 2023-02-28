<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 11:13 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Admin\AdminMenu;

class AdminMenuController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = AdminMenu::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'admin_menu';

        // Human Name
        $this->humanName = 'قائمة الموقع';



        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Name'), 'name' => 'menuTitleAr', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Name'), 'name' => 'menuTitleEn', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Link'), 'name' => 'menuLink' ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Icon'), 'name' => 'menuIco' ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Parent Page'), 'name' => 'menu_id', 'searched' => true, 'width' => 20, 'grid' => true ,'valOptions'=>'otherType','type' => 'select' , 'data' => AdminMenu::parentMenuData());
        $this->fields[] = array('title' => trans('main.Ordering'), 'name' => 'ordering', 'searched' => false, 'width' => 20, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Visible'), 'name' => 'visible' ,'type' => 'switcher');

        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');
        $this->gridButs = [];
        $this->recordButs = [];

        // The View Folder
        // $this->views = 'adminmenu';

        // Deleteable
        $this->deletable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        $this->sortBy = 'ordering';
        $this->sortType = 'ASC';
        parent::__construct();
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('parentMenu')->get();
        // $this->items = $M::all();
    }

    public function filterGrid()
    {
        // dd($this->items);
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->menu_id = ($item->menu_id == 0) ? '-' : $item->parentMenu()->getResults()->{ 'menuTitle' . ucfirst(LANG_SHORT) };
        }

        return $newItems;
    }
}