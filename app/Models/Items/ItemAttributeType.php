<?php

namespace App\Models\Items;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LapBootTrait;

class ItemAttributeType extends Model
{
    use LapBootTrait;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language()
    {
        return $this->belongsTo(\App\Models\Language::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function itemCategory()
    {
        return $this->belongsTo(ItemCategory::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function itemAttributeValues()
    {
        return $this->hasMany(ItemAttributeValue::class);
    }
}
