<?php

namespace App\Models;
use App\Models\Base as  Model;
class MembershipFollowups extends Model {



	/**

	 * The database table used by the model.

	 *

	 * @var string

	 */

	protected $table = 'membership_followups';

	protected $primaryKey = 'id';

	public $timestamps = true;



	protected $fillable = array('user_id','note');



	public static $rules = array(

		'user_id' => 'required',

		'note' => 'required',
	);







}