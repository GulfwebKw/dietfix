<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 1:18 PM
 */

namespace App\Http\Controllers\Admin;


use App\Models\StandardMenu;
use Illuminate\Support\Facades\DB;

class AdminStandardMenusControllerNew extends AdminController
{


    public function __construct()
    {
        // The Model To Work With
        $this->model = StandardMenu::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'standard_menus_new';

        // Human Name
        $this->humanName = 'Standard Menus';

        $this->fields[] = array('title'=>trans('main.Name En'), 'name'=>'standard_menu_nameEn', 'width'=>55, 'grid'=>true, 'type'=>'text', 'col'=>2);
        $this->fields[] = array('title'=>trans('main.Name Ar'), 'name'=>'standard_menu_nameAr', 'width'=>55, 'grid'=>true, 'type'=>'text', 'col'=>2);



        //$this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');
        // $this->buts['edit'] = array('name' =>'Assign Standard Menu','icon' => 'plus', 'color' => 'blue');

        $this->gridButs=[];
        $this->recordButs = [];
        //$this->routeParams = [];
        $this->customJS = "";
        // The View Folder
        $this->views = '';
        $this->deletable = true;
        $this->checkboxCol = true;


        parent::__construct();
    }



    public function loadData()
    {
        //$M = $this->model;
        $this->items = DB::select("SELECT * FROM standard_menus");
    }


}