<?php

namespace App\Models\Newsletter;

use App\Lib\Model;

class Subscriber extends Model
{
	protected $table = "newsletter_subscribers";

	protected $fillable = ['email'];
}
