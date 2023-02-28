<?php
namespace App\Models;
use App\Models\Base as  Model;
use App\Models\Clinic\Package;
use App\Models\Clinic\PackageDurations;

class Invoice extends Model {
    protected $guarded=['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function packageDuration()
    {
        return $this->belongsTo(PackageDurations::class,'package_duration_id');
    }





}