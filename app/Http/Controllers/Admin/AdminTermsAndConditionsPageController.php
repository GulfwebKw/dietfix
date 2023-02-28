<?php


namespace App\Http\Controllers\Admin;
use App\Models\Frontend\About;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use stdClass;

class AdminTermsAndConditionsPageController extends AdminController
{
    public function __construct()

    {

        // The Model To Work With

        $this->model = About::class;



        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'site-page/terms-and-conditions';



        // Human Name

        $this->humanName = 'الاعدادات';



        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Name'), 'name' => 'titleAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.English Name'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Arabic Content'), 'name' => 'contentAr','type' => 'wysiwyg');
        $this->fields[] = array('title' => trans('main.English Content'), 'name' => 'contentEn','type' => 'wysiwyg');



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
    protected function checkAdminPermession()

    {



        $adminPermission = (Auth::user()) ? unserialize(Auth::user()->getMeta(Auth::user(),'adminPermission')) : array();



        if(isset($adminPermission[2])) {

            $butsToRemove = array_diff(array_keys($this->buts),$adminPermission[2]);

            if($butsToRemove) {

                foreach ($butsToRemove as $b) {

                    unset($this->buts[$b]);

                }

            }

        }

    }
    public function getEdit($id)

    {


        $M = $this->model;
        $this->item = $M::where('page_name','TAC')->first();

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

        $item= About::where('page_name','TAC')->first();
        $item->titleAr=$request->titleAr;
        $item->titleEn=$request->titleEn;
        $item->contentAr=$request->contentAr;
        $item->contentEn=$request->contentEn;
        $item->save();
        return Redirect::to($this->menuUrl.'/edit/1');

    }
}
