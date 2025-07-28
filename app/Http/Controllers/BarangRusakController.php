<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StokBarang;
use App\BarangRusak;
use App\BarangMasuk;
use DB;
use App\Regions;
use Illuminate\Support\Facades\Auth;

use RealRashid\SweetAlert\Facades\Alert;

class BarangRusakController extends Controller
{
    public function index(Request $request)
    {
        // if ((\Auth::user()->level == 'user')) {
        //     \Alert::warning('Anda dilarang masuk ke area ini');
        //     return redirect()->to(url('/home'));
        // }
        $query = DB::table('barangrusak')
            ->join('stokbarang', 'barangrusak.id_barang', '=', 'stokbarang.id')
            ->join('users', 'barangrusak.id_user', '=', 'users.id')
            // ->join('regions','barangrusak.wilayah','=','regions.id')
            ->select(
                'stokbarang.nama_barang',
                'stokbarang.jenisbarang',
                'stokbarang.tahun_anggaran',
                'barangrusak.*',
                //'regions.*',
                'users.username as NAMA'
            )
            ->where('stokbarang.jenisbarang', 1)
            ->orderBy('barangrusak.created_at', 'DESC')
            ->get();

        $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();
        // $select2['data'] = Regions::select('id','name')->get();
        $title = 'E-Aset | Barang Rusak';
        return view('barangrusak', compact('select', 'query', 'title'));
    }


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function regions($id)
    {

    }
    public function fetchajax($id)
    {
        $ajaxData = [];
        $stokBarang = StokBarang::select('id', 'satuan', 'jumlah_sekarang', 'tahun_anggaran')
            ->where('id', $id)
            ->where('aktif', 1)
            ->whereIn('jenisbarang', [1, 2, 3])
            ->first();
        if ($stokBarang) {
            $ajaxData['data'] = $stokBarang;
            $serialNumbers = DB::table('barangmasuk')
                ->select('id', 'id_qr', 'serial_number')
                // ->where('id', $id)
                ->where('id_barang', $stokBarang->id)
                ->whereIn('stat_barangmasuk', [1, 2])
                ->where('aktif', 1)
                ->get()
                // ->pluck('serial_number')
                ->toArray();

            $ajaxData['serial_numbers'] = $serialNumbers;
        }

        return response()->json($ajaxData);
    }

    // public function inputk(Request $req)
    // {
    //     $Barang = StokBarang::where('id', $req->id_barang)->first();
    //     $kurangbarang = $Barang->jumlah_sekarang - $req->jumlah_rusak;

    //     if ($req->jumlah_rusak > $Barang->jumlah_sekarang) {

    //         Alert::warning('Jumlah Barang  Keluar tidak sesuai dengan Stok Yang ada');
    //     } else {
    //         Alert::success('Data Berhasil Terinput');

    //         if ($Barang->jenisbarang == "1") {
    //             $BR = BarangRusak::create([
    //                 'id_barang' => $req->id_barang,
    //                 'jumlah_rusak' => $req->jumlah_rusak,
    //                 'satuan' => $req->satuan,
    //                 'sn' => $req->sn,
    //                 'wilayah' => $req->wilayah,
    //                 'id_user' => Auth::user()->id,
    //                 'status' => 'Rusak',
    //                 'aktif' => 1
    //             ]);
    //         }
    //         $BR->save();
    //         $Barang->jumlah_sekarang = $kurangbarang;
    //         $Barang->save();

    //     }


    //     $Redirect = route('barangrusak');
    //     return redirect($Redirect);
    // }

    public function input(Request $req)
    {
        $Barang = StokBarang::where('id', $req->id_barang)->first();
        if (!$Barang) {
            Alert::warning('Barang tidak ditemukan.');
            return redirect()->back();
        }
        $jumlah_pinjam = is_array($req->serial_number) ? count($req->serial_number) : 0;
        if ($jumlah_pinjam == 0) {
            Alert::warning('Pilih setidaknya satu nomor seri.');
        } elseif ($jumlah_pinjam > $Barang->jumlah_sekarang) {
            Alert::warning('Jumlah Barang Keluar tidak sesuai dengan Stok Yang ada');
        } else {

            if ($Barang->jenisbarang == "1") {

                if (is_array($req->serial_number)) {
                    $serialNumbers = $req->serial_number;
                    $idQrs = $req->id_qr;

                    foreach ($serialNumbers as $index => $serial_number) {

                        $id_qr = isset($idQrs[$index]) ? $idQrs[$index] : null;
                        $barangMasuk = BarangMasuk::where('serial_number', $serial_number)
                            ->where('id_qr', $id_qr)
                            ->where('id_barang', $req->id_barang)
                            ->where('aktif', 1)
                            ->first();

                        if ($barangMasuk) {
                            $BR = BarangRusak::create([
                                'id_barang' => $req->id_barang,
                                'id_barangmasuk' => $barangMasuk->id,
                                'jumlah_rusak' => 1,
                                'satuan' => $req->satuan,
                                'sn' => $serial_number,
                                'wilayah' => $req->wilayah,
                                'id_user' => Auth::user()->id,
                                'status' => 'Rusak',
                                'aktif' => 1
                            ]);
                            $barangMasuk->update([
                                'stat_barangmasuk' => 5,
                                'id_barangrusak' => $BR->id,
                            ]);
                        } else {
                            Alert::warning("BarangMasuk not found for serial number: {$serial_number}");
                        }
                    }
                }
            }


            $Barang->jumlah_sekarang -= $jumlah_pinjam;
            $Barang->save();

            Alert::success('Data Berhasil Terinput');
        }

        return redirect('/barangrusak');
    }



    public function update(Request $req, $id)
    {

        $BR = BarangRusak::find($id);
        if (!$BR) {
            Alert::warning('Data Barang Rusak tidak ditemukan.');
            return redirect()->back();
        }

        $Barang = StokBarang::where('id', $BR->id_barang)->first();
        if (!$Barang) {
            Alert::warning('Stok Barang tidak ditemukan.');
            return redirect()->back();
        }


        $tambahbarang = $Barang->jumlah_sekarang + $BR->jumlah_rusak;
        if ($req->jumlah_rusak > $Barang->jumlah_sekarang) {
            Alert::warning('Jumlah Barang rusak tidak sesuai dengan Stok Yang ada.');
        } else {
            Alert::success('Data Berhasil Terinput');


            $Barang->jumlah_sekarang = $tambahbarang;
            $Barang->save();


            $BR->status = 'sudah diperbaiki';
            $BR->aktif = 0;
            $BR->updated_at = now();
            $BR->save();


            $id_barangrusak = $BR->id;
            BarangMasuk::whereIn('serial_number', explode(',', $BR->sn))
                ->where('id_barangrusak', $id_barangrusak)
                ->where('id_barang', $BR->id_barang)
                ->update([
                    'stat_barangmasuk' => 2,
                    'id_barangrusak' => null,
                ]);
        }

        return redirect('/barangrusak');
    }

}