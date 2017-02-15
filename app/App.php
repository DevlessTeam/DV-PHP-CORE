<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class App extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'token'];

    /**
     * Get app key
     *
     * @return string
     */
    public static function get_key()
    {
        return str_random(40);
    }

    /**
     * Get app token
     *
     * @return string
     */
    public static function get_token()
    {
        return md5(uniqid(1, true));
    }
}
