<?php
namespace App\Models;
use App\Models\Base as  Model;

class Logs extends Model {



    /**

     * The database table used by the model.

     *

     * @var string

     */

    protected $table = 'log_system';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded=['id'];




    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function user()
    {
        return  $this->belongsTo(\App\User::class,'user_id');
    }
    public function admin()
    {
        return  $this->belongsTo(\App\User::class,'admin_id');
    }





}