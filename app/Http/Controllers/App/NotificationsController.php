<?php


namespace App\Http\Controllers\App;
use App\Models\Frontend\About;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use App\Http\Controllers\Admin\AdminController;
use stdClass;

class NotificationsController extends AdminController
{
    public function __construct()

    {

        // The Model To Work With

        $this->model = About::class;

        $this->users = User::where('role_id',1)->select(['deviceType','deviceToken','username','mobile_number', 'id'])->whereNotNull('deviceToken')->get();
        $this->sendto = ['All' => trans('Send to all'),'Single' => trans('Single')];
        // Primary Key

        $this->_pk = 'id';



        // Main Part of menu url

        $this->menuUrl = 'app/notifications';



        // Human Name

        $this->humanName = 'الاعدادات';



        // Fields for this table
        $this->fields[] = array('title' => trans('Send To'), 'name' => 'sendto','type' => 'select', 'data' => $this->sendto, 'value' => 'All', 'col' => 2,'valOptions'=>'otherType');
        $this->fields[] = array('title' => trans('User'), 'name' => 'user_id', 'data' => $this->users,'type' => 'select','col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'username');
        $this->fields[] = array('title' => trans('main.Title Arabic'), 'name' => 'titleAr', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Title English'), 'name' => 'titleEn', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Arabic Content'), 'name' => 'contentAr','type' => 'textarea');
        $this->fields[] = array('title' => trans('main.English Content'), 'name' => 'contentEn','type' => 'textarea');


        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";


        parent::__construct();


    }

    public function index()
    {

        return View::make( 'admin.forms.formNotifications' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( 'url' , $this->saveurl )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
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
        $this->item = $M::where('page_name','about')->first();

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

        $request->validate([
            'titleEn' => 'required',
            'titleAr' => 'required',
            'contentAr' => 'required',
            'contentEn' => 'required',
        ]);
        
        if($request->sendto=='All'){
        $users = User::where('deviceToken',"!=",null)->where('deviceToken',"!=","")->select(['id','deviceToken'])->get();
        $res   = $this->sendFcmNotification($request->titleAr,$request->titleEn,$request->contentAr,$request->contentEn,$users);
        if($res){
            session()->flash('message','send notification successfully');
            session()->flash('status','success');
        }else{
            session()->flash('message',' return error firebase server');
            session()->flash('status','danger');
        }
        }else{
        $users = User::where('id',$request->user_id)->first();
      
        if(!empty($users->deviceToken)){
            $res   = $this->sendFcmNotificationSingle($request->titleAr,$request->titleEn,$request->contentAr,$request->contentEn,$users);
            if($res){
                session()->flash('message','send notification successfully');
                session()->flash('status','success');
            }else{
                session()->flash('message',' return error firebase server');
                session()->flash('status','danger');
            }   
        }
        }
        return Redirect::to($this->menuUrl);

    }
}