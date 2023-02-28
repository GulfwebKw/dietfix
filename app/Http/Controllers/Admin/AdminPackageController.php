<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:13 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Category;
use App\Models\Clinic\Meal;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use Illuminate\Http\Request;

class AdminPackageController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = Package::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'packages';

        // Human Name
        $this->humanName = trans('main.Packages');

        $this->con_delete = 1;

        $this->package_type = ['recommended' => trans('main.recommended'),'weightlessness' => trans('main.weightlessness')];
        
        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Arabic Details'), 'name' => 'detailsAr', 'type' => 'textarea', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Details'), 'name' => 'detailsEn', 'type' => 'textarea', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Price'), 'name' => 'price', 'type' => 'text', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Price After Discount'), 'name' => 'price_after_discount', 'type' => 'text', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Active For App'), 'name' => 'show_mobile' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2,'typeCastToInt'=>true);

        $this->fields[] = array('title' => trans('main.package_type'), 'name' => 'package_type','type' => 'select', 'data' => $this->package_type, 'col' => 2,'valOptions'=>'otherType');
   
        $this->fields[] = array('title' => trans('main.Giftable'), 'name' => 'is_giftable' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2,'typeCastToInt'=>true);


        $this->fields[] = array('title' => trans('main.Background Color(App)'), 'name' => 'background_color' ,'type' => 'text', 'width' => 20,'class'=>'jscolor','col'=>1);

        $this->fields[] = array('title' => trans('main.Meals'), 'name' => 'meals' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Meal::where('active',1)->get(),'notExist' => true);
        $this->fields[] = array('title' => trans('main.Categories'), 'name' => 'categories' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Category::where('active',1)->get(),'notExist' => true);
        $this->fields[] = array('title' => trans('main.Photo'), 'name' => 'photo', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'file' ,'multi' => false, 'photo' => true, 'folder' => 'packages');


        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'type' => 'timestampDisplay', 'col' => 2);

        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');


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

    public function getDurations(Request $request)
    {

        $packageDuration=PackageDurations::where('package_id',$request->id)->get();
        $selectedDuration=$request->userDurationId;
        $view=view('admin.thirdParty.listDuration',compact('packageDuration','selectedDuration'))->render();
       return response()->json(['result'=>true,'view'=>$view]);



    }

}
