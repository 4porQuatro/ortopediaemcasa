<?php

namespace App\Models\Store;

use App\Lib\Model;

class OrderItem extends Model
{
	protected $table = "store_order_items";

	/**
	 * The item's order
	 *
	 * @return Order
	 */
	public function order(){
		return $this->belongsTo(Order::class);
	}
}
