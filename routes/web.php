<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScanController;

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
  return view('auth.login');
})->middleware('guest');


Route::get('refreshcaptcha', 'Auth\LoginController@refreshCaptcha')->name('refreshcaptcha');

route::get('/scan', [ScanController::class, 'index'])->name('scan');

//`Auth::routes();
Auth::routes([
  'register' => false,
  // Register Routes...
  'reset' => false,
  // Reset Password Routes...
  'verify' => false, // Email Verification Routes...
]);
Route::middleware('auth')->group(function () {

  Route::resource('user', 'UserController');
  Route::get('/admin', 'UserController@admin');

  Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');
  Route::get('edit-password', 'EditProfileController@index')->name('editpswd');
  Route::post('edit-password', 'EditProfileController@store')->name('edit');

  //StokBarang
  Route::get('/stok', 'StokBarangController@main')->middleware('auth')->name('stok'); //->name('home');
  Route::get('/stok/qrcode/{id}', 'StokBarangController@qrcode')->name('qrcodestok');
  Route::get('/stok/detail/{id}', 'StokBarangController@detail')->name('detailstok')->middleware('auth'); //->name('home');
  Route::post('/stok/update/{id}', 'StokBarangController@update')->name('updatestok');
  Route::get('/stok/delete/{id}', 'StokBarangController@delete')->name('deletestok');
  Route::get('/stok/export', 'StokBarangController@export')->name('exportstok');
  Route::get('/stok/export/aset', 'StokBarangController@exportAset')->name('exportstokAset');
  Route::get('/stok/export/pinjaman', 'StokBarangController@exportPinjaman')->name('exportPinjaman');
  // Route::get('/stok/export/pinjaman', 'StokBarangController@exportPinjaman')->name('exportPinjaman');


  //BarangMasuk
  Route::get('/barangmasuk', 'BarangMasukController@main')->name('barangmasuk')->middleware('auth');
  Route::get('/barangmasuk/qrcode/{id}', 'BarangMasukController@qrcode')->name('qrcode'); //function () { return view('barangmasuk'); });
  Route::post('/barang/masuk', 'BarangMasukController@input')->name('inputbarangmasuk');
  Route::post('/barang/tambah', 'BarangMasukController@tambah')->name('tambahbarangmasuk');
  Route::get('/barang/masuk/delete/{id}', 'BarangMasukController@delete')->name('deletebarangmasuk');
  Route::get('/barangmasuk/fetch/{id}', 'BarangMasukController@fetchajax')->name('fetchajaxBM');
  Route::get('/barang/export', 'BarangMasukController@export')->name('exportbarangmasuk');
  Route::post('/import-barangmasuk', 'BarangMasukController@import')->name('barangmasuk.import');
  Route::get('/barang/export/template', 'BarangMasukController@downloadTemplate')->name('exportbarangmasuktemplate');

  //BarangKeluar
  Route::get('/barangkeluar', 'BarangKeluarController@index_server')->name('barangkeluar')->middleware('auth');
  Route::get('/barangkeluar/export', 'BarangKeluarController@export')->name('exportbarangkeluar');
  Route::get('/barangkeluar/edit/{id}', 'BarangKeluarController@edit')->name('editbarangkeluar');
  Route::post('/barang/kurang', 'BarangKeluarController@kurang')->name('kurangBK');
  Route::post('/barangkeluar/update/{id}', 'BarangKeluarController@update')->name('updatebarangkeluar');
  Route::get('/barangkeluar/delete/{id}', 'BarangKeluarController@delete')->name('deletebarangkeluar');
  Route::get('/barangkeluar/fetch/{id}', 'BarangKeluarController@fetchajax')->name('fetchajax');



  //keranjang barang keluar
  Route::get('/keranjangbarangkeluar', 'BarangKeluarController@index')->name('keranjangbarangkeluar');
  Route::get('/barangkeluar/edit/keranjang/{id}', 'BarangKeluarController@editk');
  Route::post('/barangkeluar/updatek/{id}', 'BarangKeluarController@updatek')->name('updatekeranjang');


  //peminjaman
  Route::get('/peminjaman', 'PeminjamanController@main')->name('peminjaman')->middleware('auth');
  Route::get('/peminjaman/edit/{id}', 'PeminjamanController@edit')->name('editpeminjaman');
  Route::post('/barang/pinjam', 'PeminjamanController@pinjam')->name('inputpinjam');
  Route::get('/peminjaman/fetch/{id}', 'PeminjamanController@fetchajax')->name('fetchajaxP');
  Route::get('/peminjaman/export', 'PeminjamanController@export')->name('exportpeminjaman');
  Route::get('/ttd/{id}', 'PeminjamanController@ttd')->name('inputtd');
  Route::post('/barang/kembali/{id}', 'PeminjamanController@kembali')->name('kembalibarang');
  Route::post('/peminjaman/update/{id}', 'PeminjamanController@update')->name('updatepeminjaman');
  Route::get('/ttd/fetchttd/{id}', 'PeminjamanController@fetchajaxttd')->name('fetchajaxT');


  //keranjang peminjaman
  Route::get('/keranjangpeminjaman', 'PeminjamanController@index')->name('keranjangpeminjaman')->middleware('auth');
  Route::get('/peminjaman/edit/keranjang/{id}', 'PeminjamanController@editkeranjang')->name('editkeranjangpeminjaman');
  Route::post('/peminjaman/updatek/{id}', 'PeminjamanController@updatekeranjang')->name('updatekeranjangp');
  Route::get('/peminjaman/delete/{id}', 'PeminjamanController@delete')->name('deletepeminjaman');


  // history barang 
  Route::get('/lap/barangkeluar', 'HistoryController@main')->name('history')->middleware('auth');
  Route::get('/lapbk/delete/{id}', 'HistoryController@delete')->name('deleteHBK');
  Route::get('/lap/export', 'HistoryController@export')->name('exportasetbarang');
  Route::get('/lap/bhs', 'HistoryController@bhs')->name('exportbaranghabis');
  Route::get('/lap/barangmasuk', 'HistoryController@hbarangmasuk')->name('historybm')->middleware('auth');
  Route::get('/exportmasuk', 'HistoryController@exportmasuk')->name('exportmasuk');
  Route::get('/lap/peminjaman', 'HistoryController@hpeminjaman')->name('historypm')->middleware('auth');
  Route::get('/exportpeminjamanH', 'HistoryController@exportpeminjaman')->name('exportpeminjamanH');
  Route::get('/exportlog', 'HistoryController@exportlog')->name('exportlog');
  Route::get('/lap/barangrusak', 'HistoryController@hrusak')->name('historyrsk')->middleware('auth');
  Route::get('/exportbarangrusak', 'HistoryController@exportbarangrusak')->name('exportbarangrusak');
  Route::get('/log', 'HistoryController@logaset')->name('logaset');



  //Barang Rusak
  Route::get('/barangrusak', 'BarangRusakController@index')->name('barangrusak')->middleware('auth');
  Route::post('/barangrusak/tambah', 'BarangRusakController@input')->name('tambahrusak');
  Route::get('/barangrusak/fetch/{id}', 'BarangRusakController@fetchajax')->name('fetchajaxBR');
  Route::post('/barangrusak/update/{id}', 'BarangRusakController@update')->name('updatebr');

  //BERITA ACARA SERAH TERIMA
  Route::get('/ba/serahterima/{id}', 'BeritaAcaraController@main')->name('bast')->middleware('auth');
  Route::post('/beritaacara/update/{id}', 'BeritaAcaraController@update')->name('updateba');
  Route::get('/beritaacara/output/{id}', 'OutputController@output')->name('outputba');
  Route::get('/beritaacara/download/{id}', 'OutputController@download')->name('downloadba');
  Route::get('/beritaacara/user/{id}', 'BeritaAcaraController@mainuser')->name('userba');
  Route::post('/beritaacara/update/user/{id}', 'BeritaAcaraController@updateuser')->name('userupdateba');

  //BERITA ACARA PINJAM-PAKAI
  Route::get('/ba/pinjampakai/{id}', 'BeritaAcaraController@pinjampakai')->name('bapp')->middleware('auth');

  //BERITA ACARA UPLOAD
  Route::post('/ba/upload', 'BeritaAcaraController@uploadBA')->name('uploadBA');

  //BERITA ACARA KEMBALI
  Route::get('/ba/kembali/{id}', 'BeritaAcaraController@kembali')->name('bak')->middleware('auth');
  Route::get('/ba/kembali/output/{id}', 'PeminjamanController@output')->name('outputbak');
  Route::get('/ba/kembali/download/{id}', 'PeminjamanController@download')->name('downloadbak');
  Route::get('/beritaacarakembali/user/{id}', 'PeminjamanController@mainuser');



  //REGIONS
  Route::get('regions', 'RegionsController@index')->name('Regions')->middleware('auth');
  Route::post('regions/add', 'RegionsController@post')->name('RegAdd')->middleware('auth');
  Route::get('/regions/delete/{id}', 'RegionsController@destroy')->name('deleteregions');
  Route::post('/regions/edit/{id}', 'RegionsController@update')->name('editregions')->middleware('auth');
  Route::get('regionsdata', 'RegionsController@getRegionsAjax')->name('RegionsAjax');

  //DOMAIN
  Route::get('domain', 'DomainController@index')->name('domain')->middleware('auth');
  Route::post('domain/store', 'DomainController@store')->name('storedomain')->middleware('auth');
  Route::post('domain/update/{id}', 'DomainController@update')->name('updatedomain')->middleware('auth');
  Route::get('domain/delete/{id}', 'DomainController@destroy')->name('deletedomain')->middleware('auth');
});