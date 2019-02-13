<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        date_default_timezone_set("Europe/Lisbon");
        setlocale(LC_ALL, config('app.locale') . '_' . strtoupper(config('app.locale')));
        Validator::extend('validVoucher', '\App\Http\Requests\Validators\VoucherValidator@valid');

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
