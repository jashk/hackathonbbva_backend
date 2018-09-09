<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/merchants', 'MerchantController@list');
Route::post('/merchants', 'MerchantController@add');
Route::post('/merchants/{id}/approvals', 'MerchantController@approvals');
Route::post('/merchants/{id}/qr', 'MerchantController@getQR');


Route::prefix('verify/')->group(function () {
    Route::post('start', 'VerifyController@startVerification');
    Route::post('verify', 'VerifyController@verifyCode');
});


Route::get('/products', 'ProductController@list');
Route::get('/products/{id}', 'ProductController@listByMerchant');

Route::get('/product/{id}', 'ProductController@getProduct');
Route::post('/product/add', 'ProductController@add');
Route::post('/product/{id}/update', 'ProductController@update');
Route::delete('/product/{id}', 'ProductController@delete');

Route::get('/code/generate', 'QRController@generate');



