<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class AddonTemp extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table      = 'addons';
	protected $primaryKey = 'id';
	public $timestamps    = false;

	protected $fillable = array('titleAr','titleEn','code','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'code'    => '',
		'active'  => 'in:1,0',
	);


	public function items()
	{
		return $this->belongsToMany('Item','items_addons','addon_id','item_id');
	}

	public function orders()
	{
		return $this->belongsToMany('OrderTemp','orders_addons_temp','addon_id','order_id');
	}

}