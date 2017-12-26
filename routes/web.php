<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::prefix('products')->group(function(){
    Route::get('/', 'ProductsController@index');
    Route::get('/detail', 'ProductsController@show');
});

Route::get('/about', 'AboutController@index');
Route::get('/faqs', 'FaqsController@index');
Route::get('/contacts', 'ContactsController@index');
Route::get('/politics', 'PoliticsController@index');
Route::get('/conditions', 'ConditionsController@index');
Route::get('/search', 'SearchController@index');