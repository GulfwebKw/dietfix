<?php
namespace App\Models;
use App\Models\Base as  Model;
class UserSubscriptionHold extends Model {

	/**

	 * The database table used by the model.

	 *

	 * @var string

	 */

	protected $table      = 'users_subscription_hold';
	protected $primaryKey = 'id';
	public    $timestamps = false;


}