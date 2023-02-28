<?php


namespace App\Models\Frontend;
use App\Models\Base as  Model;
class UserContact extends Model
{
    protected $table='user_contacts';
    protected $guarded=['id'];
    public $timestamps=false;


}