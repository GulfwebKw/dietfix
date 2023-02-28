<?php

namespace App\Models\Clinic;

use App\Models\Base as Model;

class Gift extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'gifts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = array('titleAr', 'titleEn', 'image', 'active');

    public static $rules = array(
        'titleAr' => 'required|min:2|max:200',
        'titleEn' => 'required|min:2|max:200',
        'image' => 'min:5',
        'active' => 'in:1,0',
    );

    public function payments()
	{
		return $this->hasMany(Payment::class,'gift_id');
	}
}
