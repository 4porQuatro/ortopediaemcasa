<?php

namespace App\Models\Items;

use App\Lib\Model;
use App\Traits\LapBootTrait;


class ItemBrand extends Model
{
    use LapBootTrait;

    /**
     *	Get language.
     *
     *	@return Language
     */
    public function language()
    {
        return $this->belongsTo(\App\Models\Language::class);
    }

    /**
     * Get items.
     *
     * @return Relation
     */
    public function items(){
        return $this->hasMany(Item::class);
    }
}
