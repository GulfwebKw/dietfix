<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 11:26 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\Models\Invoice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use PDF;
use PDF2;

class InvoicesController extends AdminController
{

    public $userId;
    public function __construct()
    {
        $this->model = User::class;

        // Primary Key
        $this->_pk = 'id';
        $this->humanName = '';
        $this->menuUrl = 'invoices';

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
        $this->views = 'invoice';
        $this->isGrid = false;
        parent::__construct();
    }
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
        $invoice=Invoice::all();
        return view( 'admin.invoices.invoices',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'invoice'=>$invoice]);

    }

    public function add()
    {
        $package=Package::all();
        $packageDuration=PackageDurations::all();
        $users=User::where('role_id',1)->get();
        return \view('admin.invoices.add',['packages'=>$package,'packageDuration'=>$packageDuration,'_pageTitle'=>$this->humanName,'items'=>[],'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS,'users'=>$users]);

    }

    public function print($id)
    {




        $invoice=Invoice::find($id);
        $html=view('admin.invoices.mainPrint',compact('invoice'))->render();

        $pdf = PDF2::loadHTML($html,$invoice);
        return $pdf->download('invoice-'.$invoice->unique_id.'_'.$invoice->created_at.'.pdf');




        $pdf = new PDF();
        $pdf::SetTitle('Invoice Dietfix');
        $pdf::AddPage();
        $pdf::writeHTML($html, false, false, true, false, '');
       return $pdf::Output('invoice-'.$invoice->unique_id.'_'.$invoice->created_at.'.pdf');

        //$pdf = PDF::writeHTML();
       // return $pdf->download('invoice-'.$invoice->unique_id.'_'.$invoice->created_at.'.pdf');

    }


    public function save(Request $request)
    {
        $validator = Validator::make(Input::all(),['user'=>"required|numeric|min:1","package"=>"required|numeric|min:1","package_duration"=>"required|numeric|min:1","count"=>"required|numeric|min:1"]);
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator->messages())->withInput();
        }

        $packageDuration=PackageDurations::whereId($request->package_duration)->first();
        $sum=$packageDuration->price*intval($request->count);

        $user=Invoice::create(['unique_id'=>time(),'user_id'=>$request->user,'package_id'=>$request->package,'package_duration_id'=>$request->package_duration,'count'=>$request->count,'sum'=>$sum,'description'=>$request->description]);



        session()->flash('message',"SuccessFully  invoice");
        session()->flash('status','success');

        return Redirect::back();




    }

    public function getGridView()
    {

        $this->userId = trim(request()->userid);
        //echo Request::get("userid");
        $this->buttons[] = ['title'=>trans('main.Print Invoice'), 'name'=>"PrintInvoice",'color'=>'blue', 'icon'=>"", "JShndlr"=>"showPrintInvoice"];

        $this->buts = [];

        $this->fields[] = ['title' => trans('main.ID'), 'name' => 'ID','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 1];
        $this->fields[] = ['title' => trans('main.Username'), 'name' => 'Username','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 1];
        $this->fields[] = ['title' => trans('main.Renewed at'), 'name' => 'Renewed at','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2];


        foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->items = [];
        $itemsData = $this->loadData();
        //count total invoice
        $totalinvoice = count($itemsData);

        foreach ($itemsData as $key => $value) {
            //var_dump($value->ID);
            array_push($this->items,array($value->ID, $value->username, $value->renewed_at));
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        //$this->i += 1;
        return View::make( 'invoice.invoice_report' )
            ->with( '_pageTitle' , $this->humanName )
            ->with( 'fields' , $this->fields )
            ->with( 'gridFields' , $this->gridFields )
            ->with( 'buttons' , $this->buttons )
            ->with( 'url' , $this->url )
            ->with('userId', $this->userId)
            ->with('items', json_encode($this->items))
            ->with( 'totalInvoice' , $totalinvoice )
            ->with( 'gridFieldsName' , $this->gridFieldsName );

    }

    public function getUsers()
    {
        $usersArr = DB::select("SELECT id AS userid, username FROM users WHERE role_id = 1 order by username");
        if(empty($usersArr))
        {
            echo json_encode(["result"=>"FAILURE"]);
        }
        echo json_encode(["result"=>"SUCCESS", "data"=>$usersArr]);
    }

    public function getInvoiceData(Request $request)
    {
        $userId = $request->userid;
        $membershipId =  $request->membership_id;
        $membershipInvoiceData = DB::select("SELECT customer_memberships.id AS ID, customer_memberships.days_left AS days_left,users.username AS username, customer_memberships.created_at AS renewed_at,packages.titleAr AS packageAr, packages.titleEn AS packageEn, packages.price AS price 
FROM customer_memberships 
INNER JOIN packages ON customer_memberships.package_id = packages.id 
INNER JOIN users ON customer_memberships.user_id = users.id WHERE user_id = ? AND customer_memberships.id = ?", array($userId, $membershipId));
        if(empty($membershipInvoiceData))
        {
            echo json_encode(["result"=>"FAILURE"]);
            return;
        }
        echo json_encode(["result"=>"SUCCESS", "data"=>$membershipInvoiceData]);
    }

    /*public function getAjax()
    {
    //echo $this->userId;
//echo $this->i;
    foreach ($this->fields as $f) {
            if (isset($f['grid'])){
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = Request::path();



        $ajaxResponse = new stdClass;
        $ajaxResponse->data = array();
        //echo json_encode($this->items);
        echo $this->userId;
        foreach ($this->items as $item) {
            $record = array();
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
            foreach ($this->buttons as $b) {
                $butsCol .= "<button onclicn='".$b["JShndlr"]."(".$item->{$this->_pk}.")'>".trans('main.Print Invoice')."</button>";
            }

            /*
            B MH


            $mainGridButsCol = '';
            foreach ($this->gridButs as $b) {
                    $butsCol .= '<button onclick="'.$b['JShndlr'].'('.$item->{$this->_pk}.')" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '">'. trans('main.'.$b['name']) .'</button>';
            }

            $recordButsCol = '';
            foreach ($this->recordButs as $b) {
                if ($b['name'] != 'Add') {
                    $butsCol .= '<a href="'. url($this->menuUrl.'/'.strtolower($b['name']) . '/' . $item->{$this->_pk}) .'" data-id="'. $item->{$this->_pk} .'" class="nwrap btn btn-xs '. $b['color'] .' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-'. $b['icon'] .'"></i> '. trans('main.'.$b['name']) .'</a>';
                }
            }

            E MH

            //$record['recordButsCol'] = $recordButsCol; // MH
            //$record['mainGridButsCol'] = $mainGridButsCol; //MH
            $record['butsCol'] = $butsCol;
            $ajaxResponse->data[] = $record;
        }
        return json_encode($ajaxResponse);
    }*/

    public function filterGrid()
    {
        return ;
    }

    public function loadData()
    {
        $membershipsArr = DB::select("SELECT customer_memberships.id AS ID, users.username AS username, customer_memberships.created_at AS renewed_at FROM customer_memberships 
INNER JOIN users ON customer_memberships.user_id = users.id WHERE user_id = ?", array($this->userId));

        return $membershipsArr;
    }

}