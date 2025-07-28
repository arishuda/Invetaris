<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $BP = DB::table('stokbarang')
        ->where('jenisbarang', 11)
        ->where('aktif', 1)
        ->sum('jumlah_sekarang');
        $barangKeluar = DB::table('barangkeluar')->sum('jumlah_keluar');
        $stokbarang = DB::table('stokbarang')
        ->where('jenisbarang', 1)
        ->where('aktif', 1)
        ->sum('jumlah_sekarang');
        $BHS = DB::table('stokbarang')
        ->where('jenisbarang', 2)
        ->where('aktif', 1)
        ->sum('jumlah_sekarang');
        $STB = DB::table('barangmasuk')
        ->where('aktif', 1)
        ->whereIn('stat_barangmasuk', [1,2])
        ->sum('jumlah_masuk');
        $BRS = DB::table('barangrusak')
        ->where('aktif', 1)
        ->sum('jumlah_rusak');
        $title = 'E-Aset | Pusdatin';
        return view('home',compact('title','BP','barangKeluar','stokbarang','BHS','STB','BRS'));
    }

}
