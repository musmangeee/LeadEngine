<?php

Route::get('/permission/get-all-roles-with-permission-in-group', 'Api\RolesPermissionController@getAllRolesWithPermissionInGroup');
Route::get('/permission/get-all-permission-in-group', 'Api\RolesPermissionController@getAllPermissionInGroup');
