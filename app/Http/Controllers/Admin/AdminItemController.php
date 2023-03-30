<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/27/2019
 * Time: 11:36 AM
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Cookie;

use App\Models\Clinic\Addon;
use App\Models\Clinic\Category;
use App\Models\Clinic\Day;
use App\Models\Clinic\Item;

use App\Models\Clinic\ItemAddons;
use App\Models\Clinic\ItemDays;

use Illuminate\Http\Request;

class AdminItemController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Item::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'items';

        // Human Name
        $this->humanName = trans('Items');

        $this->categories = Category::orderBy('ordering','asc')->select(['title'.LANG,'id'])->get();
        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Arabic Details'), 'name' => 'detailsAr', 'type' => 'textarea', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Details'), 'name' => 'detailsEn', 'type' => 'textarea', 'col' => 2);


        $this->fields[] = array('title' => trans('main.Protien'), 'name' => 'protien', 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Fat'), 'name' => 'fat', 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Carb'), 'name' => 'carb', 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Calories'), 'name' => 'calories', 'type' => 'text', 'col' => 2);


        $this->fields[] = array('title' => trans('main.Photo'), 'name' => 'photo' ,'type' => 'file','multi' => false, 'photo' => true, 'folder' => 'items', 'width' => 20, 'grid' => true);

        $this->fields[] = array('title' => trans('main.Addons'), 'name' => 'addons' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Addon::where('active',1)->get(),'notExist' => true);
        $this->fields[] = array('title' => trans('main.Days'), 'name' => 'days' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Day::where('active',1)->get(),'notExist' => true);

        $this->fields[] = array('title' => trans('main.Category'), 'name' => 'category_id', 'searched' => true, 'width' => 30, 'grid' => true ,'type' => 'select','valOptions'=>'id','keyOptionsSelect'=>'title'.LANG, 'data' => $this->categories, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Rating'), 'name' => 'rating', 'searched' => true, 'width' => 30 ,'type' => 'rating','valOptions'=>'otherType', 'data' => [1,2,3,4,5], 'col' => 2,'class'=>'example_rating');

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);
        // Grid Buttons
        $this->buts['add']    = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit']   = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
		$this->buts['view']   = array('name' =>'Category','icon' => 'plus', 'color' => 'green');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');
        $this->buts['export'] = array('name' =>'Export','icon' => 'file-excel-o', 'color' => 'grey');

        $this->gridButs    = [];
        $this->recordButs  = [];
        $this->routeParams = [];
        $this->customJS    = "";
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

        parent::__construct();

    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('category')->orderBy($this->sortBy, $this->sortType)->get();
    }
    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->photo= ($item->photo) ? '<img src="' . url('resize?w=100&h=100&r=1&c=1&src=media/items/'.$item->photo) . '" alt="">' : '-';
            $newItems[$k]->category_id= $item->category->{'title'.LANG};
        }
        return $newItems;
    }
    public function getItemDays(Request $request)
    {

        $items= Item::with(["category","days"])->get();
        return view( 'admin.itemDaysGrid',['_pageTitle'=>$this->humanName,'items'=>$items]);

    }

  public function chooseItemCategory(Request $request){

	$items = Item::where('id',$request->id)->with(["category","days"])->first();
	$categories =  Category::where('id','<>',$items->category_id)->orderBy('ordering','asc')->select(['title'.LANG,'id'])->get();
    return view( 'admin.chooseItemcategory',['_pageTitle'=>'Categories For Items','items'=>$items,'categories'=>$categories]);
	}

  public function chooseCategory(Request $request){
	 $itemId     = $request->item;
	 $categoryId = $request->category;

	 Cookie::queue("chosen_".$itemId."_".$categoryId,'selected',3600);

	 $itemsOld     = Item::where('id',$itemId)->first();
	 $itemdsAddonsLists = ItemAddons::where('item_id',$itemId)->get();
	 $itemsDaysLists    = ItemDays::where('item_id',$itemId)->get();

	 //upload image if

	 if(!empty($itemsOld->photo) && file_exists(public_path().'/media/items/'.$itemsOld->photo)){
	    $oldPath = public_path().'/media/items/'.$itemsOld->photo;
		$fileExtension = \File::extension($oldPath);
		$newfileName   = 'copy-'.$itemId.'-'.$categoryId.'-'.time().'.'.$fileExtension;
		$newPath = public_path().'/media/items/'.$newfileName;
		\File::copy($oldPath , $newPath);
	 }

	 $newitem = new Item;
	 $newitem->titleAr  = $itemsOld->titleAr;
	 $newitem->titleEn  = $itemsOld->titleEn;
	 $newitem->protien  = $itemsOld->protien;
	 $newitem->fat      = $itemsOld->fat;
	 $newitem->carb     = $itemsOld->carb;
	 $newitem->calories = $itemsOld->calories;
	 $newitem->photo    = $newfileName;
	 $newitem->detailsAr   = $itemsOld->detailsAr;
	 $newitem->detailsEn   = $itemsOld->detailsEn;
	 $newitem->category_id = $categoryId;
	 $newitem->active      = $itemsOld->active;
	 $newitem->rating      = $itemsOld->rating;
	 $newitem->save();
	 //addons
	 if(!empty($newitem->id) && !empty($itemdsAddonsLists) && count($itemdsAddonsLists)>0){
	  foreach($itemdsAddonsLists as $itemdsAddonsList){
	   $newaddon = new ItemAddons;
	   $newaddon->item_id  = $newitem->id;
	   $newaddon->addon_id = $itemdsAddonsList->addon_id;
	   $newaddon->save();
	  }
	 }
	 //days
	 if(!empty($newitem->id) && !empty($itemsDaysLists) && count($itemsDaysLists)>0){
	  foreach($itemsDaysLists as $itemsDaysList){
	   $newday = new ItemDays;
	   $newday->item_id = $newitem->id;
	   $newday->day_id  = $itemsDaysList->day_id;
	   $newday->save();
	  }
	 }
	 return back()->with('success','A copy of the chosen item is added with new chosen category');
	}


    public function export(){
        $columns = [ // Set Column to be displayed
            'Item Code' => 'id',
            'Arabic Title' => 'titleAr',
            'English Title' => 'titleEn',
            'Category' => function ($item) {
                return $item->category->{'title'.LANG} ;
            },
            'Active' => function ($item) {
                if ($item->active)
                    return 'Yes';
                return 'No';
            }
        ];
        return getCsvExport(Item::query(), $columns, 'Items_report_');
    }
}
