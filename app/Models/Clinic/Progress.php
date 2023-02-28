<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
use Illuminate\Support\Facades\File;

class Progress extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_week_progress';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $guarded=['id'];

}
