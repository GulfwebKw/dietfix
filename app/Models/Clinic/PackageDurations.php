<?php

namespace App\Models\Clinic;
use App\Models\Base as  Model;
class PackageDurations extends Model
{
    protected $table = 'package_duration';
    protected $guarded=['id'];
    protected $primaryKey = 'id';
    public $timestamps = false;
    public static $rules = array(
        'titleAr' => 'required|min:2|max:200',
        'titleEn' => 'required|min:2|max:200',
        'price' => 'numeric',
        'package_id' => 'numeric',
        'count_day' => 'numeric|min:1',
        'price_after_discount' => 'nullable|numeric|min:1',
        'active' => 'in:1,0',
    );

    public function package()
    {
        return $this->belongsTo(Package::class,'package_id');
    }



}
