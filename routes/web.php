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
Route::get('/media/{path}', 'Api\AttachmentController@showFile')
    ->where('path', '.+')
    ->name('tenant.media');

Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');


Route::get('login', 'AuthenticationController@login')->name('login');
Route::post('login', 'AuthenticationController@doLogin')->name('do-login');
Route::post('logout', 'AuthenticationController@logout')->name('logout');

Route::get('password/forgot', 'AuthenticationController@forgotPassword')->name('password.forgot');
Route::post('password/forgot', 'AuthenticationController@doForgotPassword')->name('password.do-forgot');

Route::get('password/reset', 'AuthenticationController@resetPassword')->name('password.reset');
Route::post('password/reset', 'AuthenticationController@doResetPassword')->name('password.do-reset');
Route::get('/csrf', function () {
    return response(csrf_token());
});

//Redirect Error Page from Redirect Module
Route::get('redirects/invalid-error', function () {
    return view('errors.invalid');
});

Route::get('redirects/time-limit-error', function () {
    return view('errors.time-limit');
});

Route::get('redirects/ip-address-error', function () {
    return view('errors.ip-address');
});

Route::get('redirects/click-limit-error', function () {
    return view('errors.click-limit');
});

Route::get('providers/{id}/post-instruction', 'Api\ProviderController@postInstruction');

Route::get('/{any}', 'ApplicationController')->where('any', '^(?!public).*$');
