<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConditionsController extends Controller
{
    public function index()
    {
        return view('front.pages.conditions.index', []);
    }

}
