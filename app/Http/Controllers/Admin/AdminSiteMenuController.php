<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 11:19 AM
 */

namespace App\Http\Controllers\Admin;

use App\Models\SiteMenu;

class AdminSiteMenuController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = SiteMenu::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'menu';

        // Human Name
        $this->humanName = 'قوائم الموقع';

        $this->menuPositions = array(
            'mainmenu' => trans('main.Main Menu'),
            'footer1menu' => trans('main.Footer Menu').' 1',
            'footer2menu' => trans('main.Footer Menu').' 2',
            'topmenu' => trans('main.Top Menu'),
        );


        $this->accessLevels = array(
            'any' => trans('main.Any'),
            'guest' => trans('main.Guest'),
            'user' => trans('main.User'),
        );

        $this->parentLinks = SiteMenu::getMenuLinks();



        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Name'), 'name' => 'titleAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Name'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.URL'), 'name' => 'url', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Ordering'), 'name' => 'ordering', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher');
        $this->fields[] = array('title' => trans('main.Internal'), 'name' => 'internal' ,'type' => 'switcher');


        $this->fields[] = array('title' => trans('main.Menu'), 'name' => 'menu_name' ,'valOptions'=>'keyVal','type' => 'select','grid' => true,'width' => 25,'data' => $this->menuPositions);
        $this->fields[] = array('title' => trans('main.Access Level'), 'name' => 'access_level' ,'valOptions'=>'keyVal','type' => 'select','grid' => true,'width' => 25,'data' => $this->accessLevels);
        $this->fields[] = array('title' => trans('main.Parent'), 'name' => 'parent_id' ,'valOptions'=>'otherType','type' => 'select','grid' => true,'width' => 25,'data' => $this->parentLinks);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');

        $this->gridButs = [];
        $this->recordButs = [];
        // The View Folder
        // $this->views = 'users';

        // Deleteable
        $this->deletable = true;
        // Uploadable
        $this->uploadable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('parentLink')->with('links')->get();
        // $this->items = $M::all();
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            if($item->internal)
                $newItems[$k]->url= '<a href="' . url($newItems[$k]->url) . '">' . trans('main.URL') . '</a>';
            else
                $newItems[$k]->url= '<a href="' . $newItems[$k]->url . '">' . trans('main.URL') . '</a>';
            $newItems[$k]->parent_id = ($item->parent_id == 0) ? '-' : $item->parentLink->{ 'title' . ucfirst(LANG_SHORT) };
            $newItems[$k]->menu_name = $this->menuPositions[$item->menu_name];
            $newItems[$k]->access_level = $this->accessLevels[$item->access_level];
        }
        return $newItems;
    }

    protected function delete($ids)
    {
        $M = $this->model;
        $res = $M::destroy($ids);
        if(is_array($ids)) {
            foreach ($ids as $id) {
                $M::where('parent_id',$id)->delete();
            }
        } else
            $M::where('parent_id',$ids)->delete();
        if($res)
            return 'true';
        else
            return 'false';
    }

}