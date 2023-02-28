<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class Portion extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'portions';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn');

	public static $rules = array(
		'titleAr' => 'required|min:1|max:200',
		'titleEn' => 'required|min:1|max:200',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
	{
		return $this->hasMany(Order::class,'portion_id');
	}


}