<?php

namespace App\Traits;

use App\Scopes\LanguageIdScope;
use App\Scopes\ActiveScope;
use App\Scopes\PrioritySortScope;

trait LapBootTrait
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new LanguageIdScope);
        static::addGlobalScope(new ActiveScope);
        static::addGlobalScope(new PrioritySortScope);
    }
}
