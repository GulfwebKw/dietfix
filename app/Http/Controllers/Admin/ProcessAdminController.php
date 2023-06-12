<?php

namespace App\Http\Controllers\Admin;
use App\Models\Admin\AdminMenu;
use App\Models\Clinic\Payment;
use App\Models\User;
use App\Models\UserMeta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use function GuzzleHttp\Psr7\str;

class ProcessAdminController extends AdminController
{

    public function __construct(){
        parent::__construct();

    }


    public function showHome()

    {



       // $this->beforeFilter('admin')->count();

        $data = \Cache::remember('admin_dashboard' , 15*60 , function (){
            $users = User::whereIn('role_id',[1,2,3,4])
                ->whereDate('created_at' , '>=' , Carbon::now()->subYear())
                ->get();
            $Count['register']['daily'] = $users->filter(function ($user){
                return $user->created_at->isToday();
            })->count();
            $Count['register']['weekly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subWeek());
            })->count();
            $Count['register']['monthly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subMonth());
            })->count();
            $Count['register']['quarterly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subQuarter());
            })->count();
            $Count['register']['yearly'] = $users->count();


            $users = User::whereIn('role_id',[1,2,3,4])
                ->whereDate('membership_start' , '>=' , Carbon::now()->subYear())
                ->get();
            $Count['membership']['daily'] = $users->filter(function ($user){
                return Carbon::parse($user->membership_start)->isToday();
            })->count();
            $Count['membership']['weekly'] = $users->filter(function ($user){
                return Carbon::parse($user->membership_start)->isAfter(Carbon::now()->subWeek());
            })->count();
            $Count['membership']['monthly'] = $users->filter(function ($user){
                return Carbon::parse($user->membership_start)->isAfter(Carbon::now()->subMonth());
            })->count();
            $Count['membership']['quarterly'] = $users->filter(function ($user){
                return Carbon::parse($user->membership_start)->isAfter(Carbon::now()->subQuarter());
            })->count();
            $Count['membership']['yearly'] = $users->count();


            $users = User::whereIn('role_id',[1,2,3,4])
                ->WhereNotNull('dob')
                ->get();
            $Count['birthday']['daily'] = $users->filter(function ($user){
                return Carbon::parse($user->dob)->dayOfYear() == Carbon::now()->dayOfYear() ;
            })->count();
            $Count['birthday']['weekly'] = $users->filter(function ($user){
                $dob = Carbon::parse($user->dob);
                return Carbon::parse($user->dob)->addYears($dob->diffInYears(Carbon::now()))->isCurrentWeek();
            })->count();
            $Count['birthday']['monthly'] = $users->filter(function ($user){
                $dob = Carbon::parse($user->dob);
                return Carbon::parse($user->dob)->addYears($dob->diffInYears(Carbon::now()))->isCurrentMonth();
            })->count();
            $Count['birthday']['quarterly'] = $users->filter(function ($user){
                $dob = Carbon::parse($user->dob);
                return Carbon::parse($user->dob)->addYears($dob->diffInYears(Carbon::now()))->isCurrentQuarter();
            })->count();
            $Count['birthday']['yearly'] = $users->count();




            $users = Payment::query()->where('paid' , 1 )->whereDate('created_at' , '>=' , Carbon::now()->subYear())->get();
            $Count['payment']['daily'] = $users->filter(function ($user){
                return $user->created_at->isToday();
            })->sum('total');
            $Count['payment']['weekly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subWeek());
            })->sum('total');
            $Count['payment']['monthly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subMonth());
            })->sum('total');
            $Count['payment']['quarterly'] = $users->filter(function ($user){
                return $user->created_at->isAfter(Carbon::now()->subQuarter());
            })->sum('total');
            $Count['payment']['yearly'] = $users->sum('total');


            $register = [];
            $memberShip = [];
            $payment = [];
            foreach ( Carbon::now()->subDays(30)->daysUntil(Carbon::now()) as $days ){
                $register[$days->toDateString()] = 0 ;
                $memberShip[$days->toDateString()] = 0 ;
                $payment[$days->toDateString()] = 0 ;
            }
            User::query()->whereIn('role_id',[1,2,3,4])
                ->whereDate('created_at', '>=' , Carbon::now()->subDays(30))
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->toDateString();
                })->map(function ($item) use (&$register) {
                    $register[$item[0]->created_at->toDateString() ] = $item->count();
                });
            User::query()->whereIn('role_id',[1,2,3,4])
                ->whereDate('membership_start', '>=' , Carbon::now()->subDays(30))
                ->get()
                ->groupBy(function ($item) {
                    return Carbon::parse($item->membership_start)->toDateString();
                })->map(function ($item) use (&$memberShip) {
                    $memberShip[Carbon::parse($item[0]->membership_start)->toDateString() ] = $item->count();
                });
            Payment::query()->where('paid' , 1 )
                ->whereDate('created_at', '>=' , Carbon::now()->subDays(30))
                ->get()
                ->groupBy(function ($item) {
                    return $item->created_at->toDateString();
                })->map(function ($item) use (&$payment) {
                    $payment[$item[0]->created_at->toDateString() ] = $item->sum('total');
                });
            $Count['graph']['register'] = $register;
            $Count['graph']['memberShip'] = $memberShip;
            $Count['graph']['payment'] = $payment;

            return $Count;
        });

//        dd($data);
        return View::make('admin.home' , ['animalistic_data' => $data])->with('_pageTitle',trans('main.Home'));

    }
    public function index()
    {

        if (!Auth::user() || Auth::user()->isAdmin != 1) return Redirect::to('/'.env('admin').'/process/login');

    }

    public function login()
    {

        if (Auth::user() && Auth::user()->isAdmin == 1 and \request()->cookie('adminLastDeviceCode') == Auth::user()->lastDeviceCode)

            return Redirect::to('/'.ADMIN_FOLDER.'/');

        return View::make('admin.login')->with('_pageTitle', trans('main.Login'));


    }

    public function loginAction(Request $request)
    {


        $uri = (isset($_GET['u'])) ? base64_decode($_GET['u']) : false;



        if (Auth::attempt(array('username' => Input::get('username'), 'password' => Input::get('password'), 'active' => 1,'isAdmin' => 1),Input::get('remember'))){
            $user = \auth()->user();
            $user->lastDeviceCode = \Str::uuid();
            $user->save();
            $cookie = cookie()->forever('adminLastDeviceCode',  $user->lastDeviceCode);
            return Redirect::to('/'.env('ADMIN_FOLDER').'/'.$uri)->cookie($cookie);
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
