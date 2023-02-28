<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class UserDateTemp extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table      = 'user_dates_temp';
	protected $primaryKey = 'id';
	public $timestamps    = false;
	protected $fillable   = array('date','user_id');
	public static $rules  = array(
		'date'    => 'required|date',
		'user_id' => 'required|integer',
	);
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo(User::class);
	}
    public function orders()
    {
        return $this->hasMany(OrderTemp::class,'date_id');
	}

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id');
	}



}