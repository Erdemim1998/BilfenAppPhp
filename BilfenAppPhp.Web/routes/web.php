<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route("login");
});

Route::get('/login', '\App\Http\Controllers\UserController@login')->name('login');
Route::get('/dashboard', '\App\Http\Controllers\DashboardController@index');
Route::post('/api/upload', '\App\Http\Controllers\FileUploadController@UploadFile');
