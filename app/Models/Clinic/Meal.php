<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class Meal extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'meals';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = array('titleAr','titleEn','ordering','active');
	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'ordering' => 'numeric',
		'active' => 'in:1,0',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
	{
		return $this->hasMany(Category::class,'meal_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
	{
		return $this->hasMany(Order::class,'meal_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
	{
		return $this->belongsToMany(Package::class,'packages_meals','meal_id','package_id');
	}

}