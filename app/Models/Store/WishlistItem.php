<?php

namespace App\Models;

use App\Lib\Model;

class WishlistItem extends Model
{
    protected $fillable = [
        'language_id',
        'item_id',
        'user_id'
    ];


    /**
	 * Relation: Item
	 *
	 * @return Item
	 */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
	 * Relation: User
	 *
	 * @return User
	 */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
