<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class Order extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'orders';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array(
		'user_id',
		'day_id',
		'meal_id',
		'category_id',
		'item_id',
		'portion_id',
		'approved',
		'updated_on'
		);

	public static $rules = array(
		'user_id' => 'required|integer',
		'day_id' => 'required|integer',
		'meal_id' => 'required|integer',
		'category_id' => 'required|integer',
		'item_id' => 'required|integer',
		'portion_id' => 'integer',
		'approved' => 'integer'	
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
    public function day()
	{
		return $this->belongsTo(Day::class);
	}
    public function date()
    {
        return $this->belongsTo(UserDate::class,'date_id');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function meal()
	{
		return $this->belongsTo(Meal::class)->orderBy('ordering','asc');;
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portion()
	{
		return $this->belongsTo(Portion::class);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
	{
		return $this->belongsTo(Category::class);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
	{
		return $this->belongsTo(Item::class);
	}


	public function addons()
	{
		return $this->belongsToMany(Addon::class,'orders_addons','order_id','addon_id');
	}



}