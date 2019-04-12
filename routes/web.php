<?php

Route::view('/','welcome');
Route::post('statuses','StatusesController@store')->name('statuses.store');
Route::auth();