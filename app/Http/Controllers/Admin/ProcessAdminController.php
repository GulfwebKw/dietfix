<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\AdminMenu;
use App\Models\User;
use App\Models\UserMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class ProcessAdminController extends AdminController
{

    public function __construct(){
        parent::__construct();

    }


    public function showHome()

    {



       // $this->beforeFilter('admin')->count();

        $users = User::whereIn('role_id',[1,2,3,4])
            ->count();

        return View::make('admin.home')->withUsers($users)->with('_pageTitle',trans('main.Home'));

    }
    public function index()
    {

        if (!Auth::user() || Auth::user()->isAdmin != 1) return Redirect::to('/'.env('admin').'/process/login');

    }

    public function login()
    {

        if (Auth::user() && Auth::user()->isAdmin == 1)

            return Redirect::to('/'.ADMIN_FOLDER.'/');

        return View::make('admin.login')->with('_pageTitle', trans('main.Login'));


    }

    public function loginAction(Request $request)
    {


        $uri = (isset($_GET['u'])) ? base64_decode($_GET['u']) : false;



        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1,'isAdmin' => 1),Input::get('remember'))){
            
            return Redirect::to('/'.env('ADMIN_FOLDER').'/'.$uri);
        } else
            return View::make('admin.login')->with('messages', array(trans('main.Wrong Password or Username, Please check your form submission, and ty again')))->with('_pageTitle', trans('main.Login'));
    }
    public function logout()

    {

        Auth::logout();

        return Redirect::to('/'.env('ADMIN_FOLDER').'/process/login');

    }



    public function getLock()

    {

        $data = array('userID' => Auth::user()->userID,'username' => Auth::user()->username,'admin_name' => User::find(Auth::user()->userID)->getMeta(User::find(Auth::user()->userID),'admin_name'),'admin_email' => Auth::user()->email);
        Session::put('accountLock', base64_encode(serialize($data)));

        return View::make('admin.locked')->with('_pageTitle', trans('main.Locked'))->with('lock',$data);

    }



    public function postLocked()

    {

        $locked = Session::get('accountLock');

        $locked = unserialize(base64_decode($locked));

        if (Auth::attempt(array('username' => $locked['username'], 'password' => Input::get('password'), 'active' => 1,'isAdmin' => 1)))

            return Redirect::to('/'.ADMIN_FOLDER);
        else {

            Auth::logout();
            return Redirect::to('/'.ADMIN_FOLDER.'/process/login');

        }

    }

    public function getLang($lang)

    {

        if ($lang && in_array($lang, array('ar','en')))

            session()->put('lang',$lang);

        return Redirect::to('/'.env('ADMIN_FOLDER'));

    }



    public function fix()
    {
        $adminMenus = AdminMenu::all();
        foreach ($adminMenus as $menu) {
            $adminPermission[$menu->id] = array('add','edit','delete','view','print','export');
        }
        $adminPermission = serialize($adminPermission);


        // Add Admin
        $password = Hash::make('147147');
        $user = User::create(array(
            // 'name' => 'Ahmed',
            'username' => 'Admin',
            'password' => $password,
            'email' => 'ahmed@sz4h.com',
            'phone' => '66991985',
            'country_id' => 120,
            'province_id' => 1,
            'area_id' => 1,
            'isAdmin' => 1,
            'role_id' => 10,
            'active' => 1,
        ));

        UserMeta::create(array(
            'user_id' => $user->id,
            'metaKey' => 'adminPermission',
            'metaValue' => $adminPermission,
        ));
        UserMeta::create(array(
            'user_id' => $user->id,
            'metaKey' => 'admin_name',
            'metaValue' => 'Ahmed',
        ));
    }




}
