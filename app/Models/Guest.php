<?php

namespace App\Models;
use App\Models\Base as  Model;
class Guest extends Model {
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'guest';
	protected $primaryKey = 'id';
	public $timestamps = false;
}