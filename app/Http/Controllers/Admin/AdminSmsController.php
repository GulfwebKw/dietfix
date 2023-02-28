<?php


namespace App\Http\Controllers\Admin;
use App\Models\Sms;
class AdminSmsController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model =Sms::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'sms';

        // Human Name
        $this->humanName = 'اسمس';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Content'), 'name' => 'contentAr','type' => 'textarea');
        $this->fields[] = array('title' => trans('main.English Content'), 'name' => 'contentEn','type' => 'textarea');
       // $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher');

        // Grid Buttons
        //$this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
//        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        // The View Folder
        // $this->views = 'users';

        // Deleteable
        $this->deletable = true;
        // Uploadable
        $this->uploadable = true;

        parent::__construct();
    }

}