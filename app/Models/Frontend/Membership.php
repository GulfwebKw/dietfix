<?php


namespace App\Models\Frontend;
use App\Models\Base as  Model;
class Membership extends Model
{
    protected $table='site_page';
    protected $guarded=['id'];
    public $timestamps=false;

}