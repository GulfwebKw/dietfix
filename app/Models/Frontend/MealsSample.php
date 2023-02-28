<?php
namespace App\Models\Frontend;
use App\Models\Base as  Model;
class MealsSample extends Model
{

    protected $table='meals_sample';
    protected $guarded=['id'];
    public $timestamps=false;

    public static $rules = array(
        'photo' => '',
    );






}