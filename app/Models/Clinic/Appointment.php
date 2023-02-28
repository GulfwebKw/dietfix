<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class Appointment extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'appointments';
	protected $primaryKey = 'id';
	// public $timestamps = false;

	protected $fillable = array('clinic_id','user_id','doctor_id','date','hour','height','weight','bmi','notes','confirmed');

	public static $rules = array(
		'clinic_id' => 'required|integer',
		'user_id' => 'required|integer',
		'doctor_id' => 'required|integer',
		'date' => 'required|date',
		'hour' => 'required',
		'notes' => '',
		'confirmed' => 'in:1,0',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
	{
		return $this->hasMany(AppointmentFile::class,'appointment_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function doctor()
	{
		return $this->belongsTo(User::class,'doctor_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo(User::class,'user_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function clinic()
	{
		return $this->belongsTo(Clinic::class);
	}


}