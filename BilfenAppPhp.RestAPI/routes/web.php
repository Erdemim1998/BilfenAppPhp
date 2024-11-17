<?php

use Illuminate\Support\Facades\Route;

Route::get('/api-docs', '\L5Swagger\Http\Controllers\SwaggerController@api');

//Route::get('/api-docs', function () {
//    return view('vendor.l5-swagger.index');
//});
