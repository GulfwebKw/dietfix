<?php


namespace App\Models\App;
use App\Models\Base as  Model;
class UserWeekProgress extends Model
{

    protected $table = 'user_week_progress';
    protected $primaryKey = 'id';
    public     $timestamps = false;


}
