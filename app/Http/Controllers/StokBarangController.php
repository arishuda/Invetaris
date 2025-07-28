<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StokBarang;
use App\BarangMasuk;
use App\BarangKeluar;
use App\Peminjaman;
use Vinkla\Hashids\Facades\Hashids;
use App\HistoryBarangKeluar;


use App\Logging;

use App\Exports\StokBarangExport;
use App\Exports\StokBarangAsetExport;
use App\Exports\StokBarangPinjamanExport;
use Maatwebsite\Excel\Facades\Excel;

class StokBarangController extends Controller
{
  public function export()
  {
    return Excel::download(new StokBarangExport, 'StokBarang.xlsx');
  }

  public function exportAset()
  {
    return Excel::download(new StokBarangAsetExport, 'StokBarangAset.xlsx');
  }

  public function exportPinjaman(){
    return Excel::download(new StokBarangPinjamanExport,'StokBarangPinjaman.xlsx');
  }


  public function main()
  {
    $Aquery = StokBarang::where('aktif', 1)->where('jenisbarang', 1)->orderBy('created_at', 'DESC')->get();
    $Bquery = StokBarang::where('aktif', 1)->where('jenisbarang', 2)->orWhere('jenisbarang', 3)->orderBy('created_at', 'DESC')->get();
    $Cquery = StokBarang::where('aktif', 1)->where('jenisbarang', 11)->orderBy('created_at', 'DESC')->get();
    $title = 'E-Aset | Stok Barang';

    return view('stokbarang', compact('Aquery', 'Bquery','Cquery','title')); //, 'masuk', 'keluar', 'pinjam'));
  }

  public function qrcode($id)
  {
    $decodeqr = Hashids::decode($id);
      if (empty($decodeqr)) {
          abort(404, 'Invalid ID'); 
      }
      $ids = $decodeqr[0];
    $qrcode = StokBarang::findOrFail($ids)
    ->leftjoin('barangmasuk', 'stokbarang.id', '=',  'barangmasuk.id_barang')
    ->select('stokbarang.nama_barang', 'barangmasuk.id','barangmasuk.aktif','barangmasuk.serial_number','stokbarang.id_qrs')
    ->findOrFail($ids);
    $title = 'E-Aset | Detail QR';
    return view('stokbarang.qrcode', compact('qrcode','title'));
  }

  // public function qrcodeApi()
  // {
  //     try {
  //         $qrcodes = StokBarang::leftJoin('BarangMasuk', 'stokbarang.id', '=', 'barangmasuk.id_barang')
  //             ->select('stokbarang.*', 'barangmasuk.id', 'barangmasuk.aktif', 'barangmasuk.serial_number', 'stokbarang.id_qrs')
  //             ->get();
  
  //         if ($qrcodes->isEmpty()) {
  //             return response()->json([
  //                 'message' => 'QR codes not found',
  //                 'status' => 'error',
  //             ], 404);
  //         }
  
          
  //         $data = [
  //             'qrcodes' => $qrcodes,
  //         ];
  
  //         return response()->json($data, 200);
  //     } catch (\Exception $e) {
  //         return response()->json([
  //             'message' => $e->getMessage(),
  //             'status' => 'error',
  //         ], 500);
  //     }
  // }
  
  public function detail($id)
  {
    $decodedIdArray = Hashids::decode($id);
    if (empty($decodedIdArray)) {
        abort(404, 'Invalid ID'); 
    }
    $ids = $decodedIdArray[0];
    $data = StokBarang::find($ids);
    $masuk = BarangMasuk::where('id_barang', $ids)->get();
    $keluar = BarangKeluar::where('id_barang', $ids)->get();
    $pinjam = Peminjaman::where('id_barang', $ids)->get();
    $title = 'E-Aset | Detail Stok Barang';
    return view('stokbarang.detail', compact('data', 'masuk', 'keluar', 'pinjam','title'));
  }

  public function UploadFile($data, $path, $fileRemove = null, $id = null, $typePath = null)
  {
    if ($fileRemove != null) {
      if ($typePath == 'storage') {
        if (file_exists(storage_path('app/public/' . $path . '/' . $fileRemove))) {
          unlink(storage_path('app/public/' . $path . '/' . $fileRemove));
        }
      } else {
        if (file_exists($path . '/' . $fileRemove)) {
          unlink($path . '/' . $fileRemove);
        }
      }
    }

    if ($id != null) {
      $filename = $id . '_' . date('Y-m-d_H-i-s') . '.' . $data->extension();
    } else {
      $filename = date('Y-m-d_H-i-s') . '.' . $data->extension();
    }

    if ($typePath == 'storage') {
      $pathFolder = storage_path('app/public/' . $path);
    } else {
      $pathFolder = public_path($path);
    }

    $data->move($pathFolder, $filename);
    $maxFileSize = 2 * 1024 * 1024;

    $imagePath = $pathFolder . '/' . $filename;
    $initialFileSize = filesize($imagePath);
    if ($initialFileSize > $maxFileSize) {
      $image = \Image::make($imagePath);
      $quality = 90; // Start with 90% quality
      $step = 5; // Compression step size
      while ($initialFileSize > $maxFileSize && $quality > 0) {
        $image->save($imagePath, $quality); // Save with current quality
        $quality -= $step;
        $initialFileSize = filesize($imagePath); // Check the new file size
      }
    }
    
    return $filename;
  }

  public function update(Request $req, $id)
  {
    $data = StokBarang::find($id);

    //Update
    if (!empty($req->image)) {
      $fileName = $this->UploadFile($req->image, 'stokbarang', $data->image);
    } else {
      $fileName = $data->image;
    }

    $data->jenisbarang = $req->jenisbarang;
    $data->nama_barang = $req->nama_barang;
    $data->lokasi = $req->lokasi;
    $data->tahun_anggaran = $req->tahun;
    $data->image = $fileName;
    $data->kib = $req->kib;
    $data->save();

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $id,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Memperbarui Data Stok Barang. Nama Barang : " . $req->nama_barang . " ( Jenis Barang : " . $req->jenisbarang . ", Lokasi : " . $req->lokasi . ", Tahun : " . $req->tahun . ", KIB : " . $req->kib . ")"
    ]);

    return redirect('/stok');
  }

  public function delete($id)
  {
    $barangmasuk = BarangMasuk::where('id_barang', $id)->get();
    if (empty($barangmasuk)) {
      "";
    } else {
      foreach ($barangmasuk as $DBM) {
        $DBM->aktif = 0;
        $DBM->save();
      }
    }

    $barangkeluar = BarangKeluar::where('id_barang', $id)->get();
    if (empty($barangkeluar)) {
      "";
    } else {
      foreach ($barangkeluar as $DBK) {
        $DBK->aktif = 0;
        $DBK->save();
      }
    }

    $HBarangkeluar = HistoryBarangKeluar::where('id_barang', $id)->get();
    if (empty($HBarangkeluar)) {
      "";
    } else {
      foreach ($HBarangkeluar as $DBHK) {
        $DBHK->aktif = 0;
        $DBHK->save();
      }
    }

    $data = StokBarang::find($id);
    $data->aktif = 0;
    $data->save();

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $id,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menghapus Data Stok Barang. Nama Barang : " . $data->nama_barang
    ]);

    // dd($barangkeluar);

    //////////////////////////////////////////////////

    // $peminjaman = Peminjaman::where('id_barang', $id)->first();
    // if (empty($peminjaman)){
    //   "";
    // } else {
    //   $peminjaman->delete();
    // }

    // $beritaAcara = BeritaAcara::where('id_barang', $id)->first();
    // if (empty($beritaAcara)){
    //   "";
    // } else {
    //   if(empty($beritaAcara->foto)){
    //   } else{
    //     \File::delete(public_path("fotobarang/".$beritaAcara->foto));
    //   }
    //   $beritaAcara->delete();
    // }



    return redirect('/stok');
  }

}