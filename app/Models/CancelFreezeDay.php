<?php

namespace App\Models;
use App\Models\Base as  Model;


class CancelFreezeDay extends Model {

	protected $table = 'UserMeta.php';
	protected $primaryKey = 'user_id';
	public $timestamps = false;

	protected $fillable = array('user_id','freezed_ending_date','freezed_starting_date','isFreezed', 'isAutoUnFreezed');
	protected $casts = array(
        'freezed_ending_date' => 'datetime',
        'freezed_starting_date' => 'datetime',
        'isFreezed' => 'boolean',
        'isAutoUnFreezed' => 'boolean',
    );

	public function user(){
		return $this->belongsTo(User::class);
	}
}
