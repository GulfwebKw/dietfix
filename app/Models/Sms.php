<?php


namespace App\Models;
use App\Models\Base as  Model;
class Sms extends Model
{
    protected $table = 'sms';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded=['id'];

    public static $rules = array(
        'contentAr' => 'min:2|max:2000',
        'contentEn' => 'min:2|max:2000'
    );



}