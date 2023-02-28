<?php

namespace App\Http\Controllers;

use App\Models\Clinic\Day;
use App\Models\Clinic\UserDate;
use App\Models\Country;
use App\Models\Role;
use App\Models\UserMeta;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use OAuth;

class UserController extends MainController
{


    public function __construct(){
        parent::__construct();
    }


    public function getProfile($user_id)
    {
        if ($this->notDoctor() && $this->notKitchen()) {
            return $this->dontAllow();
        }

        $user = User::where('id',$user_id)
            ->with('package')
            ->where('active',1)
            ->first();

        if(!$user)
            return Redirect::to('/')->withMessage([trans('main.No User Has Been Found')]);
        $this->salt = ['No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt')];

        return View::make('user.profile')
            ->with('user',$user)
            ->with('salts',$this->salt)
            ->withTitle($user->username);
    }

    public function postSaveprofile()
    {
        $user = User::find(Input::get('user_id'));

        $height = Input::get('height');
        $weight = Input::get('weight');
        $user->salt = Input::get('salt');
        $user->height = $height;
        $user->weight = $weight;
        if (Input::has('height') && Input::has('weight')) {
            $user->bmi = intval($weight / (($height/100) * ($height/100)));
        }
        $user->save();
        // dd($user);

        return Redirect::to('user/profile/'.Input::get('user_id'));

    }

    // Register
    public function getRegister()
    {
        $rolesObj = Role::all();
        foreach ($rolesObj as $role) {
            $roles[$role->id] = $role->{'roleName'.ucfirst(LANG_SHORT)};
        }
        $roles[0] = trans('main.Account Type');
        unset($roles[1],$roles[10]);

        $countriesObj = Country::all();
        $countries[] = trans('main.Country');
        foreach ($countriesObj as $country) {
            $countries[$country->id] = $country->countryName;
        }
        return View::make('user.register')
            ->with('title', trans('main.Register'))
            ->with('roles', $roles)
            ->with('countries', $countries);
    }
    public function postRegister()
    {
        $validator = Validator::make(Input::all(), (
        array(
            // 'name' => 'required|min:2|max:60',
            'username' => 'required|min:2|max:30|unique:users',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|min:6|same:password',
            'email' => 'required|min:2|max:100|unique:users,email',
            'phone' => 'min:2|max:100',
            'country_id' => 'required|integer',
            'province_id' => 'required|integer',
            'area_id' => 'required|integer',
            'role_id' => 'required|integer|max:5',
            // 'address' => 'min:3',
        )
        ));
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('user/register')->withErrors($validator)->withInput();
        }
        $user = new User;
        // $user->name = Input::get('name');
        $user->username = Input::get('username');
        $user->password = bcrypt(Input::get('password'));
        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        $user->role_id = (Input::get('role_id') < 5) ? Input::get('role_id') : 1;
        $user->isAdmin = 0;
        $user->country_id = Input::get('country_id');
        $user->province_id = Input::get('province_id');
        $user->area_id = Input::get('area_id');
        // $user->address = Input::get('address');
        $user->active = 1;
        // $user->measuring_id = 1;
        // $user->currency_id = 1;
        $user->save();
        $userAdded = User::find($user->id);
        $data['user'] = $user;
        $data['password'] = Input::get('password');
        $data['settings'] = $this->_setting;
        Mail::send('emails.user_registered',$data,function($message) use ($data){
            $message->to($data['user']->email, $data['settings']['siteNameEn'])->subject('Thank You For Your Registeration');
        });


        Mail::send('emails.admin_user_registered', $data, function($message) use ($data)
        {
            $message
                ->to($data['settings']['adminEmail'], $data['settings']['siteNameEn'])
                ->subject('A new user registered!');
        });
        Auth::login($userAdded);
        return Redirect::to('user/cp')
            ->with('message',array('main.Logged In Successfully'));
    }
    public function getFacebooklogin()
    {
        // get data from input
        $code = Input::get( 'code' );
        // get fb service
        $fb = OAuth::consumer( 'Facebook' );
        // check if code is valid
        // if code is provided get user data and sign in
        if ( !empty( $code ) ) {
            // This was a callback request from facebook, get the token
            $token = $fb->requestAccessToken( $code );
            // Send a request with it
            $result = json_decode( $fb->request( '/me' ), true );
            $facebook['id'] = $result['id'];
            $facebook['name'] = $result['name'];
            $facebook['email'] = $result['email'];
            $facebook['address'] = (isset($result['location']) && isset($result['location']['name'])) ? $result['location']['name'] : "";
            $facebook['token'] = $token;
            Session::put('facebook', $facebook);

            //Var_dump
            //display whole array().

            $usermeta = UserMeta::where('metaKey','facebook_id')->where('metaValue',$facebook['id'])->first();
            $user = User::where('email',$facebook['email'])->first();
            if ($usermeta && $user && $user->id == $usermeta->user_id) {
                $user = User::find($usermeta->user_id);
                Auth::login($user);
                return Redirect::to('user/cp');
            } else {
                $newUser = new User;
                $newUser->email = $facebook['email'];
                $newUser->address = $facebook['address'];
                $newUser->name = $facebook['name'];
                $newUser->save();
                $newUserMeta = new UserMeta;
                $newUserMeta['metaKey'] = 'facebook_token';
                $newUserMeta['metaValue'] = $token->getAccessToken();
                $newUserMeta['user_id'] = $newUser->id;
                $newUserMeta->save();
                $newUserMeta = new UserMeta;
                $newUserMeta['metaKey'] = 'facebook_id';
                $newUserMeta['metaValue'] = $facebook['id'];
                $newUserMeta['user_id'] = $newUser->id;
                $newUserMeta->save();
                $user = User::find($newUser->id);
                Auth::login($user);
                return Redirect::to('user/cp');
            }
        }
        // if not ask for permission first
        else {
            // get fb authorization
            $url = $fb->getAuthorizationUri();
            // return to facebook login url
            return Redirect::to( (string)$url );
        }
    }

    public function postCusername()
    {
        $username = Input::get('username');
        $user = User::where('username',$username)->first();
        if ($user) {
            return json_encode(array('valid' =>false));
        }
        return json_encode(array('valid' =>true));
    }
    public function postCemail()
    {
        $email = Input::get('email');
        $user = User::where('email',$email)->first();
        if ($user) {
            return json_encode(array('valid' =>false));
        }
        return json_encode(array('valid' =>true));
    }
    // Password Reminder
    public function getForget()
    {
        return View::make('user.forgetpass')
            ->with('title', trans('main.Password Reminder'));
    }
    public function postRemind()
    {
        $response = Password::sendResetLink(Input::only('email'), function($message){
            $message->subject(Config::get('settings.siteNameAr') . ' - ' . Config::get('settings.siteNameen') . ' ' . trans('Password Reset'));
        });
        switch ($response)
        {
            case Password::INVALID_USER:
                return Redirect::back()->with('error',array(Lang::get($response)));
            case 'passwords.sent':
            case 'passwords.reset':
                return Redirect::back()->with('message',array(Lang::get($response)));

        }
    }
    public function getReset($token = null)
    {
        if (is_null($token)) abort(404);
        return View::make('user.reset')->with('token', $token);
    }
    public function postReset()
    {
        $credentials = Input::only(
            'email', 'password', 'password_confirmation', 'token'
        );
        $response = Password::reset($credentials, function($user, $password)
        {
            $user->password = Hash::make($password);
            $user->save();
        });
        switch ($response)
        {
            case Password::INVALID_PASSWORD:
            case Password::INVALID_TOKEN:
            case Password::INVALID_USER:
                return Redirect::back()->with('error', Lang::get($response));
            case Password::PASSWORD_RESET:
                return Redirect::to('/');
        }
    }
    // Control Panel
    public function getCp()
    {
        $this->salt = ['No Salt' => trans('main.No Salt'),'Medium Salt' => trans('main.Medium Salt')];

        if (!Auth::user())
            return Redirect::to('user/login');

        return View::make('user.usercp')
            ->with('title', trans('main.User Area'))
            ->with('user' , Auth::User())
            ->with('salts' , $this->salt)
            ->with('countries' , Country::all());
    }

    public function getSubmissions()
    {
        if (!Auth::user())
            return Redirect::to('user/login?uri=user/submissions');

        return View::make('user.submissions')
            ->with('title', trans('main.My Submissions'))
            ->with('user' , Auth::User());
    }

    public function getClients()
    {
        if (!Auth::user())
            return Redirect::to('user/login?uri=user/clients');

        switch (Auth::user()->role_id) {
            case '2':
                // Teachers
                $M = 'Teacher';
                break;
            case '3':
                // Schools
                $M = 'School';
                break;
            case '4':
                // Clubs
                $M = 'Club';
                break;

            default:
                return Redirect::to('/');
                break;
        }

        $items = $M::with('packages')
            ->with(['packages.orders' => function ($q)
            {
                return $q->orderBy('id','desc');
            }])
            ->with('packages.orders.user')
            ->where('user_id',Auth::user()->id)
            ->get();
        $items = $items->toArray();
        // dd($items->toArray());
        // $packages = array_fetch($items->toArray(),'packages');

        if(!empty($items)) {
            foreach ($items as $k => $item) {
                if(empty($item['packages'])) {
                    unset($items[$k]);
                    continue;
                }
                foreach ($item['packages'] as $kk => $package) {
                    if(empty($package['orders']))
                        unset($items[$k]['packages'][$kk]);
                }
            }
        }

        if(!empty($items)) {
            foreach ($items as $k => $item) {
                if(empty($item['packages'])) {
                    unset($items[$k]);
                    continue;
                }
            }
        }

        $statuses = [
            'unpaid' => trans('main.Un Paid'),
            'paid' => trans('main.Paid'),
            'proccessing' => trans('main.Proccessing'),
            'completed' => trans('main.Completed'),
            'cancelled' => trans('main.Cancelled'),
        ];


        return View::make('user.clients')
            ->with('title', trans('main.My Clients'))
            ->with('items', $items)
            ->with('statuses', $statuses)
            ->with('user' , Auth::User());
    }

    public function getClientsView($orderID)
    {
        if (!Auth::user())
            return Redirect::to('user/login?uri=user/clients-view/'.$orderID);

        switch (Auth::user()->role_id) {
            case '2':
                // Teachers
                $M = 'Teacher';
                $rel = 'teacher';
                break;
            case '3':
                // Schools
                $M = 'School';
                $rel = 'school';
                break;
            case '4':
                // Clubs
                $M = 'Club';
                $rel = 'club';
                break;

            default:
                return Redirect::to('/');
                break;
        }

        $pural = $rel . 's';

        $order = Order::with('package')
            ->with('user')
            ->with('package.'.$rel)
            ->with('package.'.$rel.'.user')
            ->where('id',$orderID)
            ->first();

        if($order->package->$rel->user->id != Auth::user()->id)
            return Redirect::to(url('user/clients'))
                ->withMessage([trans('main.You are not allowed to do so')]);

        $statuses = [
            'unpaid' => trans('main.Un Paid'),
            'paid' => trans('main.Paid'),
            'proccessing' => trans('main.Proccessing'),
            'completed' => trans('main.Completed'),
            'cancelled' => trans('main.Cancelled'),
        ];


        return View::make('user.order')
            ->with('title', trans('main.View Order') . ' # '. $orderID)
            ->with('order', $order)
            ->with('pural', $pural)
            ->with('rel', $rel)
            ->with('statuses', $statuses)
            ->with('user' , Auth::User());

    }

    public function postPassword()
    {
        // echo (Auth::user()->password);
        // dd(Hash::make(Input::get('password')));
        if (!Auth::attempt(array('username' => Auth::user()->username,'password' => Input::get('password'), 'active' => 1)))
            return Redirect::to('user/info')->with('error', array('خطأ في كلمة المرور'))->withInput();
        $validator = Validator::make(Input::all(), (
        array(
            'id' => 'integer',
            'password' => 'required|min:6',
            'newpassword' => 'required|min:6',
            'newpasswordconf' => 'required|min:6|same:newpassword',
        )
        ));
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('user/info')->withErrors($validator)->withInput();
        }
        $user = User::find(Auth::user()->id);
        $user->password = Hash::make(Input::get('newpassword'));
        $user->save();
        return Redirect::to('user/cp')
            ->with('message',array(trans('main.Passwrd Changed Successfully')));
    }
    public function postInfo()
    {
        $validator = Validator::make(Input::all(), (
        array(
            'id' => 'integer',
            'username' => 'min:2|max:100|unique:users,username,'.Input::get('id').',id',
            'email' => 'required|min:2|max:100|unique:users,email,'.Auth::user()->id.',id',
            'phone' => 'min:2|integer',
            // 'name' => 'required|min:2',
            'country_id' => 'required|integer',
            'province_id' => 'required|integer',
            'area_id' => 'required|integer',
            // 'address' => 'min:2',
        )
        ));
        if ($validator->fails()) {
            $messages = $validator->messages();
            return Redirect::to('user/cp')->withErrors($validator)->withInput();
        }
        $user = User::find(Auth::user()->id);
        if (Input::has('username'))
            $user->username = Input::get('username');
        $user->country_id = Input::get('country_id');
        $user->province_id = Input::get('province_id');
        $user->area_id = Input::get('area_id');
        if (Input::has('name'))
            $user->name = Input::get('name');
        $user->email = Input::get('email');
        $user->phone = Input::get('phone');
        // $user->address = Input::get('address');
        $user->save();
        // Auth::login($user);
        return Redirect::to('user/cp')
            ->with('message',array(trans('main.Saved!')));
    }
    // Login
    public function getLogin()
    {
        $url = '/';
       // session()->remove('hidden_home');
        if(Input::has('uri'))
            $url = Input::get('uri');
        // dd(Session::get('message'));
        if (Auth::user() && Auth::user()->active == 1 && Auth::user()->role_id == 1)
            return Redirect::to();

        return View::make('user.login')
            ->with('title', trans('main.Login'))
            ->withUrl($url);
    }
    public function getNewLogin()
    {
        session()->put('hidden_home',true);
        $url = '/';
        if(Input::has('uri'))
            $url = Input::get('uri');
        // dd(Session::get('message'));
        if (Auth::user() && Auth::user()->active == 1 && Auth::user()->role_id == 1)
            return Redirect::to();

        return View::make('user.otherLogin')
            ->with('title', trans('main.Login'))
            ->withUrl($url);
    }
    public function postLogin(Request $request)
    {
        $url = '/members';
        if(Input::has('uri'))
            $url = Input::get('uri');

        $username=Input::get('username');


        //dd(Auth::attempt(array('email' => $username, 'password' => Input::get('password'),'active'=>1),Input::get('remember')));

        if (Auth::attempt(array('username' => $username,'password' => Input::get('password'), 'active' => 1),Input::get('remember'))||Auth::attempt(array('mobile_number' => $username, 'password' => Input::get('password'),'active'=>1),Input::get('remember')) ) {
            return \redirect()->route('home_members');
        }
        else {
            return View::make('user.login')
                ->with('messages',array('danger' =>
                    trans('main.Wrong Password or Username, Please check your form submission, and ty again') ))
                ->with('title', trans('main.Login'));
        }
    }
    public function postLoginExternal()
    {
        $url = '/';
        if(Input::has('uri'))
            $url = Input::get('uri');

        $remember = Input::has('remember') ? Input::get('remember') : 0;
        $username=Input::get('username');

        if (Auth::attempt(array('username' => $username,'password' => Input::get('password'), 'active' => 1),Input::get('remember'))||Auth::attempt(array('mobile_number' => $username, 'password' => Input::get('password'),'active'=>1),Input::get('remember')) ) {
            return \redirect()->route('home_members');
        }
        else {
            return View::make('user.login')
                ->with('messages',array('danger' =>
                    trans('main.Wrong Password or Username, Please check your form submission, and ty again') ))
                ->with('title', trans('main.Login'));
        }
    }
    // Logout
    public function getLogout()
    {
        Auth::logout();
        return Redirect::to('/');
    }

    public function getNewlogout()
    {
        Auth::logout();
        return \redirect()->route('new_login');
    }


    public function test()
    {
        $dates= UserDate::whereId('date',696478)->with(['user.package.meals'])->get();
        dd($dates);


        $unixTimestamp = time();
        $dayOfWeek = date("l", $unixTimestamp);
        $dayId=Day::where('titleEn',$dayOfWeek)->first();

        dd($dayId);

    }
    public function getSummeryIndex(){
        $title=trans('main.Menu Summary');
        $user = Auth::user();
        $days= UserDate::with(["orders.meal","orders.item"])->where('freeze',0)->where('isMealSelected',1)->where('user_id',$user->id)->orderBy('date','asc')->get();
        return \view('summary',compact('title','user','days'));
    }


}
