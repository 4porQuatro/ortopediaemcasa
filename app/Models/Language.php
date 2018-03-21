<?php

namespace App\Models;

use App\Lib\Model;
use App\Models\Items\ItemCategory;
use App\Models\Items\Item;

class Language extends Model
{
    protected $fillable = ['iso', 'language'];

    /**
     * Builds an array with the active remaining languages
     * @return array
     */
    public function otherLangsArr()
    {
        return $this->where('active', 1)->where('iso', '!=', $this->iso)->orderBy('priority')->pluck('iso')->toArray();
    }

    /**
     * Get an array of active locales
     * @return array
     */
    public static function localesArr()
    {
        $locales_arr = self::select('iso')->where('active', 1)->orderBy('priority')->pluck('iso')->toArray();

        return $locales_arr;
    }

    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
