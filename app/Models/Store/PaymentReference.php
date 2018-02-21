<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class PaymentReference extends Model
{
    protected $fillable = [
        'order_id',
        'entity',
        'reference',
        'amount'
    ];

    /**
     * The reference's order.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
