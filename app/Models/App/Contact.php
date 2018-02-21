<?php

namespace App\Models\App;

use App\Lib\Model;
use App\Traits\LapBootTrait;

class Contact extends Model
{
    use LapBootTrait;

    protected $table = "app_contacts";

    /**
     *	Get language
     *
     *	@return Language
     */
    public function language()
    {
        return $this->belongsTo(\App\Models\Language::class);
    }
}
