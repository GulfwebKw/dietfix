<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use Illuminate\Support\Facades\File;

class Item extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'items';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('titleAr','titleEn','photo','detailsAr','detailsEn','category_id','active');

	public static $rules = array(
		'titleAr' => 'required|min:2|max:200',
		'titleEn' => 'required|min:2|max:200',
		'photo' => 'min:5',
		'detailsAr' => '',
		'detailsEn' => '',
		'category_id' => 'required|integer',
		'active' => 'in:1,0',
	);

	public static function boot()
    {
        parent::boot();
        
        // Attach event handler, on deleting of the user
        Item::deleting(function($item)
        {   
        	File::delete(public_path('media/items/'.$item->photo));
        });
    }


	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function addons()
	{
		return $this->belongsToMany(Addon::class,'items_addons','item_id','addon_id');
	}

	public function days()
	{
		return $this->belongsToMany(Day::class,'items_days','item_id','day_id');
	}

	public function orders()
	{
		return $this->hasMany(Order::class,'item_id');
	}

}