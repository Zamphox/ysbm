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

Route::group(['middleware'=>'web'], function(){
	Route::get('/login', ['as'=>'login', 'uses'=>'LoginController@show']);
	Route::post('/login', ['uses'=>'LoginController@login']);
	Route::get('/logout',['as'=>'logout', 'uses'=>'LoginController@logout']);
	Route::post('/shipment/{id}/add',['uses'=> 'ShipmentController@add']);
	Route::get('/shareToken',['uses' => 'ShipmentController@shareToken']);
});


Route::group(['middleware'=>['CheckToken','web']], function(){
	Route::get('/',['as'=>'home', 'uses'=> 'IndexController@show']);
	Route::get('/shipment/{id}', ['as'=>'shipment', 'uses'=>'ShipmentController@show']);
	Route::get('/shipment/{id}/delete', ['uses' => 'ShipmentController@delete']);
	Route::post('/', ['uses'=> 'IndexController@newShipment']);

});