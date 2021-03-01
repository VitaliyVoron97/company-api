<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'user'], function () {
    Route::post('register', 'AuthController@register');
    Route::post('sign-in', 'AuthController@signIn');

    Route::get('companies', 'CompaniesController@show');
    Route::post('companies', 'CompaniesController@add');
});
