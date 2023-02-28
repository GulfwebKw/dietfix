<?php


namespace App\Http\Controllers\Admin;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DiscountController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = Discount::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'discount';

        // Human Name
        $this->humanName = "Discount";

        $this->con_delete = 1;


        // Fields for this table
        $this->fields[] = array('title' => trans('main.Arabic Title'), 'name' => 'titleAr', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => trans('main.English Title'), 'name' => 'titleEn', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "Discount Code", 'name' => 'code', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "Value", 'name' => 'value', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "Limitation", 'name' => 'count_limit', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "User Limitation", 'name' => 'count_limit_user', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "Type", 'name' => 'type' , 'data' => [['id'=>"Percent","title"=>"Percent"],["id"=>"KD","title"=>"KD"]],'type' => 'select', 'col' => 2,'valOptions'=>'id','keyOptionsSelect'=>'title');
        $this->fields[] = array('title' => "Start", 'name' => 'start', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);
        $this->fields[] = array('title' => "End", 'name' => 'end', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'text', 'col' => 2);

        $this->fields[] = array('title' => trans('main.Active'), 'name' => 'active' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true, 'col' => 2);


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

        parent::__construct();

    }



    public function store(Request $request)
    {


        $M = $this->model;

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
                if($field['name']=='price_after_discount'){
                    if(isset($val)){
                        $val=floatval($val);
                    }else{
                        $val=0;
                    }


                }
                // $this->item->{$field['name']} = Input::get($field['name']); // Assign Field
                $this->item->{$field['name']} = $val; // Assign Field
            } else if (isset($field['nullable'])) {
                $this->item->{$field['name']} = NULL; // Clear Field


            } else {
                if($field['name']=='price_after_discount'){
                    $this->item->{$field['name']} = 0; // Clear Field
                }else{
                    if($field['name']=='package_id'){
                        if($val==null || $val==""){
                            $val=0;
                        }
                    }else{
                        $this->item->{$field['name']} = ''; // Clear Field
                    }

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


            if($field['name']=='ordering' || $field['name']=='confirmed' || $field['name']=='can_see_others' || $field['name']=='menu_id'|| $field['name']=='active' || $field['name']=='internal'||$field['name']=='can_appointment'||$field['name']=='can_see_others'){
                $this->item->{$field['name']}=intval( Input::get($field['name']));

            }
            if(array_key_exists('typeCastToInt',$field)){
                $this->item->{$field['name']}=intval( Input::get($field['name']));
            }




        }


        $this->item->save();



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
        if(Input::get('save_return'))
            return Redirect::to($this->menuUrl.'/edit/'.$this->item->{$this->_pk});
        return Redirect::to( $this->menuUrl);
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


        if(isset($this->isGrid))
        {
            if($this->isGrid == false)
            {

                return View::make($this->views.'.'.$this->indexViewFile);
            }
        }

        return view( 'admin.discount',['_pageTitle'=>$this->humanName,'items'=>$this->items,'url'=>$this->url,'fields'=>$this->fields,'gridFields'=>$this->gridFields,'gridFieldsName'=>$this->gridFieldsName,'buts'=>$this->buts,'sortType'=>$this->sortType,'sortBy'=>$this->sortBy,'customJS'=> $this->customJS]);

    }
    public function create()
    {
        $this->grabOtherTables();
        return View::make('admin.discount.add')
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'uploadable' , $this->uploadable )
            ->with('fields',[]);

    }

    public function edit($id)
    {

        // $id = Request::segment(4);


        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;

        $this->item = $M::where($this->_pk, $id)->first();

        $this->grabOtherTables();
        return View::make('admin.discount.edit')
            ->with( '_pageTitle' , trans('main.Edit') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'item' , $this->item )
            ->with('fields',[]);
    }



    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::orderBy($this->sortBy, $this->sortType)->get();
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
                if($field['name']=='Package')
                    dd('pppp');

                else   if($field['type'] == 'datestampDisplay' || $field['type'] == 'timestampDisplay') {
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

//    public function loadData()
//    {
//        $M = $this->model;
//        $this->items = $M::orderBy($this->sortBy, $this->sortType)
//            ->get();
//    }
}
