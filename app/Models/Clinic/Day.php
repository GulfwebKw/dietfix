<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use App\Models\User;

class Day extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'days';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','day_number','ordering','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'day_number' => 'in:0,1,2,3,4,5,6',
		'ordering' => 'numeric',
		'active' => 'in:1,0',
	);

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function times()
	{
		return $this->hasMany(DoctorTime::class,'day_id');
	}


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
	{
		return $this->hasMany(Order::class,'day_id');
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
	{
		return $this->belongsToMany(Item::class,'items_days','day_id','item_id')
					->where('active',1);
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function doctors()
	{
		return $this->belongsToMany(User::class,'doctors_times','day_id','doctor_id');
	}

	public function hasItem($id)
	{
	    return ! $this->items->filter(function($item) use ($id)
	    {
	        return $item->id == $id;
	    })->isEmpty();
	}

}