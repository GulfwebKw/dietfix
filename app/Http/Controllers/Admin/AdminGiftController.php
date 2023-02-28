<?php

/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:13 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Gift;

class AdminGiftController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = Gift::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'gifts';

        // Human Name
        $this->humanName = trans('main.Gifts');

        $this->con_delete = 1;


        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true, 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true, 'type' => 'text', 'col' => 2);


        $this->fields[] = array('title' => trans('main.Image'), 'name' => 'image' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'gifts', 'width' => 20, 'grid' => true);

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active', 'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);

        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'type' => 'timestampDisplay', 'col' => 2);

        // Grid Buttons
        $this->buts['add'] = array('name' => 'Add', 'icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' => 'Edit', 'icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' => 'Delete', 'icon' => 'remove', 'color' => 'red');


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

        //        foreach ($this->fields as $field) {
        //
        //            if($field['type']=='many2many'){
        //
        //                dd($field);
        //
        //            }
        //
        //        }
    }

    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)
            ->get();
    }
}
