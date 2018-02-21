<?php
namespace App\Packages\PayPal;

use App\Lib\Model;
use App\Models\Store\Order;

class PaypalPaymentRequest extends Model
{
    protected $fillable = [
        'token',
        'payment_id',
        'order_id'
    ];

    /**
     * This payment order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
