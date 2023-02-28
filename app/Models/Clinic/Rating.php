<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use Illuminate\Support\Facades\File;

class Rating extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'rating_food';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $guarded=['id'];


    public function category()
    {
        return $this->belongsTo(Item::class);
    }


}
