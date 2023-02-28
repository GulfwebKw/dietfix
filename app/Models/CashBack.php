<?php


namespace App\Models;
use App\Models\Base as  Model;
class CashBack extends Model
{
    protected $table="cash_back";
    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

}
