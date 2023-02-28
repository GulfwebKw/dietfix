<?php
namespace App\Http\Controllers\Admin;
use App\Models\Frontend\MealsSample;

class AdminExamplesOfMealsController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model =MealsSample::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'site-page/examples-of-meals';

        // Human Name
        $this->humanName = 'أمثلة من بين وجبات الطعام';


        $this->fields[] = array('title' => trans('main.Photo'), 'name' => 'photo', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'file' ,'multi' => false, 'photo' => true, 'folder' => 'gallery');

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
            $newItems[$k]->photo= '<img src="'.url('resize?w=30&h=30&src=media/gallery/'.$item->photo).'" alt="" />';
           // $newItems[$k]->url= '<a href="' . $newItems[$k]->url . '">' . trans('main.URL') . '</a>';
        }
        return $newItems;
    }




}