<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/', function (Request $request) {
    return $request->user();
});

Route::get('barangmasuk','BarangMasukController@get_barang')->name('barangmasuk.get_barang');
// Route::get('barangkeluar','BarangKeluarController@get_barangk')->name('barangkeluar.get_barang');
// Route::get('qrcode','StokBarangController@qrcodeApi')->name('stokbarang.qrcodeApi');

