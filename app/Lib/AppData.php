<?php

namespace App\Lib;


class AppData
{
    public static function getUserMenus()
    {
        return [
            [
                'href' => 'user-welcome',
                'title' => trans('app.home')
            ],
            [
                'href' => 'user-orders',
                'title' => trans('app.orders')
            ],
//            [
//                'href' => 'user-favourites',
//                'title' => trans('app.favorites')
//            ],
            [
                'href' => 'user-profile',
                'title' => trans('app.edit-profile')
            ],
            [
                'href' => 'user-password',
                'title' => trans('app.change-password')
            ],
        ];
    }
}
