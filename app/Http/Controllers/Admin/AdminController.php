<?php

namespace App\Http\Controllers\Admin;
use App\Events\RegisterUser;
use App\Models\Admin\AdminMenu;
use App\Models\StandardMenu;
use App\Models\Admin\AdminMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class AdminController extends Controller
{

    // The Model To Work With
    protected $model;

    // Primary Key
    protected $_pk;

    // Human Name
    protected $humanName;

    // Fields to view in grid
    protected $fields = array();

    // Fields to view in grid
    protected $gridFields = array();

    // Fields to view in grid
    protected $gridFieldsName = array();

    //Grid and record Buttons
    protected $buts;

    // Index view file
    protected $indexViewFile ; // MH

    // is a Grid view
    protected $isGrid = true; // MH

    // grid View
    protected $gridView;

    //Buttons per record
    protected $gridButs; // MH

    //Grid only buttons
    protected $recordButs; // MH

    protected $routeParams; // MH

    //Custom JS
    protected $customJS; // MH

    // The View Folder
    protected $views;

    // Main Part of menu url
    protected $menuUrl;

    // Edit Url
    protected $editurl;

    // New Url
    protected $newurl;

    // Delete Url
    protected $deleteurl;

    // Save Url
    protected $saveurl;

    // View Url
    protected $viewurl;

    // Upload Files
    protected $uploadable = false;

    // Deleteable
    protected $deletable;

    // Validate runs?
    protected $validateWithInput = true;

    /*Stop Modifing Here*/

    // the name of current Controller
    protected $controller;

    // URL
    protected $url;

    // Items in grid
    protected $Items;

    // Item in edit
    protected $item;

    // Sort Type
    protected $sortType = 'desc';

    // Sort By
    protected $sortBy='id';

    protected $noOfItems = 20;

    protected $oldItem;

    public $extender = 'main';

    protected $_adminUser=null;
    protected $searchUser=null;
    protected $searchItem=null;

    public function __construct(){
     //$this->checkAdminPermession();
       // parent::__construct();

        //dd(Lang::get('main.GridDeleteConfirmation'));
        if(!request()->ajax()){


            if(isset($this->isGrid))
            {
                if($this->isGrid === true)
                {
                    if ($this->views)
                        $this->views = $this->views."_";
                }
            }



            $this->genders=['Female' => trans('main.Female'),'Male' => trans('main.Male')];
            $this->periods = ['Summery' => trans('main.Summery'),'Wintry' => trans('main.Wintry'),'All Year' => trans('main.All Year')];
            $this->merchantID = env('merchantID');
            $this->merchantUserName = env('merchantUserName');
            $this->merchantPassword =env('merchantPassword');
            $this->merchantName =env('merchantUserName');
            $this->paymentWebService = env('paymentWebService');

            $this->controller = get_class($this);
            $this->menuUrl  = (!empty($this->menuUrl)) ? env('ADMIN_FOLDER') . '/' .$this->menuUrl : env('ADMIN_FOLDER').'/';
            $this->newurl  = (!empty($this->newurl)) ? url($this->menuUrl.'/'.$this->newurl) : url($this->menuUrl.'/add');
            $this->editurl  = (!empty($this->editurl)) ? url($this->menuUrl.'/'.$this->editurl) : url($this->menuUrl.'/edit');
            $this->deleteurl  = (!empty($this->deleteurl)) ? url($this->menuUrl.'/'.$this->deleteurl) : url($this->menuUrl.'/delete');
            $this->saveurl  = (!empty($this->saveurl)) ? url($this->menuUrl.'/'.$this->saveurl) : url($this->menuUrl.'/save');
            $this->viewurl  = (!empty($this->viewurl)) ? url($this->menuUrl.'/'.$this->viewurl) : url($this->menuUrl.'/view');
            $this->_pk  = (!empty($this->_pk)) ? $this->_pk : 'id';
            $this->sortBy  = (!empty($this->sortBy)) ? $this->sortBy : $this->_pk;


            $replace = [env('ADMIN_FOLDER') .'/','/add','/edit','/delete','/print','/view'];
            $this->_currentLink = str_replace($replace, '',request()->path());


            // Current Link Object
            $this->_currentLinkInfo = AdminMenu::with('parentMenu')->where('menuLink',$this->_currentLink)->first();

            if (isset($this->_currentLinkInfo->id) && $this->_currentLinkInfo->menu_id != 0) {
                $this->_currentLinkInfo->parent1 = $this->_currentLinkInfo->parentMenu;
                if (isset($this->_currentLinkInfo->parent1->menuID) && $this->_currentLinkInfo->parent1->menu_id != 0) {
                    $this->_currentLinkInfo->parent2 = $this->_currentLinkInfo->parent1->parentMenu;
                }
            }



            // Admin Menu
            $this->_menus = AdminMenu::with('activemenus')->where('menu_id',0)->where('visible',1)->orderBy('ordering','ASC')->get();




            // Admin Language
            //  $this->_adminLang = (LANG_SHORT == 'en') ? 'english' : 'arabic';






            view()->share(['_langShort'=>config('settings.defaultLang.value'),'_setting'=>config('settings'),'appUrl'=>env('APP_URL'),'genders'=>$this->genders,'genders_select'=> [trans('main.Gender')]+$this->genders,'periods'=>$this->periods,'periods_select'=>[trans('main.Period')] + $this->periods,'newurl'=>$this->newurl,'editurl'=>$this->editurl,'noOfItems'=>$this->noOfItems,'viewurl'=>$this->viewurl,'deleteurl'=>$this->deleteurl,'menuUrl'=>$this->menuUrl,'extender'=>$this->extender,'_pk'=>$this->_pk,'customJS'=>$this->customJS,'_currentLink'=>$this->_currentLink,'_currentLinkInfo'=>$this->_currentLinkInfo,'_menu'=>$this->_menus,]);


            $this->middleware(function ($request, $next) {

                $l=session()->get('lang','english');
                if($l=='english'){
                    $this->_adminLang ='english';
                }elseif ($l=='en'){
                    $this->_adminLang ='english';
                }else{
                    $this->_adminLang ='arabic';
                }


                if(Auth::user() && Auth::user()->isAdmin == 1) {
                    $this->_adminUser = Auth::user();




                    $this->_adminMeta = $this->_adminUser->getAllMeta($this->_adminUser);
                    // $queries = DB::getQueryLog();
                    // print_r($queries);
                    // die();
                    $this->_adminMeta['adminPermission'] = unserialize($this->_adminMeta['adminPermission']);

                    $this->_adminMessages = AdminMessage::where('readed', 0)->get();
                    $this->_adminMessages->count = $this->_adminMessages->count();


                    view()->share(['_adminUser'=>$this->_adminUser,'_adminMeta'=>$this->_adminMeta,'_adminMessages'=>$this->_adminMessages,'_adminLang'=>$this->_adminLang]);
                }
                return $next($request);
            });




            // Admin Auth & Set Admin Data & Meta





        }


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {




        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();


        if(isset($this->isGrid))
        {
            if($this->isGrid == false)
            {

                return View::make($this->views.'.'.$this->indexViewFile);
            }
        }
        return view( 'admin.grid',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);


    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $M = $this->model;
        $this->item = new $M;

        $this->beforSave();

        if (Input::get($this->_pk)) {


            // Update
            $validator = Validator::make(Input::all(), (isset($M::$rules['update'])) ? $M::$rules['update'] : $M::$rules);
            $id = Input::get($this->_pk);

            if (!$id || !is_numeric($id))
                die();

            $this->item = $M::find($id);


            if ($validator->fails()) {
                $messages = $validator->messages();
                return Redirect::to($this->menuUrl . '/edit/'.Input::get($this->_pk) )->withErrors($validator)->withInput();
            }


        } else {

            // New
            $validator = Validator::make(Input::all(), (isset($M::$rules['default'])) ? $M::$rules['default'] : $M::$rules);
            $this->item = new $M;


            if ($validator->fails()) {
                $messages = $validator->messages();
                return Redirect::to($this->menuUrl . '/add/' )->withErrors($validator)->withInput();
            }
           // $this->item->save();
        }

        $many_relations = [];

      if(isset($request->is_weekend_address_same)){
       $this->item->is_weekend_address_same = !empty($request->is_weekend_address_same)?$request->is_weekend_address_same:0;
      }

        foreach ($this->fields as $field) {


            if ($field['type'] == 'many2many')
                $many_relations[] = $field;

            if (isset($field['notExist']))
                continue;
            $val = Input::get($field['name']);







            // Handle Clear Fields / Fields
            if (!empty($val)) {
                if($field['name']=='ordering'){
                    $val=intval($val);

                }

               // $this->item->{$field['name']} = Input::get($field['name']); // Assign Field
                $this->item->{$field['name']} = $val; // Assign Field
            } else if (isset($field['nullable'])) {
                $this->item->{$field['name']} = NULL; // Clear Field
            } else {


                if($field['name']=='package_id'){
                    if($val==null || $val==""){
                        $val=0;
                    }
                }else{
                    $this->item->{$field['name']} = ''; // Clear Field
                }

            }

            // Handle Password
            if($field['type'] == 'password' && !empty($val))
                $this->item->{$field['name']} = Hash::make(Input::get($field['name'])); // Hash Password
            elseif ($field['type'] == 'password' && empty($val))
                unset($this->item->{$field['name']});

            // Remove Time Stamps
            if($field['type'] == 'timestampDisplay')
                unset($this->item->{$field['name']});
            // Remove Time Stamps
            if($field['type'] == 'datestampDisplay')
                unset($this->item->{$field['name']});


            if($field['name']=='ordering' || $field['name']=='confirmed' || $field['name']=='can_see_others' || $field['name']=='menu_id'|| $field['name']=='active' || $field['name']=='internal'||$field['name']=='can_appointment'||$field['name']=='can_see_others' || $field['name']=='priority_in_ordering'){
                $this->item->{$field['name']}=intval( Input::get($field['name']));

            }
            if(array_key_exists('typeCastToInt',$field)){
                $this->item->{$field['name']}=intval( Input::get($field['name']));
            }




        }



        $this->item->save();

        if($this->item instanceof  User){
            $i=Input::get($this->_pk);
            if(!isset($i)){
                event(new RegisterUser($this->item));
            }
        }


        if(!empty($many_relations)) {
            foreach ($many_relations as $many)
            {
                $rm = $many['name'];

                if(Input::get($rm)!=null){
                    if (count(Input::get($rm)) > 0)
                    {
                        $this->item->$rm()->sync(Input::get($rm));
                    }

                }


                if((isset($this->con_delete))&&($many['name']=="meals"))
                {
                    $meal_id = "";
                    foreach($this->item->meals as $values)
                    {
                        if($meal_id=="") $meal_id = $meal_id.$values['id'];
                        else  $meal_id = $meal_id.",".$values['id'];
                    }
                    DB::delete("delete from orders where user_id IN (select id from users where package_id=".$this->item->{$this->_pk}.") and meal_id NOT IN (".$meal_id.")");
                }
                if((isset($this->con_delete))&&($many['name']=="categories"))
                {
                    $cat_id = "";
                    foreach($this->item->categories as $values)
                    {
                        if($cat_id=="") $cat_id = $cat_id.$values['id'];
                        else  $cat_id = $cat_id.",".$values['id'];
                    }
                    DB::delete("delete from orders where user_id IN (select id from users where package_id=".$this->item->{$this->_pk}.") and category_id NOT IN (".$cat_id.")");
                }
            }

        }


        $this->saveOther();


        if(Input::get('save_new'))
            return Redirect::to($this->menuUrl.'/add');
        if(Input::get('save_next'))
            return Redirect::to('/admin/users_days/edit/'.$this->item->{$this->_pk});
        if(Input::get('save_return'))
            return Redirect::to($this->menuUrl.'/edit/'.$this->item->{$this->_pk});
        return Redirect::to( $this->menuUrl);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        dd('dd');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return View::make( 'admin.forms.' . $this->views . 'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( 'url' , $this->saveurl )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

       // $id = Request::segment(4);

        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;

        $this->item = $M::where($this->_pk, $id)->first();

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
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getView($id)
    {


        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;
        $this->item = $M::find($id);

        foreach ($this->fields as $key => $value) {
            if($value['type'] == 'select')
                $this->item->{$value['name']} = $value['data'][$this->item->{$value['name']}];
            if($value['type'] == 'file')
                $this->item->{$value['name']} = '<a href="' . url('media/'.$value['folder'].'/'.$this->item->{$value['name']}) . '" target="_blank">' . trans('main.Download') .'</a>';
            if($value['type'] != 'hidden')
                $this->fields[$key]['type'] = 'div';
        }

        $this->grabOtherTables();



        return View::make( 'admin.forms.' . $this->views . 'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->delete($id);

        return Redirect::to( $this->menuUrl);
    }

    public function getAjax(Request $request)
    {


        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();

        $result=[];
        $record=[];
        foreach ($this->items as $item) {
            $record = array();
            $record['checkboxCol'] = '<input type="checkbox" class="checkboxes" value="'. $item->{$this->_pk} .'"/>';
            $record[$this->_pk] = $item->{$this->_pk};

            foreach ($this->fields as $field) {
                if($field['type'] == 'datestampDisplay' || $field['type'] == 'timestampDisplay') {
                    $type = $field['type'];
                    $temp = json_decode(json_encode($item->{$field['name']}));
                    if(isset($temp->date))
                        ${$field['name']} = (string) json_decode(json_encode($item->{$field['name']}))->date;
                    else
                        ${$field['name']} = (string) $temp;
                    if($type == 'datestampDisplay')
                        ${$field['name']} = date('Y-m-d',strtotime(${$field['name']}));
                    else if($type == 'timestampDisplay')
                        ${$field['name']} = date('Y-m-d H:i',strtotime(${$field['name']}));
                    $record[$field['name']] = ${$field['name']};
                } else if ($field['type'] == 'switcher')

                    $record[$field['name']] = ($item->{$field['name']} == 1) ? trans('main.Yes') :  trans('main.No');

                else
                    $record[$field['name']] = $item->{$field['name']};
            }
            $butsCol = '';

            foreach ($this->buts as $b) {
                if ($b['name'] != 'Add') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';

                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }

            }

            /*
            B MH
            */

            $mainGridButsCol = '';
            foreach ($this->gridButs as $b) {
                $butsCol .= '<button onclick="'.$b['JShndlr'].'('.$item->{$this->_pk}.')" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                $butsCol .= '">'. trans('main.'.$b['name']) .'</button>';
            }

            $recordButsCol = '';
            foreach ($this->recordButs as $b) {
                if ($b['name'] != 'Add' && $b['name'] != 'Menu' && $b['name'] != 'Invoice') {
                    $butsCol .= '<a href="'. url('admin/'.$this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<a href="./orders/view/'.$item->{$this->_pk}.'" class="btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>';
                }
                if ($b['name'] == 'Invoice') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="'.$item->{$this->_pk}.'"><input type="hidden"  id="userid-val" name="userid-val" value="'.$item->{$this->_pk}.'"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }
            }
            /*
            E MH
            */
            $record['recordButsCol'] = $recordButsCol; // MH
            $record['mainGridButsCol'] = $mainGridButsCol; //MH
            $record['butsCol'] = $butsCol;

            $result[]=$record;
        }

        return response()->json(['data'=>$result]);
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)->get();
    }
    public function postSaveReturn()
    {
        $this->store();

        return Redirect::to($this->menuUrl.'/edit/'.$this->item->_pk);
    }
    public function postSaveNew()
    {
        $this->store();

        return Redirect::to($this->menuUrl.'/add');
    }
    public function grabOtherTables(){}
    public function postDelete(Request $request)
    {
        $ids = explode(',', $request->id);
        return $this->delete($ids);
    }
    public function getDeletehome($id)
    {
        $this->delete($id);

        return Redirect::to(ADMIN_FOLDER);
    }
    public function getActive($id)
    {
        $M = $this->model;
        $item = $M::find($id);
        $item->publish = 1;

        $item->save();

        return Redirect::to(ADMIN_FOLDER);
    }

    public function getAdd_menu_to_admin($value='')
    {
        $usermeta = UserMeta::where('metaKey','adminPermission')->where('userID',1)->first();
        $allMenu = AdminMenu::all();
        $arr = array();
        foreach ($allMenu as $me) {
            $arr[$me->menuID] = array('add','edit','view','export','delete');
        }
        $arr = serialize($arr);

        $usermeta->metaValue = $arr;
        $usermeta->save();
    }
    public function getData($value='')
    {
        // Fetch Inputs
        $page = Input::has('page') ? Input::get('page') : 1;
        $numberPerPage = Input::get('rp');
        $sortName = Input::get('sortname');
        $sortOrder = Input::get('sortorder');
        $query = Input::get('query');
        $qtype = Input::get('qtype');
        $fields = Input::get('fields');
        $where = Input::get('where');

        // Set Sorting Defaults
        if (!$sortName) $sortName = $this->_pk;
        if (!$sortOrder) $sortOrder = 'DESC';

        // Set Paging Defaults
        if (!$page) $page = 1;
        if (!$numberPerPage) $numberPerPage = 15;

        // Skip and Take Vars
        $take = $numberPerPage;
        $skip = ($page-1)*$take;

        // Assign Model
        $M = $this->model;

        // Get Records Cases
        // if ($qtype && $where) {
        // 	$this->items = $M::where()
        // }
        $this->items = $M::where($this->_pk,'!=',0);

        $this->items->where($qtype , 'LIKE' , '%'.$query.'%');


        // Count Found Items , have to be before take and skip
        $count = $this->items->count();

        // Paging
        $this->items->take($take);
        $this->items->skip($skip);

        // Sorting
        $this->items->orderBy($sortName, $sortOrder);

        /*Debug it*/
        // echo $this->items->toSql();

        // Get the collection
        $this->items = $this->items->get();

        $this->items = $this->filterGrid();

        // $queries = DB::getQueryLog();
        // print_r($queries);

        $obj = array();
        $obj['total'] = $count;
        $obj['page'] = $page;
        $obj['rows'] = array();

        foreach ($this->items as $item) {
            $arr = array();
            $arr['id'] = $item->{$this->_pk};
            $arr['cell'][] = $item->{$this->_pk};
            foreach ($this->fields as $field) {
                if(isset($field['grid'])) {
                    if ($field['type'] == 'timestampDisplay')
                        $arr['cell'][] = date('Y-m-d H:i:s',strtotime($item->{$field['name']}));
                    if ($field['type'] == 'datestampDisplay')
                        $arr['cell'][] = date('Y-m-d',strtotime($item->{$field['name']}));
                    else
                        $arr['cell'][] = $item->{$field['name']};
                }
            }

            $obj['rows'][] = $arr;
        }
        return json_encode($obj);
    }
    public function filterGrid()
    {
        return $this->items;
    }

    public function getGridView()
    {
        echo 'DIE '.$this->gridView;

    }

    public function saveOther()
    {
    }
    public function beforSave()
    {
    }


    protected function checkAdminPermession()
    {
        $menuUrlID = AdminMenu::where('menuLink',$this->menuUrl)->first();

        if (!isset($menuUrlID->id))
            return;
        $adminPermission = (Auth::user()) ? unserialize(Auth::user()->getMeta(Auth::user(),'adminPermission')) : array();

        if(isset($adminPermission[$menuUrlID->id])) {
            $butsToRemove = array_diff(array_keys($this->buts),$adminPermission[$menuUrlID->id]);
            if($butsToRemove) {
                foreach ($butsToRemove as $b) {
                    unset($this->buts[$b]);
                }
            }
        }
    }
    protected function delete($ids)
    {
        $M = $this->model;
        $res = $M::destroy($ids);
        if($res)
            return 'true';
        else
            return 'false';
    }

    public function sendFcmNotification($titleAr,$titleEn,$contentAr,$contentEn,$users)
    {

        $arrayToken=[];
        if($users->count()>=1){
                foreach ($users as $item) {
                    \DB::table('notifications')->insert(['user_id'=>$item->id,'titleEn'=>$titleEn,'contentEn'=>$contentEn,'titleAr'=>$titleAr,'contentAr'=>$contentAr]);
                    array_push($arrayToken,$item->deviceToken);
                }
        }
        if(count($arrayToken)>=1){
            $splitedArray = array_chunk($arrayToken,1000);
            foreach($splitedArray as $v){
                if(!empty($v)){

                $url = "https://fcm.googleapis.com/fcm/send";
                $serverKey =env('SERVER_KEY');
                $notification = array('title' =>$titleEn ,'body' =>$contentEn, 'text' =>$contentEn, 'sound' => 'default', 'badge' => '1','Notifications_type'=>'regular','data'=>['notify_type'=>'regular']);
                $arrayToSend = array('registration_ids' =>$v,'notify_type'=>'regular','notification' => $notification,'priority'=>'high','data'=>['notify_type'=>'regular']);
                $json = json_encode($arrayToSend);
                //echo $json;exit;
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='.$serverKey;
                $headers[] = 'Notifications_type=regular';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                //Send the request
                $response = curl_exec($ch);
                //Close request
                 curl_close($ch);



                }
            }
          return true;

        }
        return false;
    }

    //single
    public function sendFcmNotificationSingle($titleAr,$titleEn,$contentAr,$contentEn,$user)
    {


        $arrayToken=[];
        if(!empty($user->id)){
            \DB::table('notifications')->insert(['user_id'=>$user->id,'titleEn'=>$titleEn,'contentEn'=>$contentEn,'titleAr'=>$titleAr,'contentAr'=>$contentAr]);
             array_push($arrayToken,$user->deviceToken);

        }
        if(count($arrayToken)>=1){
            foreach ($arrayToken as $item) {
                $url = "https://fcm.googleapis.com/fcm/send";
                $serverKey =env('SERVER_KEY');
                $notification = array('title' =>$titleEn,'body' =>$contentEn , 'text' =>$contentEn, 'sound' => 'default', 'badge' => '1','Notifications_type'=>'regular','data'=>['notify_type'=>'regular']);
                $arrayToSend = array('to' =>$item,'notify_type'=>'regular','notification' => $notification,'priority'=>'high','data'=>['notify_type'=>'regular']);
                $json = json_encode($arrayToSend);
                $headers = array();
                $headers[] = 'Content-Type: application/json';
                $headers[] = 'Authorization: key='.$serverKey;
                $headers[] = 'Notifications_type=regular';
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
                //Send the request
                $response = curl_exec($ch);
                //Close request
                 curl_close($ch);
                 //dd($response);


            }
          return true;
        }
        return false;
    }

}
