<?php

namespace App;

use App\Mail\ForgotPassword;
use App\Models\CancelFreezeDay;
use App\Models\CashBack;
use App\Models\Clinic\Appointment;
use App\Models\Clinic\Area;
use App\Models\Clinic\Clinic;
use App\Models\Clinic\Day;
use App\Models\Clinic\DoctorTime;
use App\Models\Clinic\Order;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;
use App\Models\Clinic\Payment;
use App\Models\Clinic\Province;
use App\Models\Clinic\UserDate;
use App\Models\Country;
use App\Models\Delivery;
use App\Models\Role;
use App\Models\StandardMenu;
use App\Models\UserMeta;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMailabl;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $fillable = array('name',
        'username',
        'password',
        'email',
        'phone',
        'country_id',
        'province_id',
        'area_id',
        'codeRequested',
        'deviceType',
        'deviceToken',
        'address',
        'isAdmin',
        'role_id',
        'active',
        'clinic_id',
        'package_id',
        'doctor_id',
        'membership_end',
        'membership_start',
        'sex',
        'salt',
        'height',
        'weight',
        'bmi',
        'address',
        'building_number',
        'building_number_work',
        'standard_menu_id',
        'delivery_type',
        'autoapprove_menus',
        'lastDeviceCode',
    );

    public static $rules = array(
        'default' => array(
            // 'name' => 'required|min:2|max:60',
            'username' => 'required|min:2|max:20|unique:users',
            'password' => 'required|min:6',
            'email' => 'required|email|min:6|max:60|unique:users',
            'phone' => 'nullable|min:6|max:16|unique:users',
            'mobile_number' => 'required|min:6|max:16|unique:users',
            'delivery_type' => 'required|exits:delivery_type',
            'country_id' => 'required|integer',
            'province_id' => 'required|integer',
            'area_id' => 'required|integer',
            'codeRequested' => '',
            'deviceType' => 'in:iphone,android,site',
            'deviceToken' => '',
            // 'province_id' => 'required|integer',
            // 'city_id' => 'required|integer',
            'role_id' => 'required|integer',
            'address' => '',
            'isAdmin' => 'integer',
            'active' => 'integer|size:1',
            'sex' => 'in:Male,Female',
           // 'salt' => 'in:No Salt,Medium Salt,More Salt',
            'clinic_id' => 'required|integer',
            'package_id' => 'integer',
            'doctor_id' => 'integer',
            'membership_start' => 'date',
            'membership_end' => 'date',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
            'bmi' => '',
        ),
        'update' => array(
            'username' => 'min:2|max:20',
            'email' => 'required|email|min:6|max:60',
            'country_id' => 'required',
            'height' => 'nullable|numeric',
            'weight' => 'nullable|numeric',
        ),
    );


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function days()
    {
        return $this->belongsToMany(Day::class,'doctors_times','doctor_id','day_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function meta()
    {
        return $this->hasMany(UserMeta::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function times()
    {
        return $this->hasMany(DoctorTime::class,'doctor_id');
    }

    public function clients()
    {
        return $this->hasMany(User::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dates()
    {
        return $this->hasMany(UserDate::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders_sorted_by_dates()
    {
        return $this->hasMany(Order::class,'user_id')
            ->orderBy('day_id','asc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function doctor_appointments()
    {
        return $this->hasMany(Appointment::class,'doctor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class,'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function upcoming_appointments()
    {
        return $this->hasMany(Appointment::class,'user_id')->where('date','>=',date('Y-m-d'));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function CancelDay()
    {
        return $this->hasOne(CancelFreezeDay::class,'user_id' );
    }

    public function packageDuration()
    {
        return $this->belongsTo(PackageDurations::class);
    }

    public function deliveryType()
    {
        return $this->belongsTo(Delivery::class , 'delivery_type' );
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function countryWeekends()
    {
        return $this->belongsTo(Country::class,'country_work_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class);
    }
    public function provinceWeekends()
    {
        return $this->belongsTo(Province::class,'province_work_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function areaWeekends()
    {
        return $this->belongsTo(Area::class,'area_work_id');
    }
    public function standard_menu()
    {
        return $this->belongsTo(StandardMenu::class);
        //	return ;
    }

    /**
     * @param $obj
     * @return array
     */
    public static function getAllMeta($obj)
    {
        $returnArray = array();
        foreach ($obj->meta as $key => $value) {
            $returnArray[$value->metaKey] = $value->metaValue;
        }
        return $returnArray;
    }
    public static function getMeta($obj,$key)
    {
        $allMeta = User::getAllMeta($obj);
        if (isset($allMeta[$key]))
            return $allMeta[$key];
        else
            return false;
    }
    public function getDates()
    {
        return array('created_at','updated_at');
    }

    public function unique_username($username, $id)
    {
        return !$this->where('username',$username)->where('id', '!=', $id)->first();
    }
    public function unique_email($email, $id)
    {
        return !$this->where('email',$email)->where('id', '!=', $id)->first();
    }

    public function getRemDayCount()
    {
        $today=date('Y-m-d');
        $date = strtotime("+3 day", strtotime($today));
        $firstValidDay=date("Y-m-d",$date);

        if(isset($this->id)){
         return   Cache::remember('user_rem_count'.$this->id,2,function()use($today) {
              return  UserDate::where('user_id',$this->id)->where('date','>=',$today)->count();
            });
        }
        return 0;



    }

    public function lastDay()
    {
        return $this->hasOne(UserDate::class,'user_id')->where('freeze',0)->orderBy('date','desc');
    }


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password','codeRequested');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the e-mail address where password reminders are sent.
     *
     * @return string
     */
    public function getReminderEmail()
    {
        return $this->email;
    }


    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
    public function sendPasswordResetNotification($token)
    {
        Mail::to($this->email)->send(new ForgotPassword($token));
       // $this->notify(new CustomResetPasswordNotification());
    }

    public function cashBacks()
    {
        return $this->hasMany(CashBack::class,'user_id');
    }
}
