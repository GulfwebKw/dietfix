<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 10:59 AM
 */

namespace App\Http\Controllers\Admin;

use App\Models\LanguagesVars;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class AdminLangController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = LanguagesVars::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'languages';

        // Human Name
        $this->humanName = 'اللغات';

        $langs = DB::table('languages');
        $langs = $langs->select(['langName', 'id'])->get();

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Key'), 'name' => 'key', 'searched' => true, 'width' => 15, 'grid' => true ,'type' => 'text');
        $this->fields[] = array('title' => trans('main.Value'), 'name' => 'value', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'textarea');
        $this->fields[] = array('title' => trans('main.Default'), 'name' => 'defaults', 'searched' => true, 'width' => 25, 'grid' => true ,'type' => 'textarea');
        $this->fields[] = array('title' => trans('main.Language'), 'name' => 'lang_id', 'width' => 15, 'grid' => true ,'keyOptionsSelect'=>'langName' ,'valOptions'=>'id', 'data' => $langs,'type' => 'select');

        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');

        // The View Folder
        // $this->views = 'users';

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        // Deleteable
        $this->deletable = true;

        parent::__construct();
    }

    public function loadData()
    {
        $M = $this->model;
        $M::with('language')->get();
        $this->items = $M::all();
    }

    public function getMass()
    {
        return View::make('admin.forms.mass');
    }

    public function postMass()
    {
        $values = Input::get('value');
        foreach (Input::get('key') as $k => $key) {
            if(!empty($key)) {
                $value = $values[$k];

                $var = new LanguagesVars;
                $var->defaults = $key;
                $var->key = $key;
                $var->value = $value;
                $var->lang_id = 1;
                $var->save();
            }
        }
        return View::make('admin.forms.mass');
    }

    public function filterGrid()
    {
        $newItems = $this->items;
        foreach ($this->items as $k => $item) {
            $newItems[$k]->lang_id= $item->language->langName;
        }
        return $newItems;
    }




}