<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:01 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Area;
use Illuminate\Support\Facades\DB;

class AdminAreasController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = Area::class;

        // Primary Key
        $this->_pk = 'id';

        // Sort By
        $this->sortBy='id';

        // Main Part of menu url
        $this->menuUrl = 'areas';

        // Human Name
        $this->humanName = 'المناطق';


        $provinces = DB::table('provinces')->select(['title'.LANG, 'id'])->get()->toArray();
       // $this->provinces = $provinces;


        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Province'), 'name' => 'province_id', 'width' => 15, 'grid' => true ,'valOptions'=>'id','keyOptionsSelect'=>'title'.LANG, 'data' => $provinces,'type' => 'select');
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 30, 'grid' => true);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        // The View Folder
        // $this->views = 'admins';

        // Deleteable
        $this->deletable = true;

        // Upload Files
        // protected $uploadable = true;


        // Sort Type
        // $this->sortType = 'asc';

        // Sort By
        // $this->sortBy = 'ordering';
        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
//        foreach ($this->fields as $field) {
//            if($field['type']=='select'){
//                foreach ($field['data'] as $datum) {
//
//                    dd($datum->{$field['valOptions']});
//                }
//            }
//        }

        parent::__construct();
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            // $newItems[$k]->active= ($item->active == '1') ? trans('main.Yes') :  trans('main.No');
            $newItems[$k]->province_id = $item->province->{'title'.LANG};
        }
        return $newItems;
    }

}