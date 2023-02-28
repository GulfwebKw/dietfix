<?php


namespace App\Models\App;
use App\Models\Base as  Model;
use Illuminate\Support\Facades\DB;

class AppSlideShow extends Model
{
    protected $table = 'app_slideshow';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = array('titleAr','titleEn','photo','url','active');
    public static $rules = array(

        'titleAr' => 'min:2|max:100',

        'titleEn' => 'min:2|max:100',

        'photo' => '',

        'url' => '',

        'active' => '',

    );


    public static function getList()
    {
        return DB::table('app_slideshow')->select(['*'])->where('active',1)->get();
    }
}