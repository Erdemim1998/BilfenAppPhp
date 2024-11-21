<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('swagger.docs');
});

Route::get('/api-docs', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('swagger.docs');

Route::get('/api/users/GetAllUsers', '\App\Http\Controllers\UserController@GetAllUsers');
Route::get('/api/users/GetUser/{id}', '\App\Http\Controllers\UserController@GetUser');
Route::post('/api/users/Login', '\App\Http\Controllers\UserController@Login');
Route::post('/api/users/CreateUser', '\App\Http\Controllers\UserController@CreateUser');
Route::put('/api/users/EditUser', '\App\Http\Controllers\UserController@EditUser');
Route::delete('/api/users/DeleteUser/{id}', '\App\Http\Controllers\UserController@DeleteUser');
Route::get('/api/users/GetVapidKeys', '\App\Http\Controllers\UserController@GetVapidKeys');
Route::post('/api/users/SendNotification', '\App\Http\Controllers\UserController@SendNotification');
Route::post('/api/users/SendEmail', '\App\Http\Controllers\UserController@SendEmail');
Route::get('/api/documents/GetAllDocuments', '\App\Http\Controllers\DocumentController@GetAllDocuments');
Route::get('/api/documents/GetAllDocumentsByUserId/{userId}', '\App\Http\Controllers\DocumentController@GetAllDocumentsByUserId');
Route::get('/api/documents/GetDocument/{id}', '\App\Http\Controllers\DocumentController@GetDocument');
Route::post('/api/documents/CreateDocument', '\App\Http\Controllers\DocumentController@CreateDocument');
Route::put('/api/documents/EditDocument', '\App\Http\Controllers\DocumentController@EditDocument');
Route::delete('/api/documents/DeleteDocument/{id}', '\App\Http\Controllers\DocumentController@DeleteDocument');
Route::get('/api/roles/GetAllRoles', '\App\Http\Controllers\RoleController@GetAllRoles');
Route::get('/api/roles/GetRole/{id}', '\App\Http\Controllers\RoleController@GetRole');
Route::post('/api/roles/CreateRole', '\App\Http\Controllers\RoleController@CreateRole');
Route::put('/api/roles/EditRole', '\App\Http\Controllers\RoleController@EditRole');
Route::delete('/api/roles/DeleteRole/{id}', '\App\Http\Controllers\RoleController@DeleteRole');
