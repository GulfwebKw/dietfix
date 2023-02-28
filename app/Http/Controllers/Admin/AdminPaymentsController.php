<?php

namespace App\Http\Controllers\Admin;

use App\Models\Clinic\Payment;
use Illuminate\Http\Request;

class AdminPaymentsController extends AdminController
{



    public function __construct()
    {


        // The Model To Work With
        $this->model = Payment::class;

        // Primary Key
        $this->_pk = 'id';

        $this->sortBy = "id";

        // Main Part of menu url
        $this->menuUrl = 'payments';

        // Human Name
        $this->humanName = 'الدفعات';

        $this->payment_type = [
            'knet' => trans('main.Knet'),
            'credit_card' => trans('main.Credit Card'),
            'cash_on_delivery' => trans('main.Cash On Delivery'),
        ];

        // Fields for this table
        $this->fields[] = array('title' => trans('main.User'), 'name' => 'user_id', 'searched' => true, 'width' => 40, 'grid' => true, 'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Package'), 'name' => 'package_id', 'searched' => true, 'width' => 50, 'grid' => true, 'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => "Package Duration", 'name' => 'package_duration_id', 'searched' => true, 'width' => 50, 'grid' => true, 'type' => 'div', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Paid'), 'name' => 'paid', 'width' => 3, 'grid' => true, 'type' => 'switcher');
        $this->fields[] = array('title' => trans('main.Payment Type'), 'name' => 'type', 'searched' => true, 'width' => 40, 'grid' => true, 'type' => 'select', 'data' => $this->payment_type, 'col' => 2);
        $this->fields[] = array('title' => trans('main.pay_way_type'), 'name' => 'pay_way_type', 'searched' => true, 'width' => 40, 'grid' => true, 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.total_credit'), 'name' => 'total_credit', 'searched' => true, 'width' => 40, 'grid' => true, 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Total'), 'name' => 'total', 'type' => 'div');
        $this->fields[] = array('title' => trans('main.PaymentID'), 'name' => 'paymentID', 'searched' => true, 'width' => 50, 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Payment Data'), 'name' => 'payment_data', 'type' => 'textarea');

        $this->fields[] = array('title' => trans('main.PaidCurrencyValue'), 'name' => 'PaidCurrencyValue', 'width' => 50, 'grid' => true, 'type' => 'text');
        $this->fields[] = array('title' => 'Discount', 'name' => 'discount', 'width' => 50, 'grid' => true, 'type' => 'text');
        $this->fields[] = array('title' => trans('main.DueValue'), 'name' => 'DueValue', 'width' => 50, 'grid' => true, 'type' => 'text');
        $this->fields[] = array('title' => trans('main.InvoiceDisplayValue'), 'name' => 'InvoiceDisplayValue', 'width' => 50, 'grid' => true, 'type' => 'text');
        $this->fields[] = array('title' => trans('main.PaidCurrency'), 'name' => 'PaidCurrency', 'width' => 50, 'grid' => true, 'type' => 'text');
        $this->fields[] = array('title' => trans('main.Currency'), 'name' => 'Currency', 'width' => 50, 'grid' => true, 'type' => 'text');
        
        $this->fields[] = array('title' => trans('main.Gift'), 'name' => 'gift_id', 'searched' => true, 'width' => 50, 'grid' => true, 'type' => 'div', 'col' => 3);
        
        $this->fields[] = array('title' => trans('main.RefId'), 'name' => 'ref', 'grid' => true, 'width' => 50, 'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.RefId'), 'name' => 'ref_id', 'grid' => true, 'width' => 50, 'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'width' => 10, 'grid' => true, 'type' => 'datestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'width' => 10, 'grid' => true, 'type' => 'datestampDisplay', 'col' => 2);

        // Grid Buttons
        // $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        //        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        
        $this->buts['paid'] = array('name' => 'Paid', 'icon' => 'list', 'color' => 'green');
        $this->buts['unpaid'] = array('name' => 'UnPaid', 'icon' => 'list', 'color' => 'red');
        

        $this->gridButs = [];
        
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    public function indexcustom(Request $request)
    {


 

        foreach ($this->fields as $f) {
            if (isset($f['grid'])) {
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData($request);

        $this->items = $this->filterGrid();


        if (isset($this->isGrid)) {
            if ($this->isGrid == false) {

                return View::make($this->views . '.' . $this->indexViewFile);
            }
        }
        return view('admin.paymentGrid', ['_pageTitle' => $this->humanName, 'items' => $this->items, 'url' => $this->url, 'fields' => $this->fields, 'gridFields' => $this->gridFields, 'gridFieldsName' => $this->gridFieldsName, 'buts' => $this->buts, 'sortType' => $this->sortType, 'sortBy' => $this->sortBy, 'customJS' => $this->customJS]);
    }
    public function index()
    {




        foreach ($this->fields as $f) {
            if (isset($f['grid'])) {
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }

        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData();

        $this->items = $this->filterGrid();


        if (isset($this->isGrid)) {
            if ($this->isGrid == false) {

                return View::make($this->views . '.' . $this->indexViewFile);
            }
        }
        return view('admin.paymentGrid', ['_pageTitle' => $this->humanName, 'items' => $this->items, 'url' => $this->url, 'fields' => $this->fields, 'gridFields' => $this->gridFields, 'gridFieldsName' => $this->gridFieldsName, 'buts' => $this->buts, 'sortType' => $this->sortType, 'sortBy' => $this->sortBy, 'customJS' => $this->customJS]);
    }
    public function filterGrid()
    {

        $newItems = $this->items;

        foreach ($this->items as $k => $item) {
            $newItems[$k]->user_id = ($item->user_id) ? $item->user->email.'<br>'.$item->user->mobile_number : trans('main.Guest');
            if ($item->type != null) {
                if (array_key_exists($item->type, $this->payment_type)) {
                    $newItems[$k]->payment_type = $this->payment_type[$item->type];
                } else {
                    $newItems[$k]->payment_type = "";
                }
            } else {
                $newItems[$k]->payment_type = "";
            }

            $newItems[$k]->package_id = '<a href="' . url(ADMIN_FOLDER . '/packages/edit/' . $item->package_id) . '">' . ($item->package->titleEn??'--') . '</a>';
            $newItems[$k]->gift_id = '<a href="' . url(ADMIN_FOLDER . '/gifts/edit/' . $item->gift_id) . '">' . $item->gift_id . '</a>';
            $newItems[$k]->package_duration_id = '<a href="' . url(ADMIN_FOLDER . '/packages-duration/edit/' . $item->package_duration_id) . '">' . ($item->package_duration_id??'--') . '</a>';
            // $newItems[$k]->paid= ($item->paid) ? trans('main.Yes') : trans('main.No');
        }
        return $newItems;
    }
    public function loadData($request='')
    {
       
        $M = $this->model;
        $this->items = $M::with('user')->with('package');
        if(!empty($request->type) && $request->type=="paid"){
        $this->items = $this->items->where('status',1);
        }else if(!empty($request->type) && $request->type=="unpaid"){
        $this->items = $this->items->where('status','!=',1);   
        }

        //search
        if($this->searchUser!=null){
                $this->items=$this->items->wherehas('user',function($sq){
				 $sq->where('ref_id',"like","%".$this->searchUser."%")
					->orWhere('ref',"like","%".$this->searchUser."%")
					->orWhere("description","like","%".$this->searchUser."%")
                    ->where('username',"like","%".$this->searchUser."%")
					->orWhere('mobile_number',"like","%".$this->searchUser."%")
					->orWhere("email","like","%".$this->searchUser."%");
				});
                /*
                $this->items=$this->items->whereHas('user',function($sq){
                    $sq->where('username',"like","%".$this->searchUser."%")
					->orWhere('mobile_number',"like","%".$this->searchUser."%")
					->orWhere("email","like","%".$this->searchUser."%");
                });*/
            
        }
        $this->items = $this->items->orderBy($this->sortBy, $this->sortType)->paginate(20);
    }
    public function grabOtherTables()
    {
        $this->item->user_id = ($this->item->user_id) ? $this->item->user->email : trans('main.Guest');
    }
    public function getAjax(Request $request)
    {

        $this->searchUser=$request->search['value'];


        foreach ($this->fields as $f) {
            if (isset($f['grid'])) {
                $this->gridFields[] = $f;
                $this->gridFieldsName[] = $f['name'];
            }
        }
        $this->gridFieldsName = implode(',', $this->gridFieldsName);
        $this->url = request()->path();


        $this->loadData($request);

        $this->items = $this->filterGrid();

        $result = [];
        $record = [];
        foreach ($this->items as $item) {
            $record = array();
            $record['checkboxCol'] = '<input type="checkbox" class="checkboxes" value="' . $item->{$this->_pk} . '"/>';
            $record[$this->_pk] = $item->{$this->_pk};

            foreach ($this->fields as $field) {
                if ($field['type'] == 'datestampDisplay' || $field['type'] == 'timestampDisplay') {
                    $type = $field['type'];
                    $temp = json_decode(json_encode($item->{$field['name']}));
                    if (isset($temp->date))
                        ${$field['name']} = (string) json_decode(json_encode($item->{$field['name']}))->date;
                    else
                        ${$field['name']} = (string) $temp;
                    if ($type == 'datestampDisplay')
                        ${$field['name']} = date('Y-m-d', strtotime(${$field['name']}));
                    else if ($type == 'timestampDisplay')
                        ${$field['name']} = date('Y-m-d H:i', strtotime(${$field['name']}));
                    $record[$field['name']] = ${$field['name']};
                } else if ($field['type'] == 'switcher')
                    $record[$field['name']] = ($item->{$field['name']} == 1) ? trans('main.Yes') :  trans('main.No');
                else
                    $record[$field['name']] = $item->{$field['name']};
            }
            $butsCol = '';
           /*
            foreach ($this->buts as $b) {
                if ($b['name'] != 'Add') {
                    $butsCol .= '<a href="' . url('admin/' . $this->menuUrl . '/' . strtolower($b['name']) . '/' . $item->{$this->_pk}) . '" data-id="' . $item->{$this->_pk} . '" class="nwrap btn btn-xs ' . $b['color'] . ' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-' . $b['icon'] . '"></i> ' . trans('main.' . $b['name']) . '</a>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="' . $item->{$this->_pk} . '"><input type="hidden"  id="userid-val" name="userid-val" value="' . $item->{$this->_pk} . '"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }
            }
          */
            /*
            B MH
            */

            $mainGridButsCol = '';
            foreach ($this->gridButs as $b) {
                $butsCol .= '<button onclick="' . $b['JShndlr'] . '(' . $item->{$this->_pk} . ')" data-id="' . $item->{$this->_pk} . '" class="nwrap btn btn-xs ' . $b['color'] . ' btn-block ';
                $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                $butsCol .= '">' . trans('main.' . $b['name']) . '</button>';
            }

            $recordButsCol = '';
            foreach ($this->recordButs as $b) {
                if ($b['name'] != 'Add' && $b['name'] != 'Menu' && $b['name'] != 'Invoice') {
                    $butsCol .= '<a href="' . url('admin/' . $this->menuUrl . '/' . strtolower($b['name']) . '/' . $item->{$this->_pk}) . '" data-id="' . $item->{$this->_pk} . '" class="nwrap btn btn-xs ' . $b['color'] . ' btn-block ';
                    $butsCol .= ($b['name'] == 'Delete') ? 'grid-del-but' : '';
                    $butsCol .= '"><i class="fa fa-' . $b['icon'] . '"></i> ' . trans('main.' . $b['name']) . '</a>';
                }
                if ($b['name'] == 'Menu') {
                    $butsCol .= '<a href="./orders/view/' . $item->{$this->_pk} . '" class="btn btn-xs  btn-success"><i class="fa fa-plus"></i> Menu </a>';
                }
                if ($b['name'] == 'Invoice') {
                    $butsCol .= '<form method="POST" action="/admin/invoices/getinvoice" accept-charset="UTF-8" class="form-inline form-bordered form-row-stripped spaceForm" role="form" enctype="multipart/form-data"><input type="hidden"  id="userid" name="userid" value="' . $item->{$this->_pk} . '"><input type="hidden"  id="userid-val" name="userid-val" value="' . $item->{$this->_pk} . '"><button style="margin-top:3px;" class="btn btn-xs btn-primary" id="submit" type="submit"><i class="fa fa-file-o"></i> Invoice</button></form>';
                }
            }
            /*
            E MH
            */
            $record['recordButsCol'] = $recordButsCol; // MH
            $record['mainGridButsCol'] = $mainGridButsCol; //MH
            $record['butsCol'] = $butsCol;

            $result[] = $record;
        }

        return response()->json(['data' => $result]);
    }
}
