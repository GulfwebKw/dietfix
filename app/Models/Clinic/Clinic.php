<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;
use Illuminate\Support\Facades\File;

class Clinic extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'clinics';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','logo','email','phone','contact_info','can_see_others','can_appointment','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'logo' => 'min:5',
		'phone' => 'numeric',
		'email' => 'email',
		'contact_info' => '',
		'can_see_others' => 'in:1,0',
		'can_appointment' => 'in:1,0',
		'active' => 'in:1,0',
	);

//	public static function boot()
//    {
//        parent::boot();
//
//
//       //  Attach event handler, on deleting of the user
//      Clinic::deleting(function($item)
//        {
//        	File::delete(public_path('media/clinics/'.$item->logo));
//        });
//    }


	public function appointments()
	{
		return $this->hasMany('Appointment','clinic_id');
	}


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
	{
		return $this->hasMany(User::class,'clinic_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function doctors()
	{
		return $this->hasMany(User::class,'clinic_id')->where('role_id',2);
	}



}