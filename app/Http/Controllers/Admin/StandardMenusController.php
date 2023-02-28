<?php
/**
 * Created by PhpStorm.
 * User: 2
 * Date: 7/24/2019
 * Time: 11:58 AM
 */

namespace App\Http\Controllers\Admin;


use App\Models\StandardMenu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

use App\Models\Clinic\Item;
use App\Models\Clinic\Order;

class StandardMenusController extends AdminController
{

    public function __construct()
    {
        // The Model To Work With
        $this->model = standardMenu::class;

        // Primary Key
        $this->_pk = 'id';

        // Main Part of menu url
        $this->menuUrl = 'standard_menus';

        // Human Name
        $this->humanName = '';


        // $provinces = DB::table('provinces');
        // $this->provinces = $provinces->lists('title'.ucfirst(LANG_SHORT), 'id');
        // $cities = DB::table('cities');
        // $this->cities = $cities->lists('title'.ucfirst(LANG_SHORT), 'id');

        // Fields for this table
        // $this->fields[] = array('title' => trans('main.Name'), 'name' => 'name','width' => 15, 'grid' => true ,'type' => 'text', 'col' => 2);
        $meals = DB::select("SELECT * FROM meals;");
        //$this->fields[] = array('title'=> 'Day', 'name'=>'day', 'width'=>20, 'grid'=>true, 'type'=>'text', 'col'=>0);
        for($i = 0; $i < count($meals); $i++)
        {
            //$this->fields[$mealRC->] = [];
            $mealArr = json_decode(json_encode($meals[$i]), true);
            ///var_dump($mealArr['titleAr']);
            $this->fields[] = array('title'=> $mealArr['titleEn'], 'name'=>$mealArr['titleEn'], 'width'=>20, 'grid'=>true, 'type'=>'text', 'col'=>($i+1));
        }



        // Grid Buttons
        // print_r(AdminController::$_adminMeta);
        //$this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        //$this->buts['edit'] = array('name' =>'Edit','icon' => 'edit', 'color' => 'blue');
        //$this->buts['delete'] = array('name' =>'Delete','icon' => 'remove', 'color' => 'yellow');
        $this->buts['add'] = array('name' =>'Add','icon' => 'plus', 'color' => 'green');
        $this->buts['edit'] = array('name' =>'Assign Standard Menu','icon' => 'plus', 'color' => 'blue');
        // $this->buts['view'] = array('name' =>'View','icon' => 'magnifier', 'color' => 'green');

        //$this->gridButs['add'] = ['name' =>'Add','icon' => 'add', 'color' => 'green', 'JShndlr'=>"showRenewPopup"];
        //$this->recordButs['Add to pdf test'] = ['name'=>'Add pdf', 'icon'=>'', 'color' => 'red']; // NOT IMPLEMENTED, should show grid buttons only
        $this->gridButs = [];
        //$this->recordButs['edit'] = ['name' =>'Edit','icon' => 'edit', 'color' => 'green', 'JShndlr'=>"showEditRow"];
        $this->recordButs = [];

        $this->routeParams = [];
        $this->customJS = "cpassets/js/standard_menus.js";
        // The View Folder
        $this->views = '';
        $this->deletable = false;
        $this->checkboxCol = false;


        parent::__construct();
    }

    public function getSM_List()
    {
        $smList = DB::select("SELECT * FROM standard_menus");
        echo json_encode(["result"=>"SUCCESS", "data"=>$smList]);
    }

    public function loadData()
    {

        $M = $this->model;
        $date = date("Y-m-d");
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('', '>',$date)->where('role_id', '=', 1)->join('roles', 'users.role_id','=','roles.id')->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email', 'roles.roleNameAr', 'roles.roleNameEn', 'users.active')->get();
        $this->items =[];// DB::select("SELECT titleEn AS day FROM days");
//    	$this->items = $M::where('membership_end', '<=',$date)->where('role_id', '=', 1)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//    	$this->items = $M::where('membership_end', '<=',$date)->join('packages', 'users.package_id', '=', 'packages.id')->select('users.username', 'packages.titleAr', 'packages.titleEn', 'users.membership_end', 'users.phone','users.email')->get();
//		var_dump($M);

    }

    public function getMeals()
    {
        //$items = DB::select('SELECT * FROM items');
        $meals  = DB::select("SELECT * FROM meals");
        $itemsByMeal = DB::select('SELECT items.titleAr AS itemAr, items.titleEn AS itemEn, meals.titleAr AS mealAr, meals.titleEn AS mealEn, meals.id AS mealId, items.id AS itemId FROM meals 
INNER JOIN categories ON categories.meal_id = meals.id
INNER JOIN items ON categories.id = items.category_id ');
        //echo json_encode(["result"=>"SUCCESS", "data"=>$items, "meals"=>$meals]);
        if(empty($itemsByMeal))
        {
            echo json_encode(["result"=>"FAILURE"]);
        }
        echo json_encode(["result"=>"SUCCESS", "data"=>$itemsByMeal, "meals"=>$meals]);
    }



    public function addStandardMenu()
    {
        $sMenuEn = Input::get("sm_name_en");
        $sMenuAr = Input::get("sm_name_ar");
        //DB::insert("INSERT INTO standard_menus (standard_menu_nameAr, standard_menu_nameEn, created_at) VALUES (?, ?, ?)", array($sMenuEn, $sMenuAr, date("Y-m-d")));
        $id = 0;
        try
        {
            $id = DB::table('standard_menus')->insertGetId(
                array('standard_menu_nameAr' => $sMenuAr, 'standard_menu_nameEn'=>$sMenuEn, 'titleAr'=>$sMenuAr, 'titleEn'=>$sMenuEn,  'created_at'=>date('Y-m-d')));
            //DB::table('standard_menus')->insert(array('standard_menu_nameAr'=>$sMenuAr, 'standard_menu_nameEn'=>$sMenuEn, 'created_at'=>date("Y-m-d")));

            //$idArr = DB::select("SELECT id FROM standard_menus WHERE standard_menu_nameAr = ? AND standard_menu_nameEn = ?", array($sMenuAr, $sMenuAr));
            //var_dump($idArr);
            //$id = $idArr["id"];
            //$id = DB::table('standard_menus')->insert("INSERT INTO standard_menus (standard_menu_nameAr, standard_menu_nameEn, created_at) VALUES (?, ?, ?)", array($sMenuEn, $sMenuAr, date("Y-m-d")))->lastInsertId();
        }
        catch (\Illuminate\Database\QueryException $e)
        {
            //var_dump($e->getMessage());
            echo json_encode(["result"=>"FAILURE", "msg"=>$e->getMessage()]);
        }

        //$idArr = DB::select("SELECT id FROM standard_menus WHERE standard_menu_nameAr = ?, standard_menu_nameEn = ?", array($sMenuAr, $sMenuEn));
        //$id = $idArr[0]["id"];
//    	var_dump($id);
        $daysArr = DB::select("SELECT * FROM days");
        $mealsArr = DB::select("SELECT * FROM meals");
        $days = json_decode(json_encode($daysArr), true);
        $meals = json_decode(json_encode($mealsArr), true);
        for($i = 0; $i < count($days); $i++)
        {
            for($y = 0; $y < count($meals); $y++)
            {
                //echo $meals[$y]["id"];
                DB::insert("INSERT INTO standard_menu ( standard_menu_id, item_id, meal_id, created_at, day_id) VALUES (?,?,?,?,?)", array($id, -1, $meals[$y]["id"], date("Y-m-d"), $days[$i]["id"]));
            }
        }
        $standardMenuData = DB::select("SELECT * FROM standard_menu INNER JOIN days ON standard_menu.day_id = days.id WHERE standard_menu_id = ?", array($id));
        echo json_encode(["result"=>"SUCCESS", "data"=>$standardMenuData]);
    }


    public function get_users_list()
    {
        $smlist = DB::select("SELECT id,username FROM users where role_id = 1 order by id");
        echo json_encode(["result"=>"SUCCESS", "data"=>$smlist]);
    }

    // Assigning standard menu to the selected people
    public function assign_menu()
    {
        $user_list = implode(",",Input::get("user_list"));
        $menu_id = Input::get("menu_id");
        if($user_list=="-1")
        {
            echo json_encode(["result"=>"failure"]);
        }
        else
        {
            if($user_list=="0")
            {
                $users = DB::select("SELECT id, package_id FROM users where role_id = 1 and standard_menu_id !=?",array($menu_id));
            }
            else
            {
                $users = DB::select("SELECT id, package_id FROM users WHERE role_id = 1 and id in ($user_list) ");
            }
            if($users)
            {
                foreach($users as $user)
                {
                    if(intval($menu_id) === 2 || $menu_id=== 1)
                    {
                        DB::update("UPDATE users SET  standard_menu_id=? WHERE id = ?", array($menu_id,$user->id));
                       // DB::update("UPDATE users SET autoapprove_menus = 0, standard_menu_id=? WHERE id = ?", array($menu_id,$user->id));
                    }
                    else
                    {
                        $menulist = DB::select("SELECT item_id, meal_id, day_id FROM standard_menu WHERE standard_menu_id = ? AND meal_id IN (SELECT meal_id FROM packages_meals WHERE package_id = ?)", array($menu_id,$user->package_id));
                        DB::update("UPDATE users SET autoapprove_menus = 1, standard_menu_id=? WHERE id = ?", array($menu_id,$user->id));
                        for($i = 0; $i < count($menulist); $i++)
                        {
                            $meal_id = $menulist[$i]->meal_id;
                            $item_id = $menulist[$i]->item_id;
                            $day_id = $menulist[$i]->day_id;
                            if($item_id!=-1)
                            {
                                $category_id = Item::find($item_id)->category->id;
                                $order = Order::where('user_id',$user->id)
                                    ->where('day_id',$day_id)
                                    ->where('meal_id',$meal_id)
                                    ->first();
                                if (!$order) {
                                    $order = new Order;
                                }
                                $order->day_id = $day_id;
                                $order->user_id = $user->id;
                                $order->meal_id = $meal_id;
                                $order->item_id = $item_id;
                                $order->category_id = $category_id;
                                $order->approved = 1;
                                $order->save();
                            }
                        }
                    }
                }
            }
            echo json_encode(["result"=>"SUCCESS"]);
        }
    }

    public function getStandardMenu()
    {
        $smID = Input::get("menu_id");
        $standardMenuItems  = DB::select("SELECT standard_menu_id, item_id, meals.titleEn AS mealTitleEn, meals.titleAr AS mealTitleAr , items.titleEn, items.titleAr, meal_id, day_id FROM standard_menu LEFT OUTER JOIN items ON standard_menu.item_id = items.id       INNER JOIN meals ON standard_menu.meal_id = meals.id WHERE standard_menu_id = ?", array($smID));
        echo json_encode(["result"=>"SUCCESS", "data"=>$standardMenuItems]);
    }
    public function get_standard_menu_day()
    {
        $smID = Input::get("menu_id");
        $day_id = Input::get("day_id");
        $standardMenuItems  = DB::select("select items.titleAr AS itemAr, items.titleEn AS itemEn, meals.titleAr AS mealAr, meals.titleEn AS mealEn, meals.id AS mealId, items.id AS itemId,st_menu.active from items join items_days on items.id = items_days.item_id join categories on categories.id = items.category_id join meals on meals.id = categories.meal_id left join(select 1 as active,item_id from standard_menu where standard_menu_id = ? and day_id = ? ) as st_menu on st_menu.item_id = items.id  where items_days.day_id = ?", array($smID,$day_id,$day_id));
        $meals  = DB::select("SELECT * FROM meals");
        echo json_encode(["result"=>"SUCCESS", "data"=>$standardMenuItems, "meals"=>$meals]);
    }
    public function saveMenu()
    {
        $smID = Input::get("sm_id");
        $dayID = Input::get("day_id");
        $data = Input::get("data");
        foreach($data AS $k=>$v)
        {

            //var_dump($k); echo "something";
            //echo $k.$v." ";
            $sql = "UPDATE standard_menu SET item_id = ".$v." WHERE meal_id = ".$k." AND day_id = ".$dayID." AND standard_menu_id =".$smID;
            DB::statement($sql);
            //echo $sql;
            //echo DB::table("standard_menu")->where("day_id",$dayID)->where("standard_menu_id", $smID)->where("meal_id",$k)->update(array("item_id"=>$v ));
            //$res = DB::update("UPDATE standard_menu SET  item_id = ? WHERE day_id = ? AND standard_menu_id = ? AND meal_id = ?", array($v, $dayID, $smID, $k ) );
            //if($res == 0)
            //{
            //	echo json_encode(["result"=>"FAILURE", array($v, $k, $dayID, $smID )]);
            //	return;
            //}
        }
        echo json_encode(["result"=>"SUCCESS"]);
    }

    public function getStandardMenuList()
    {
        $standardMenuItems  = DB::select("SELECT * FROM standard_menus");
        echo json_encode(["result"=>"SUCCESS", "data"=>$standardMenuItems]);
    }


}
