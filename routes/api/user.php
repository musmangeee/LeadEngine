<?php

Route::get('/users/profile', 'Api\UserController@profile');
Route::post('/users/profile', 'Api\UserController@updateProfile');
Route::get('/users/get-all', 'Api\UserController@getAll');
Route::get('/user-getRolesAndPermissions', 'Api\UserController@getRolesAndPermissions');
Route::get('/user-options', 'Api\UserController@getOptions');
Route::middleware(['permission:User.update'])->post('/user/disable', 'Api\UserController@disable');
Route::middleware(['permission:User.update'])->post('/user/activate', 'Api\UserController@activate');


Route::middleware(['permission:User.read'])->get('/listuser', 'Api\UserController@list');
Route::middleware(['permission:User.create'])->post('/user', 'Api\UserController@store');
Route::middleware(['permission:User.update'])->post('/user/{id}', 'Api\UserController@update');
Route::middleware(['permission:User.read'])->get('/user/{id}', 'Api\UserController@show');
Route::middleware(['permission:User.delete'])->delete('/user/{id}', 'Api\UserController@destroy');
