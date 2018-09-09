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

Route::get('/', function () {
    return view('welcome');
});

Route::get('file/{filename}', function ($filename) {
    //dd(Storage::get($filename));
    return Storage::url($filename);

    //return response()->download(, null, [], null);
})->where('filename', '^(.+)\/([^\/]+)$');