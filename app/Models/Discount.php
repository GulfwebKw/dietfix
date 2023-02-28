<?php

namespace App\Models;
use App\Models\Base as  Model;
class Discount extends Model
{
    protected $table = 'discounts';
    protected $guarded=['id'];
    protected $primaryKey = 'id';
    public static $rules = array(
        'titleAr' => 'required|min:2|max:400',
        'titleEn' => 'required|min:2|max:400',
        'code' => 'required|min:4|max:20',
        'min_price_order' => 'numeric',
        //'package_id' => 'numeric',
        'count_day' => 'numeric|min:1',
        'value' => 'required|numeric',
        'count_limit' => 'required|numeric',
        'count_limit_user' => 'required|numeric',
        'start' => 'required|date',
        'end' => 'required|date|after_or_equal:start',
        'active' => 'in:1,0',
    );




}
