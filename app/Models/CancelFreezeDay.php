<?php

namespace App\Models;
use App\Models\Base as  Model;


class CancelFreezeDay extends Model {

	protected $table = 'UserMeta.php';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	protected $fillable = array('user_id','resume_at');
	protected $casts = array('resume_at' => 'datetime');

	public function user(){
		return $this->belongsTo(User::class);
	}
}
