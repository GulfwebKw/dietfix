<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 10:52 AM
 */

namespace App\Http\Controllers\Admin;

use App\Models\Clinic\Province;

class AdminProvincesController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Province::class;

        // Sort By
        $this->sortBy='id';

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'provinces';

        // Human Name
        $this->humanName = 'المحافظات';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 40, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 40, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Ordering'), 'name' => 'ordering', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 15, 'grid' => true);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        // The View Folder
        // $this->views = 'admins';

        // Deleteable
        $this->deletable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        // Upload Files
        // protected $uploadable = true;


        // Sort Type
        // protected $sortType = 'desc';

        // Sort By
        // protected $sortBy = 'id';

        parent::__construct();
    }

    public function getCities($id)
    {
        return Province::find($id)->cities->toArray();
    }

}