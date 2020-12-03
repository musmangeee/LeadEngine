<?php

Route::get('/notifications', 'Api\NotificationController@index');
Route::get('/notifications/get-all-unread', 'Api\NotificationController@getAllUnread');
Route::get('/notifications/mark-all-read', 'Api\NotificationController@markAllRead');
Route::get('/notifications/take/{take}', 'Api\NotificationController@getLatest');
Route::get('/notifications/{id}', 'Api\NotificationController@show');
