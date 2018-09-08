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


Route::prefix('verify/')->group(function () {
    Route::post('start', 'VerifyController@startVerification');
    Route::post('verify', 'VerifyController@verifyCode');
});
