<?php


namespace App\Models\Frontend;
use App\Models\Base as  Model;
class Contact extends Model
{
    protected $table='contact_info';
    protected $guarded=['id'];
    public $timestamps=false;

    public static $rules =[
        'mobile' => 'required|min:5|max:15',
        'telephone' => 'required|min:5|max:15',
        'fax' => 'required|min:5|max:15',
        'email' => 'required|email',
        'address' => 'required',
//        'lat' => 'required',
//        'lang' => 'required',
    ];

}