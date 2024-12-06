<?php

use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::get('/', function () {
    return redirect()->route('swagger.docs');
});

Route::get('/api-docs', '\L5Swagger\Http\Controllers\SwaggerController@api')->name('swagger.docs');
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);
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
Route::get('/api/countries/GetAllCountries', '\App\Http\Controllers\CountryController@GetAllCountries');
Route::get('/api/countries/GetCountry/{id}', '\App\Http\Controllers\CountryController@GetCountry');
Route::post('/api/countries/CreateCountry', '\App\Http\Controllers\CountryController@CreateCountry');
Route::put('/api/countries/EditCountry', '\App\Http\Controllers\CountryController@EditCountry');
Route::delete('/api/countries/DeleteCountry/{id}', '\App\Http\Controllers\CountryController@DeleteCountry');
Route::get('/api/cities/GetAllCities', '\App\Http\Controllers\CityController@GetAllCities');
Route::get('/api/cities/GetAllCitiesByCountryId/{countryId}', '\App\Http\Controllers\CityController@GetAllCitiesByCountryId');
Route::get('/api/cities/GetCity/{id}', '\App\Http\Controllers\CityController@GetCity');
Route::post('/api/cities/CreateCity', '\App\Http\Controllers\CityController@CreateCity');
Route::put('/api/cities/EditCity', '\App\Http\Controllers\CityController@EditCity');
Route::delete('/api/cities/DeleteCity/{id}', '\App\Http\Controllers\CityController@DeleteCity');
Route::get('/api/districts/GetAllDistricts', '\App\Http\Controllers\DistrictController@GetAllDistricts');
Route::get('/api/districts/GetAllDistrictsByCityId/{cityId}', '\App\Http\Controllers\DistrictController@GetAllDistrictsByCityId');
Route::get('/api/districts/GetDistrict/{id}', '\App\Http\Controllers\DistrictController@GetDistrict');
Route::post('/api/districts/CreateDistrict', '\App\Http\Controllers\DistrictController@CreateDistrict');
Route::put('/api/districts/EditDistrict', '\App\Http\Controllers\DistrictController@EditDistrict');
Route::delete('/api/districts/DeleteDistrict/{id}', '\App\Http\Controllers\DistrictController@DeleteDistrict');
Route::get('/api/dictionaries/GetAllDictionaries/{lang}', '\App\Http\Controllers\DictionaryController@GetAllDictionaries');
Route::get('/api/dictionaries/GetDictionary/{id}/{lang}', '\App\Http\Controllers\DictionaryController@GetDictionary');
Route::post('/api/dictionaries/CreateDictionary', '\App\Http\Controllers\DictionaryController@CreateDictionary');
Route::put('/api/dictionaries/EditDictionary', '\App\Http\Controllers\DictionaryController@EditDictionary');
Route::delete('/api/dictionaries/DeleteDictionary/{id}', '\App\Http\Controllers\DictionaryController@DeleteDictionary');
