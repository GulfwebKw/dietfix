<?php

namespace App\Models;
use App\Models\Base as  Model;

class Delivery extends Model {

    protected $table = 'delivery_type';

    protected $primaryKey = 'id';



    public $timestamps = false;



    protected $fillable = array('id','type_en','type_ar');



    public static $rules = array(

        'id' => 'integer|max:10',

        'type_en' => 'max:255',

        'type_ar' => 'max:255',
    );
}
