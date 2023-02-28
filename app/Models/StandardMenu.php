<?php

namespace App\Models;
use App\Models\Base as  Model;

class StandardMenu extends Model {
	protected $table = 'standard_menus';

	protected $primaryKey = 'id';

	public $timestamps = false;



	protected $fillable = array('standard_menu_nameAr','standard_menu_nameEn', 'titleAr','titleEn','created_at');



	public static $rules = array(

		'standard_menu_nameAr' => 'required|min:2|max:100',

		'standard_menu_nameEn' => 'required|min:2|max:100',

		'created_at' => 'required|min:2|max:100',

	);




/*
	public static function getStandardMenuMenu()

	{

		$standardMenus = DB::table('standard_menus');

		$data2 = $standardMenus::lists('title'.LANG, 'id');

		$data[] = trans('main.Choose');

		// $countries = Country::all();

		// $data = array();

		// $data[0] = trans('main.Choose');


		foreach ($data2 as $k => $v) {

			$data[$k] = $v;

		}
		//var_dump($data);
		//var_dump($data2);
		//var_dump(DB::getQueryLog());

		return $data;

	}

	*/


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()

	{
		return $this->hasMany(User::class,'standard_menu_id');
	}



/*	public function cities()

	{

		return $this->hasMany('City');

	}
*/


}
