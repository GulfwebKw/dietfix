<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\User;

class Payment extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'payments';
	protected $primaryKey = 'id';
	// public $timestamps = false;
    protected $guarded=['id'];



	public static $rules = array(
		'package_id' => 'required|integer',
		'user_id' => 'required|integer',
		'paymentID' => 'required|integer',
		'type' => 'in:knet,credit_card,cash_on_delivery',
		'payment_data' => 'min:2',
		'total' => 'numeric',
		'paid' => 'in:1,0',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
	{
		return $this->belongsTo(User::class);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
	{
		return $this->belongsTo(Package::class);
	}

    public function packaged()
    {
        return $this->belongsTo(PackageDurations::class);
	}

	


}
