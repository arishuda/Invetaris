<?php

namespace App\Http\Controllers;
use App\StokBarang;


class ScanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * 
     * @return void
     */
    
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'E-Aset | Pusdatin';
        return view('scan.index',compact('title'));
    }
    public function qrcode($id)
    {
    $qrcode = StokBarang::findOrFail($id)
        ->leftJoin('BarangMasuk', 'stokbarang.id', '=',  'barangmasuk.id_barang')
        ->select('stokbarang.nama_barang', 'barangmasuk.id','barangmasuk.aktif','barangmasuk.serial_number','barangmasuk.id_qr','stokbarang.id_qrs')
        ->findOrFail($id);
    
    return response()->json($qrcode); // Return the data as JSON
    }

}
