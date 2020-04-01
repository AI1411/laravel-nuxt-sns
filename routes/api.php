<?php

use Illuminate\Support\Facades\Route;

//public routes


//Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
   //
});

//Routes for guest only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
});
