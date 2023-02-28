<?php

namespace App\Models;
use App\Models\Base as  Model;

class FutureRenewalPackage extends Model {

	/**

	 * The database table used by the model.

	 *

	 * @var string

	 */

	protected $table = 'renew_future_package';

	protected $primaryKey = 'id';

	public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()

	{

		return $this->hasOne(User::class,'id','user_id');

	}
	
	public function package()

	{

		return $this->hasOne(Clinic\Package::class,'id','package_id');

	}
	
	public function packageduration()

	{

		return $this->hasOne(Clinic\PackageDurations::class,'id','package_duration_id');

	}
	
	public function payment()

	{

		return $this->hasOne(Clinic\Payment::class,'id','pay_id');

	}


//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasMany
//     */
//    public function aqars()
//
//	{
//
//		return $this->hasMany(Aqar::class);
//
//	}





}