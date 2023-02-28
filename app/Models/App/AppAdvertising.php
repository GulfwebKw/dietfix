<?php


namespace App\Models\App;
use App\Models\Base as  Model;
class AppAdvertising extends Model
{
    protected $table = 'app_advertising';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded=['id'];
    public static $rules = array(

        'photoEn' => '',
        'photoAr' => '',
        'link' => '',

    );

}