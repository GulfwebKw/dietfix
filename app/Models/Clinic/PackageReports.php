<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class PackageReports extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'packagereports';
	protected $primaryKey = 'id';
	public $timestamps = false;

	protected $fillable = array('packageid','total_users','package_name');

	public static $rules = array(
		'package_name' => 'required|min:2|max:200'
	);
	
}