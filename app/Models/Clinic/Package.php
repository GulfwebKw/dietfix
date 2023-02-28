<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class Package extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packages';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','detailsAr','detailsEn','price','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'detailsAr' => '',
		'detailsEn' => '',
		'price' => 'numeric|min:1',
		'price_after_discount' => 'numeric|min:1',
		'active' => 'in:1,0',
	);


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
	{
		return $this->hasMany(User::class,'package_id');
	}

	public function payments()
	{
		return $this->hasMany(Payment::class,'package_id');
	}

    public function packageDurations()
    {
        return $this->hasMany(PackageDurations::class,'package_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function meals()
	{
		return $this->belongsToMany(Meal::class,'packages_meals','package_id','meal_id')
						->orderBy('ordering','asc');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
	{
		return $this->belongsToMany(Category::class,'packages_categories','package_id','category_id')
						->orderBy('ordering','asc');
	}

	public function hasMeal($id)
	{
	    return ! $this->meals->filter(function($meal) use ($id)
	    {
	        return $meal->id == $id;
	    })->isEmpty();
	}

	public function hasCategory($id)
	{
	    return ! $this->categories->filter(function($category) use ($id)
	    {
	        return $category->id == $id;
	    })->isEmpty();
	}
}
