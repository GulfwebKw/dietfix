<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class Area extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'areas';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','province_id','ordering','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:100',
		'titleEn' => 'required|min:2|max:100',
		'province_id' => 'required|integer',
		'ordering' => 'integer',
		'active' => 'in:0,1',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
	{
		return $this->belongsTo(Province::class);
	}

	public function users()
	{
		return $this->hasMany(User::class,'area_id');
	}
	

}