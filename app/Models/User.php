<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use App\Packages\Store\StoreUserTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Store\Order;

class User extends Authenticatable
{
    use Notifiable, StoreUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'billing_name',
        'billing_phone',
        'billing_address',
        'billing_city',
        'billing_zip_code',
        'billing_country_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'shipping_city',
        'shipping_zip_code',
        'shipping_country_id',
        'vat_number',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    /**
     * Get billing country
     *
     * @return Country
     */
    public function billingCountry()
    {
        return $this->belongsTo(\App\Models\Geo\Country::class, 'billing_country_id');
    }

    /**
     * Get shipping country
     *
     * @return Country
     */
    public function shippingCountry()
    {
        return $this->belongsTo(\App\Models\Geo\Country::class, 'shipping_country_id');
    }

    /**
     * Get related users
     *
     * @return Collection
     */
    public function items()
    {
        return $this->belongsToMany(Items\Item::class, 'wishlist_items', 'user_id', 'item_id');
    }


    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
	 * Get users' first name
	 */
    public function getFirstNameAttribute()
    {
        $name_pieces = explode(" ", $this->billing_name);

        return $name_pieces[0];
    }


    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }
}
