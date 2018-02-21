<?php

namespace App\Models\Store;

use \Carbon\Carbon;

use App\Lib\Model;

class Order extends Model
{
	protected $table = "store_orders";

	/**
	 * Get order items collection
	 *
	 * @return Collection
	 */
	public function items(){
		return $this->hasMany(OrderItem::class);
	}

	/**
	 * Relation: User
	 *
	 * @return User The user who placed the order
	 */
	public function user(){
		return $this->belongsTo(\App\Models\User::class);
	}

	/**
	 * Relation: Order state
	 * 
	 * @return StoreOrderState The state of the order
	 */
	public function state(){
		return $this->belongsTo(OrderState::class);
	}

    /**
     * Payment reference.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
	public function paymentReference()
    {
        return $this->hasOne(PaymentReference::class);
    }

	public function getFormattedCreatedAtAttribute(){
		return utf8_encode(Carbon::parse($this->created_at)->formatLocalized('%d.%m.%Y'));
	}
}
