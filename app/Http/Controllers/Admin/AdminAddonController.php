<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:45 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Addon;

class AdminAddonController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Addon::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'addons';

        // Human Name
        $this->humanName = trans('Addons');

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);


        $this->fields[] = array('title' => trans('main.Code'), 'name' => 'code', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text', 'col' => 2, 'value' => 100);
        $this->fields[] = array('title' => trans('main.Photo'), 'name' => 'photo' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'items', 'width' => 20,'col'=>1);

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
        // protected $sortType = 'desc';

        // Sort By
        // protected $sortBy = 'id';
        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

}