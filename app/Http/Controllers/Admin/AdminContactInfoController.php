<?php


namespace App\Http\Controllers\Admin;
use App\Models\Frontend\About;
use App\Models\Frontend\Contact;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AdminContactInfoController extends AdminController
{

    public function __construct()

    {

        // The Model To Work With
        $this->model = Contact::class;



        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url
        $this->menuUrl = 'site-page/contact-info';



        // Human Name

        $this->humanName = 'الاعدادات';



        // Fields for this table

        $this->fields[] = ['title' => trans('main.Email'), 'name' => 'email','type' => 'text','col' => 2];
        $this->fields[] = ['title' => trans('main.Phone'), 'name' => 'telephone','type' => 'text','col' => 2];
        $this->fields[] = ['title' => trans('main.Mobile'), 'name' => 'mobile','type' => 'text','col' => 2];
        $this->fields[] = ['title' => trans('main.Fax'), 'name' => 'fax','type' => 'text','col' => 2];
//        $this->fields[] = ['title' => trans('main.Lat'), 'name' => 'lat','type' => 'text','col' => 2];
//        $this->fields[] = ['title' => trans('main.Long'), 'name' => 'lang','type' => 'text','col' => 2];
        $this->fields[] = ['title' => trans('main.Address'), 'name' => 'address' ,'type' => 'textarea', 'size' => 1,'col' => 2];
        $this->fields[] = ['title' => trans('main.Map Address'), 'name' => 'map' ,'type' => 'textarea', 'size' => 1,'col' => 2];




        // Grid Buttons

        // $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');

        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');

        // $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');



        // The View Folder

        // $this->views = 'users';



        // Deleteable

        // $this->deletable = true;

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";


        parent::__construct();


    }
    public function getEdit($id)

    {


        $M = $this->model;
        $this->item = $M::first();

        //dd($this->item->meals->pluck('id')->toArray());

        $this->grabOtherTables();
        return View::make( 'admin.forms.' . $this->views . 'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );

    }
    public function store(Request $request)
    {
        $validator = Validator::make(Input::all(),Contact::$rules);
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to($this->menuUrl . '/edit/'.Input::get($this->_pk) )->withErrors($validator)->withInput();
        }

            $item= Contact::first();
            if(is_null($item)){
                $item=new Contact();
            }

            $item->email=$request->email;
            $item->mobile=$request->mobile;
            $item->telephone=$request->telephone;
            $item->fax=$request->fax;
            $item->email=$request->email;
            $item->lat=$request->lat;
            $item->lang=$request->lang;
            $item->address=$request->address;
            $item->save();
        return Redirect::to($this->menuUrl.'/edit/1');

    }

}