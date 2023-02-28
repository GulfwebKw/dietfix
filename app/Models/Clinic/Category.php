<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class Category extends Model{

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'categories';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','meal_id','ordering','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'ordering' => 'numeric',
		'meal_id' => 'required|integer',
		'active' => 'in:1,0',
	);

	public function meal()
	{
		return $this->belongsTo(Meal::class);
	}

	public function items()
	{
		return $this->hasMany(Item::class,'category_id')
					->where('active',1);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
	{
		return $this->hasMany(Order::class,'category_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages()
	{
		return $this->belongsToMany(Package::class,'packages_categories','category_id','package_id');
	}

}