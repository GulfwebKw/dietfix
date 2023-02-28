<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/25/2019
 * Time: 10:31 AM
 */
namespace App\Http\Controllers\Admin;
use App\Models\Admin\AdminMessage;
use Illuminate\Support\Facades\View;
use stdClass;

class AdminMessageController extends AdminController
{
    public function __construct()
    {
        // The Model To Work With
        $this->model = AdminMessage::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'message';

        // Human Name
        $this->humanName = 'الرسائل';

        // Fields for this table
        $this->fields[] = array('title' => trans('main.Title'), 'name' => 'messageTitle', 'searched' => true, 'width' => 50, 'grid' => true ,'type' => 'div');
        $this->fields[] = array('title' => trans('main.Content'), 'name' => 'messageContent' ,'type' => 'div');
        $this->fields[] = array('title' => trans('main.From'), 'name' => 'messageFrom', 'searched' => true, 'width' => 50, 'grid' => true ,'type' => 'div');
        $this->fields[] = array('title' => trans('main.Date'), 'name' => 'created_at', 'searched' => true, 'width' => 50, 'grid' => true ,'type' => 'timestampDisplay');

        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        // $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        // $this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        $this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        // The View Folder
        // $this->views = 'adminmenu';

        // Deleteable
        $this->deletable = true;

        $this->sortBy = 'created_at';
        $this->sortType = 'DESC';

        $this->gridButs = [];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "";
        parent::__construct();
    }
    public function getView($id)
    {

        if(!$id || !is_numeric($id))
            die();

        $M = $this->model;
        $this->item = $M::find($id);

        $this->item->readed = 1;
        $this->item->save();
        return View::make( 'admin.' . $this->views . 'form' )
            ->with( '_pageTitle' , trans('main.Add') . ' ' . $this->humanName )
            ->with( '_pk' , $this->_pk )
            ->with( 'url' , $this->saveurl )
            ->with( 'item' , $this->item )
            ->with( 'uploadable' , $this->uploadable )
            ->with( 'fields' , $this->fields );
    }

}
