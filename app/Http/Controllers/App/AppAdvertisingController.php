<?php
namespace App\Http\Controllers\App;
use App\Http\Controllers\Admin\AdminController;
use App\Models\App\AppAdvertising;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppAdvertisingController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model =AppAdvertising::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'app/advertising';

        // Human Name
        $this->humanName = 'تبلیغات';
		
		$this->isactive = ['yes' => 'Yes','no' => 'No'];

        // Fields for this table
        $this->fields[] = array('title' => trans('main.PhotoEn'), 'name' => 'photoEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'file' ,'multi' => false, 'photo' => true, 'folder' => 'advertising');
        $this->fields[] = array('title' => trans('main.PhotoAr'), 'name' => 'photoAr', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'file' ,'multi' => false, 'photo' => true, 'folder' => 'advertising');
        $this->fields[] = array('title' => trans('main.URL'), 'name' => 'link', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
		
		$this->fields[] = array('title' => trans('main.Active'), 'name' => 'isactive','type' => 'select', 'data' => $this->isactive, 'col' => 2,'valOptions'=>'otherType');

        // Grid Buttons
//        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
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
            $newItems[$k]->photoEn= '<img src="'.url('resize?w=30&h=30&src=media/advertising/'.$item->photoEn).'" alt="" />';
            $newItems[$k]->photoAr= '<img src="'.url('resize?w=30&h=30&src=media/advertising/'.$item->photoAr).'" alt="" />';
            $newItems[$k]->link= '<a href="' . $newItems[$k]->link . '">' . trans('main.URL') . '</a>';
			$newItems[$k]->isactive = $item->isactive;
        }
        return $newItems;
    }

    public function appSettings()
    {
        $settings= DB::table('app_settings')->whereIn('setting_key',['android_version','notification_content','terms_conditions'])->get()->keyBy('setting_key');
        return view('admin.application.application',['_pageTitle'=>'App Settings','url'=> $this->saveurl,'fields'=>[],'settings'=>$settings]);
    }

    public function saveSettings(Request $request)
    {
        foreach ($request->except('_token') as $key=>$value) {
            DB::table("app_settings")->where("setting_key",$key)->update(['value'=>$value]);
        }
        return redirect()->back();
    }

}