<?php


namespace App\Models;
use App\Models\Base as  Model;
class ReferralUser extends Model
{
    protected $table = 'referral_user';
    protected $guarded=['id'];
    public $timestamps = false;

}