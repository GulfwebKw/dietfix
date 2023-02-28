<?php


namespace App\Http\Controllers\App;
use App\Http\Controllers\Admin\AdminController;
use App\Models\App\AppSlideShow;

class AdminAppSlideShowController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model =AppSlideShow::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'app/slideshow';

        // Human Name
        $this->humanName = 'السلايد شو';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Name'), 'name' => 'titleAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Name'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Arabic Content'), 'name' => 'contentAr','type' => 'wysiwyg');
        $this->fields[] = array('title' => trans('main.English Content'), 'name' => 'contentEn','type' => 'wysiwyg');
        $this->fields[] = array('title' => trans('main.Photo'), 'name' => 'photo', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'file' ,'multi' => false, 'photo' => true, 'folder' => 'slideshow');
        $this->fields[] = array('title' => trans('main.URL'), 'name' => 'url', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher');

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');

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
    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->photo= '<img src="'.url('resize?w=30&h=30&src=media/slideshow/'.$item->photo).'" alt="" />';
            $newItems[$k]->url= '<a href="' . $newItems[$k]->url . '">' . trans('main.URL') . '</a>';
        }
        return $newItems;
    }



}