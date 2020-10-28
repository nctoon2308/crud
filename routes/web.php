<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/',"Backend\DashboardController@index");

//crud
Route::get('/backend/product/index',"Backend\ProductsController@index");
Route::get('/backend/product/delete/{id}',"Backend\ProductsController@delete");
Route::post('/backend/product/destroy/{id}',"Backend\ProductsController@destroy");
Route::get('/backend/product/edit2/{id}',"Backend\ProductsController@edit2");
Route::post('/backend/product/update/{id}',"Backend\ProductsController@update");
Route::get('/backend/product/create',"Backend\ProductsController@create");


//lưu sản phẩm
Route::post('/backend/product/store',"Backend\ProductsController@store");


