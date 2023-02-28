<?php

namespace App\Models;
use App\Models\Base as  Model;
class Setting extends Model
{

    protected $table='settings';
    protected $guarded=['id'];
    public $timestamps=false;
    public static $rules =[

        'key' => 'required|alpha_num|min:2|max:255',

        'value' => '',

        'help' => '',
    ];
    public static  function getSetting()
    {
        return Setting::select(['value','help','key'])->get()->keyBy('key')->toArray();
    }
    /**
     * Add a settings value
     *
     * @param $key
     * @param $val
     * @param string $help
     * @return bool
     */
    public static function add($key, $val, $help='')
    {
        if ( self::has($key) ) {
            return self::set($key, $val,$help);
        }

        return self::create(['key' => $key, 'value' => $val, 'help' => $help]) ? $val : false;
    }
    /**
     * Get a settings value
     *
     * @param $key
     * @param null $default
     * @return bool|mixed
     */
    public static function get($key, $default = null)
    {
        if ( self::has($key) ) {
          return  $setting = self::getAllSettings()->where('key', $key)->first();
        }

        return $default;
    }
    /**
     * Set a value for setting
     *
     * @param $key
     * @param $val
     * @param string $help
     * @return bool
     */
    public static function set($key, $val, $help = 'string')
    {
        if ( $setting = self::getAllSettings()->where('name', $key)->first() ) {
            return $setting->update([
                'key' => $key,
                'value' => $val,
                'help' => $help]) ? $val : false;
        }

        return self::add($key, $val,$help);
    }
    /**
     * Remove a setting
     *
     * @param $key
     * @return bool
     */
    public static function remove($key)
    {
        if( self::has($key) ) {
            return self::wherekey($key)->delete();
        }

        return false;
    }
    /**
     * Check if setting exists
     *
     * @param $key
     * @return bool
     */
    public static function has($key)
    {
        return (boolean) self::getAllSettings()->whereStrict('key', $key)->count();
    }
    /**
     * Get the validation rules for setting fields
     *
     * @return array
     */
    public static function getValidationRules()
    {
        return self::$rules;
    }
    /**
     * Get all the settings
     *
     * @return mixed
     */
    public static function getAllSettings()
    {
        return self::all();
    }
}
