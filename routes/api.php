<?php

Route::post('login', 'AuthController@login');
Route::put('verify-account', 'AuthController@emailVerify');
Route::post('/email/verify', ['as' => 'email.verify', 'uses' => 'AuthController@emailVerify']);
Route::group(['prefix' => 'password'], function () {
    Route::post('forgot', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('reset', 'Auth\ResetPasswordController@reset')->name('password.reset');
});

Route::group(['middleware' => ['auth.jwt', 'authenticated']], function () {
    Route::post('/user/verification', ['as' => 'user.verification', 'uses' => 'AuthController@emailRequestVerification']);
    Route::get('auth/verify-token', 'AuthController@verifyToken');
    Route::get('logout', 'AuthController@logout');
    Route::post('update-password', 'AuthController@updatePassword');
    Route::put('update-user/{user}', 'UserController@update');
    Route::get('load-env-settings', 'EnvSettingsController@load');
    Route::put('env-config', 'EnvSettingsController@update');

    Route::get('load-menu', 'MenuController@load');

    require_once __DIR__ . "/crud-routes.php";
});

