<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class DoctorTime extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'doctors_times';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('doctor_id','day_id','start','end');

	public static $rules = array(
		'start' => 'required',
		'end' => 'required',
		'doctor_id' => 'required|integer',
		'day_id' => 'required|integer',
	);


	public function doctor()
	{
		return $this->belongsTo(User::class,'doctor_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function day()
	{
		return $this->belongsTo(Day::class,'day_id');
	}

}