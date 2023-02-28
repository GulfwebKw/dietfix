<?php


namespace App\Models;
use App\Models\Base as  Model;
class City extends Model
{
    protected $table = 'city';
    protected $primaryKey = 'id';
    public $timestamps = false;


}