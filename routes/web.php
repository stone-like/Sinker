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

Route::view('/','home');
Route::view('/{any}','home');//ここでやっていることは結局なんでもhome.baldeを表示するということSPAなのでこれでいいし、中のcomponentはしっかり変わっているからおｋ
Route::view('/{any}/{any1}','home');//この形もlaravelでhome.bladeを表示できるようにする

