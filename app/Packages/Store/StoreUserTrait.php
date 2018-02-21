<?php

namespace App\Packages\Store;

use App\Models\Store\Order;

trait StoreUserTrait
{
    /**
     * User orders.
     *
     * @return mixed
     */
    public function orders(){
        return $this->hasMany(Order::class)->latest();
    }

    /**
     * Calculates the amount of points for this users' orders that have received payment.
     *
     * @return mixed
     */
    private function getPointsEarned()
    {
        return $this->orders()->where('state_id', 2)->sum('points_earned');
    }

    /**
     * Calculates the amount of points spent for the orders that are or waiting for payment or already paid.
     *
     * @return mixed
     */
    private function getPointsSpent()
    {
        return $this->orders()->whereIn('state_id', [1, 2])->sum('points_spent');
    }

    /**
     * calculates the available points this user can spend on a purchase.
     *
     * @return mixed
     */
    public function getAvailablePoints()
    {
        return $this->getPointsEarned() - $this->getPointsSpent();
    }
}