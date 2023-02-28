<?php

namespace App\Http\Controllers\Admin;
use App\Models\Clinic\Appointment;
use App\Models\Clinic\AppointmentFile;
use App\Models\Clinic\Clinic;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;

class AdminAppointmentController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = Appointment::class;


        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'appointments';

        // Human Name
        $this->humanName = trans('main.Appointments');

        $this->clinics = Clinic::where('can_appointment',1)->where('active',1)->select(['title'.LANG,'id'])->get();
        $this->users = User::whereIn('role_id',[1])->orderBy('username')->select(['username','id'])->get();
        $this->doctors = User::whereIn('role_id',[2])->orderBy('username')->select(['username','id'])->get();

        // Fields for this table
        $this->fields[] = array('title' => trans('main.User'), 'name' => 'user_id' ,'type' => 'select', 'searched' => true, 'width' => 10, 'grid' => true, 'col' => 2, 'data' => $this->users,'keyOptionsSelect'=>'username','valOptions'=>'id');
        $this->fields[] = array('title' => trans('main.Clinic'), 'name' => 'clinic_id' ,'type' => 'select', 'searched' => true, 'width' => 10, 'grid' => true, 'col' => 2, 'data' => $this->clinics,'keyOptionsSelect'=>'title'.LANG,'valOptions'=>'id');


        $this->fields[] = array('title' => trans('main.Doctor'), 'name' => 'doctor_holder' ,'type' => 'hidden','notExist' => true, 'searched' => true, 'width' => 10, 'grid' => true, 'data' => $this->doctors);
        $this->fields[] = array('title' => trans('main.Date'), 'name' => 'date_holder', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'hidden','notExist' => true, 'col' => 2);
        $this->fields[] = array('title' => trans('main.Time'), 'name' => 'hour_holder', 'searched' => true, 'width' => 10, 'grid' => true ,'type' => 'hidden','notExist' => true, 'col' => 2);

        $this->fields[] = array('title' => trans('main.Notes'), 'name' => 'notes', 'searched' => true, 'width' => 20, 'grid' => true ,'type' => 'textarea');

        $this->fields[] = array('title' => trans('main.Attended?'), 'name' => 'confirmed' ,'type' => 'switcher', 'searched' => true, 'width' => 5, 'grid' => true);

        // $this->fields[] = array('title' => trans('main.Activities'), 'name' => 'activities' ,'type' => 'many2many','parent_title' => 'title'.LANG, 'data' => Activity::where('active',1)->get(),'notExist' => true);
        $this->fields[] = array('title' => trans('main.Files'), 'name' => 'filess' ,'type' => 'file','ext' => 'doc,docx,xls,xlsx,pdf,png,jpg,gif,zip,rar,jpeg,bmp','multi' => true, 'photo' => false, 'folder' => 'files','notExist' =>true);

        $this->fields[] = array('title' => trans('main.Created Date'), 'name' => 'created_at', 'type' => 'timestampDisplay', 'col' => 2);
        $this->fields[] = array('title' => trans('main.Updated Date'), 'name' => 'updated_at', 'type' => 'timestampDisplay', 'col' => 2);



        // Grid Buttons
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'red');

        View::share('doctors',$this->doctors);
        // The View Folder
        $this->views = 'appointments';

        // Deleteable
        $this->deletable = true;


        // Upload Files
        // protected $uploadable = true;


        // Sort Type
        $this->sortType = 'desc';

        // Sort By
        $this->sortBy = 'id';


        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";

        parent::__construct();
    }

    public function beforSave()
    {
        $this->fields[] = array('title' => trans('main.Doctor'), 'name' => 'doctor_id' ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Date'), 'name' => 'date' ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Time'), 'name' => 'hour' ,'type' => 'text');
    }
    public function grabOtherTables()
    {
        $filesObj = AppointmentFile::select('file')->where('appointment_id',$this->item->id)->get();
        $files = array();
        foreach ($filesObj as $file) {
            $files[] = $file['file'];
        }
        $this->item->filess = $files;
    }

    public function saveOther()
    {
        $id = false;
        // Update
        if (Input::get($this->_pk)) {
            AppointmentFile::where('appointment_id',Input::get($this->_pk))->delete();
            $id = Input::get($this->_pk);
        }


        if(Input::has('filess')&& \request()->filess!=null) {
            foreach (Input::get('filess') as $file) {
                $apFile = new AppointmentFile();
                $apFile->appointment_id = ($id) ? $id : $this->item->{$this->_pk};
                $apFile->file = $file;
                $apFile->save();
            }
        }
    }
    public function loadData()
    {
        $M = $this->model;
        $this->items = $M::with('user')->with('clinic')->with('doctor')->orderBy($this->sortBy, $this->sortType)->get();
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->user_id= $item->user->username;
            $newItems[$k]->doctor_holder= $item->doctor->username;
            $newItems[$k]->date_holder= $item->date;
            $newItems[$k]->hour_holder= $item->hour;
            $newItems[$k]->clinic_id= optional($item->clinic)->{'title'.LANG};
        }
        return $newItems;
    }


}
