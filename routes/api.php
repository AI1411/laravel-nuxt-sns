<?php

use Illuminate\Support\Facades\Route;

//public routes
Route::get('me', 'User\MeController@getMe');

Route::get('designs', 'Designs\DesignController@index');

Route::get('users', 'User\UserController@index');

//Route group for authenticated users only
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::put('settings/profile', 'User\SettingsController@updateProfile');
    Route::put('settings/password', 'User\SettingsController@updatePassword');

    Route::post('designs', 'Designs\UploadController@upload');
    Route::put('designs/{id}', 'Designs\DesignController@update');
    Route::delete('designs/{id}', 'Designs\DesignController@destroy');
});

//Routes for guest only
Route::group(['middleware' => ['guest:api']], function () {
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('/verification/verify/', 'Auth\VerificationController@verify')->name('verification.verify');
    Route::post('verification/resend', 'Auth\VerificationController@resend');
    Route::post('login', 'Auth\LoginController@login');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
});

