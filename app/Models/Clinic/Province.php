<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;
class Province extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'provinces';
	protected $primaryKey = 'id';
	public $timestamps = false;



	protected $fillable = array('titleAr','titleEn','ordering','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:100',
		'titleEn' => 'required|min:2|max:100',
		'ordering' => 'integer',
		'active' => 'in:0,1',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function areas()
	{
		return $this->hasMany(Area::class,'province_id')->orderBy('title'.LANG,'asc');
	}


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
	{
		return $this->hasMany(User::class,'province_id');
	}


}