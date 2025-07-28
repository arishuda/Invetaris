<?php

namespace App\Http\Controllers;

use App\Exports\RiwayatBarangMasukExport;
use App\Exports\RiwayatKeluarAExport;
use App\Exports\RiwayatKeluarBExport;
use App\Exports\PeminjamanExport;
use App\Exports\BarangRusakExport;
use App\Exports\LogPinjamExport;
use App\HistoryBarangKeluar;
use App\Logging;
use App\StokBarang;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class HistoryController extends Controller
{
  //

  public function main(Request $request)
  {
    // if ((\Auth::user()->level == 'user')) {
    //   \Alert::warning('Anda dilarang masuk ke area ini');
    //   return redirect()->to(url('/home'));
    // }
    $Aquery = \DB::table('historybarangkeluar')
      ->join('stokbarang', 'historybarangkeluar.id_barang', '=', 'stokbarang.id')
      ->leftjoin('users', 'historybarangkeluar.id_user', '=', 'users.id')
      ->select('stokbarang.nama_barang', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'historybarangkeluar.*', 'users.username as NAMA')
      ->whereIn('historybarangkeluar.aktif', [0, 1])
      ->where('stokbarang.jenisbarang', 1)
      ->orderBy('historybarangkeluar.created_at', 'DESC');


    if ($request->filled('start_date')) {
      $Aquery->whereDate('historybarangkeluar.created_at', '>=', $request->input('start_date'));
    }

    if ($request->filled('end_date')) {
      $Aquery->whereDate('historybarangkeluar.created_at', '<=', $request->input('end_date'));
    }

    $AqueryResults = $Aquery->get();

    $Bquery = \DB::table('historybarangkeluar')
      ->join('stokbarang', 'historybarangkeluar.id_barang', '=', 'stokbarang.id')
      ->leftjoin('users', 'historybarangkeluar.id_user', '=', 'users.id')
      // ->leftjoin('log_barangkeluar', 'barangkeluar.id', '=', 'log_barangkeluar.id_barangkeluar')
      ->select('stokbarang.nama_barang', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'historybarangkeluar.*', 'users.username as NAMA')
      ->where(function ($query) {
        $query->where('stokbarang.jenisbarang', 2)
          ->orWhere('stokbarang.jenisbarang', 3);
      })
      ->orderBy('historybarangkeluar.created_at', 'DESC');


    if ($request->filled('start_date_1')) {
      $Bquery->whereDate('historybarangkeluar.created_at', '>=', $request->input('start_date_1'));
    }

    if ($request->filled('end_date_1')) {
      $Bquery->whereDate('historybarangkeluar.updated_at', '<=', $request->input('end_date_1'));
    }

    $BqueryResults = $Bquery->get();

    $title = 'E-Aset | Lap Barang Keluar';
    return view('report.HBarangkeluar', compact('AqueryResults', 'BqueryResults','title'));
  }

  public function export(Request $request)
  {
    $startDate = $request->input('start_date');
    $endDate = $request->input('end_date');

    return Excel::download(new RiwayatKeluarAExport($startDate, $endDate), 'RiwayatBarangAsetKeluar.xlsx');
  }

  public function bhs(Request $request)
  {
    $startDate1 = $request->input('start_date_1');
    $endDate1 = $request->input('end_date_1');

    return Excel::download(new RiwayatKeluarBExport($startDate1, $endDate1), 'RiwayatBarangHabisPakai.xlsx');
  }
  public function delete($id)
  {

    $HBarangKeluar = HistoryBarangKeluar::find($id);
    $BarangStok = StokBarang::where('id', $HBarangKeluar->id_barang)->first();
    // $BarangBA       = BeritaAcara::where('id_barangkeluar', $id)->first();

    $StokKembali = $HBarangKeluar->jumlah_keluar + $BarangStok->jumlah_sekarang;

    // Update Data Barang Stok
    $BarangStok->jumlah_sekarang = $StokKembali;
    $BarangStok->jumlah_awal = $StokKembali;
    $BarangStok->save();

    $HBarangKeluar->aktif = 0;
    $HBarangKeluar->save();


    Logging::create([
      'log' => "HBKELUAR",
      'id_barang' => $HBarangKeluar->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menghapus Data dari list History Barang Keluar. Nama Barang : " . $BarangStok->nama_barang
    ]);

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $HBarangKeluar->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menambah Stok Barang dari Data yang dihapus pada  History Barang Keluar. Nama Barang : " . $BarangStok->nama_barang . " Sebanyak " . $HBarangKeluar->jumlah_keluar . " " . $HBarangKeluar->satuan
    ]);

    return redirect('/history');
  }

  public function hbarangmasuk(Request $request)
  {
    // if ((\Auth::user()->level == 'user')) {
    //   \Alert::warning('Anda dilarang masuk ke area ini');
    //   return redirect()->to(url('/home'));
    // }
    $query = \DB::table('barangmasuk')
    ->join('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
    ->leftjoin('users', 'barangmasuk.id_user', '=', 'users.id')
    ->select(
        'stokbarang.nama_barang', 
        'stokbarang.id AS IDBM', 
        'barangmasuk.stat_barangmasuk AS STATUS', 
        'barangmasuk.tanggal_masuk', 
        'barangmasuk.last AS last_update', 
        'barangmasuk.aktif', 
        'users.name as NAMA', 
        'barangmasuk.serial_number',
        'barangmasuk.filename', 
        'stokbarang.kib',
        'barangmasuk.satuan as satuan',
        'barangmasuk.last as last',
        'barangmasuk.jumlah_masuk',
        'stokbarang.kib',
      
       // \DB::raw('count(barangmasuk.id) as jumlah_masuk'),
        // \DB::raw('MAX(barangmasuk.satuan) as satuan'),
        // \DB::raw('MAX(barangmasuk.last) as last') // Using MAX as an example
    )
    ->where('barangmasuk.aktif', 1)
    // ->groupBy(
    //     'stokbarang.nama_barang', 
    //     'stokbarang.id', 
    //     'stokbarang.kib',
    //     'barangmasuk.serial_number',
    //     'barangmasuk.stat_barangmasuk', 
    //     'barangmasuk.tanggal_masuk', 
    //     'barangmasuk.last', 
    //     'barangmasuk.satuan',
    //     'barangmasuk.aktif', 
    //     'users.name', 
    //     'barangmasuk.filename'
    // )
    ->orderBy('IDBM', 'DESC');

if ($request->filled('start_date')) {
    $query->whereDate('barangmasuk.created_at', '>=', $request->input('start_date'));
}

if ($request->filled('end_date')) {
    $query->whereDate('barangmasuk.created_at', '<=', $request->input('end_date'));
}

$results = $query->get();
$title = 'E-Aset | Lap BarangMasuk';
    return view('report.HBarangmasuk', compact('results','title'));
  }
  public function exportmasuk(Request $request)
  {
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');


    return Excel::download(new RiwayatBarangMasukExport($start_date, $end_date), 'RiwayatBarangMasuk.xlsx');

  }

  public function hpeminjaman(Request $request)
  {
    // if ((\Auth::user()->level == 'user')) {
    //   \Alert::warning('Anda dilarang masuk ke area ini');
    //   return redirect()->to(url('/home'));
    // }
    // $query = BarangMasuk::stok()->get();
    $query = \DB::table('peminjaman')
      ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
      ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id')
      ->select('stokbarang.nama_barang', 'peminjaman.jumlah_pinjam', 'peminjaman.tanggal_pinjam', 'peminjaman.id', 'peminjaman.id_barang', 'peminjaman.jumlah_kembali', 'peminjaman.tanggal_kembali', 'peminjaman.dipinjam', 'peminjaman.satuan', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'peminjaman.keperluan', 'users.username as NAMA')
      ->orWhere('peminjaman.aktif', 1)
      ->orderBy('peminjaman.created_at', 'DESC');

    if ($request->filled('start_date')) {
      $query->whereDate('peminjaman.created_at', '>=', $request->input('start_date'));
    }

    if ($request->filled('end_date')) {
      $query->whereDate('peminjaman.updated_at', '<=', $request->input('end_date'));
    }

    $queryresult = $query->get();

    $select = StokBarang::select('id', 'nama_barang')->get();

    $Bquery = \DB::table('logkembali')
      ->join('stokbarang', 'logkembali.id_barang', '=', 'stokbarang.id')
      ->select('stokbarang.nama_barang', 'logkembali.*')
      ->orderBy('logkembali.created_at', 'DESC');


    if ($request->filled('start_date_1')) {
      $Bquery->whereDate('logkembali.created_at', '>=', $request->input('start_date_1'));
    }

    if ($request->filled('end_date_1')) {
      $Bquery->whereDate('logkembali.created_at', '<=', $request->input('end_date_1'));
    }

    $Bquery = $Bquery->get();
    // dd($query);
    $title = 'E-Aset | Lap Peminjaman';
    return view('report.HPeminjaman', compact('queryresult', 'select', 'Bquery','title'));
  }

  public function exportpeminjaman(Request $request)
  {
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    return Excel::download(new PeminjamanExport($start_date, $end_date), 'RiwayatPeminjaman.xlsx');

  }

  public function exportlog(Request $request)
  {
    $start_date_1 = $request->input('start_date_1');
    $end_date_1 = $request->input('end_date_1');

    return Excel::download(new LogPinjamExport($start_date_1, $end_date_1), 'LogPinjam.xlsx');

  }

  public function hrusak(Request $request)
  {
    // if ((\Auth::user()->level == 'user')) {
    //   \Alert::warning('Anda dilarang masuk ke area ini');
    //   return redirect()->to(url('/home'));
    // }
    $query = \DB::table('barangrusak')
      ->join('stokbarang', 'barangrusak.id_barang', '=', 'stokbarang.id')
      ->join('users', 'barangrusak.id_user', '=', 'users.id')
      ->select(
        'stokbarang.nama_barang',
        'stokbarang.jenisbarang',
        'stokbarang.tahun_anggaran',
        'barangrusak.*',
        'users.username as NAMA'
      )
      ->where('stokbarang.jenisbarang', 1)
      ->orderBy('barangrusak.created_at', 'DESC');


    if ($request->filled('start_date')) {
      $query->whereDate('barangrusak.created_at', '>=', $request->input('start_date'));
    }

    if ($request->filled('end_date')) {
      $query->whereDate('barangrusak.created_at', '<=', $request->input('end_date'));
    }

    $query = $query->get();
    $title = 'E-Aset | Lap Barang Rusak';
    return view('report.HBarangrusak', compact('query','title'));
  }

  public function exportbarangrusak(Request $request)
  {
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');

    return Excel::download(new BarangRusakExport($start_date, $end_date), 'Barang_Rusak.xlsx');

  }

  public function logaset(Request $request)
  {
    if ((\Auth::user()->level == 'user' || \Auth::user()->level == 'admin' )) {
      \Alert::warning('Anda dilarang masuk ke area ini');
      return redirect()->to(url('/home'));
    }
    $query = \DB::table('log_easet')
      ->join('users', 'log_easet.id_user', '=', 'users.id')
      ->join('stokbarang', 'log_easet.id_barang', '=', 'stokbarang.id')
      ->select('log_easet.id', 'log_easet.log', 'log_easet.desc', 'log_easet.last', 'users.username as NAMA', 'stokbarang.nama_barang')
      ->orderBy('log_easet.created_at', 'DESC')
      ->get();
    $title = 'E-Aset | Log';
    return view('log.view', compact('query','title'));
  }
}