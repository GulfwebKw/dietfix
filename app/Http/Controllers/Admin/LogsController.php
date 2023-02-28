<?php


namespace App\Http\Controllers\Admin;
 use App\Models\Logs;
 use App\User;

 class LogsController extends AdminController
{

     public $userId;
     public function __construct()
     {
         $this->model = Logs::class;

         // Primary Key
         $this->_pk = 'id';
         $this->humanName = 'logs';
         $this->menuUrl = 'logs';

         // $provinces = DB::table('provinces');
         // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
         // $cities = DB::table('cities');
         // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

         // Fields for this table
         // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
         //$this->fields[] = ['title'=>trans('main.Username'), 'name'=> 'name', 'width'=>25, 'grid'=>true, 'type'=>'text', 'col'=>1];


         // Grid Buttons
         //$usersArr = DB::select('SELECT id AS userid, username FROM users WHERE role_id = 1');
         //$this->items = $usersArr;
         // print_r(AdminController::$_adminMeta);
         //$this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
         //$this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
         //$this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
         $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');

         $this->gridView = 'invoice.select_date_invoice';
         // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

         //$this->gridButs['export'] = ['name' =>'Renew/Add membership','icon' => 'print', 'color' => 'green', 'JShndlr'=>"showRenewPopup"];
         //$this->recordButs['Add to pdf test'] = ['name'=>'Add pdf', 'icon'=>'', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only
         $this->gridButs = [];
         $this->recordButs = [];

         $this->routeParams = [];
         $this->customJS = "";
         $this->indexViewFile = 'select_date_invoice';
         // The View Folder
         $this->views = 'logs';
         $this->isGrid = false;
         parent::__construct();
     }
     public function index()
     {
         $logs=   Logs::with(['admin','user'])->orderBy('id','desc')->paginate(20);
         return view( 'admin.log.log',['_pageTitle'=>$this->humanName,'items'=>[],'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'logs'=>$logs]);

     }

}