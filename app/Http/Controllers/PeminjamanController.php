<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StokBarang;
use App\Peminjaman;
use App\LogKembali;
use App\BeritaAcara;
use App\BarangMasuk;
use App\Logging;
use PDF;
use TCPDF;


use RealRashid\SweetAlert\Facades\Alert;
use App\Exports\PeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;
use Vinkla\Hashids\Facades\Hashids;

class PeminjamanController extends Controller
{
  //
  public function main()
  {
    // $query = BarangMasuk::stok()->get();
    $query = \DB::table('peminjaman')
      ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
      ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id')
      ->select('stokbarang.nama_barang', 'peminjaman.jumlah_pinjam', 'peminjaman.tanggal_pinjam', 'peminjaman.id', 'peminjaman.id_barang', 'peminjaman.jumlah_kembali', 'peminjaman.tanggal_kembali', 'peminjaman.dipinjam', 'peminjaman.satuan', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'peminjaman.keperluan')
      ->orWhere('peminjaman.aktif', 1)
      ->orderBy('peminjaman.created_at', 'DESC')
      ->get();


    $select = StokBarang::select('id', 'nama_barang')->get();




    $title = 'E-Aset | Peminjaman';
    // dd($query);
    return view('peminjaman.peminjaman', compact('query', 'select', 'title'));
  }


  public function export()
  {
    return Excel::download(new PeminjamanExport, 'Peminjaman.xlsx');
  }


  public function fetchajax($id)
  {
    $ajaxData['data'] = StokBarang::select('stokbarang.id', 'stokbarang.satuan', 'stokbarang.jumlah_sekarang', 'stokbarang.tahun_anggaran')
      ->where('stokbarang.id', $id)
      ->where('stokbarang.aktif', 1)
      ->whereIn('jenisbarang', [1, 2, 3])
      ->first();

    if ($ajaxData['data']) {
      $serialNumbers = \DB::table('barangmasuk')
            ->select('id_qr', 'serial_number')
            ->where('id_barang', $ajaxData['data']->id)
            ->whereIn('stat_barangmasuk', [1, 2])
            ->where('aktif', 1)
            //->pluck('serial_number')
            ->get()
            ->toArray();

    
      $ajaxData['serial_numbers'] = $serialNumbers;
    }

    // dd($ajaxData);

    return response()->json($ajaxData);
  }


  public function pinjam(Request $req)
  {
    //dd($req->all());
    $Barang = StokBarang::where('id', $req->id_barang)->first();
  
    $jumlah_pinjam = is_array($req->serial_number) ? count($req->serial_number) : 0;
    
    // dd($req->all());
    if ($jumlah_pinjam == 0) {
      Alert::warning('Pilih setidaknya satu nomor seri !');
    } elseif ($jumlah_pinjam > $Barang->jumlah_sekarang) {
      Alert::warning('Jumlah Barang Keluar tidak sesuai dengan Stok Yang ada');
    } else {
      if ($Barang->jenisbarang == "1") {
        if (is_array($req->serial_number)) {
          $total_quantity = count($req->serial_number);
          $serial_numbers = implode(',', $req->serial_number);

          $BP = Peminjaman::create([
            'id_logkembali' => $req->id_logkembali,
            'id_barang' => $req->id_barang,
            'jumlah_pinjam' => $total_quantity,
            'satuan' => $req->satuan,
            'dipinjam' => $req->dipinjam,
            'aktif' => 1,
            'id_user' => \Auth::user()->id,
            'buatba' => 0,
            'tanggal_pinjam' => date("Y-m-d"),
            'serialnumber' => $serial_numbers,
            'keperluan' => $req->keperluan
          ]);

          $serialNumbers = $req->serial_number;
            $idQrs = $req->id_qr;

            foreach ($serialNumbers as $index => $serial_number) {
            
                $id_qr = isset($idQrs[$index]) ? $idQrs[$index] : null;
                BarangMasuk::where('serial_number', $serial_number)
                    ->where('id_qr', $id_qr)
                    ->where('id_barang', $req->id_barang)
                    ->where('aktif', 1)
                    ->update([
                        'stat_barangmasuk' => 4,
                        'id_peminjaman' => $BP->id,
                        // 'aktif' => 0, // Uncomment if needed
                    ]);
            }
          // dd($NM);

         $uy = BeritaAcara::create([
            'id_peminjaman' => $BP->id,
            'id_barang' => $req->id_barang,
            'nama_p2' => $req->nama_p2,
            'statuskerja_p2' => $req->statuskerja_p2,
            'nip_p2' => $req->nip_p2,
            'nrk_p2' => $req->nrk_p2,
            'nik_p2' => $req->nik_p2,
            'lokasikerja' => $req->lokasikerja,
            'serialnumber' => implode(', ', $req->serial_number),
            'jabatan_p2' => $req->jabatan_p2
          ]);    

        }
         //dd($uy);
      } 

      $Barang->jumlah_sekarang -= $jumlah_pinjam;
      $Barang->save();

      Logging::create([
        'log' => "BPINJAM",
        'id_barang' => $req->id_barang,
        'id_user' => \Auth::user()->id,
        'last' => now(),
        'desc' => "Meminjam Barang. Nama Barang : " . $Barang->nama_barang . " Sebanyak " . $jumlah_pinjam . " " . $req->satuan . " (Keperluan :" . $req->keperluan . " diambil :" . $req->dipinjam . ")"
      ]);
  
    }

    return redirect('/peminjaman');
  }


//FORM KEMBALI BARANG//
  public function ttd($id)
  {
    $decodedIdArray = Hashids::decode($id);
    if (empty($decodedIdArray)) {
        abort(404, 'Invalid ID'); 
    }
    $ids = $decodedIdArray[0];
      $data = \DB::table('peminjaman')
          ->where('peminjaman.id', $ids)
          //  ->join('peminjaman', 'logkembali.id_peminjaman', '=','peminjaman.id')
          ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
          ->select( 'stokbarang.nama_barang','peminjaman.*')
          ->first();
  
  
      $log = LogKembali::where('id_peminjaman', $data->id)->sum('jumlah_kembali');
      $serialNumbers = \DB::table('barangmasuk')
      ->where('id_peminjaman', $ids)
      ->pluck('serial_number')
      ->toArray();
    
      return view('peminjaman.ttd', compact('data','log','serialNumbers'));
  }

  public function mainuser($id)
    {
        $ids = \Crypt::decryptString($id);

        function Tblg($number)
        {
            $number = str_replace('.', '', $number);
            if (!is_numeric($number))
                throw new \Exception("Please input number.");
            $base = array('Nol', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan');
            $numeric = array('1000000000000000', '1000000000000', '1000000000000', 1000000000, 1000000, 1000, 100, 10, 1);
            $unit = array('Kuadriliun', 'Triliun', 'Biliun', 'Milyar', 'Juta', 'Ribu', 'Ratus', 'Puluh', '');
            $str = null;
            $i = 0;
            if ($number == 0) {
                $str = 'Nol';
            } else {
                while ($number != 0) {
                    $count = (int) ($number / $numeric[$i]);
                    if ($count >= 10) {
                        $str .= $count . ' ' . $unit[$i] . ' ';
                    } elseif ($count > 0 && $count < 10) {
                        $str .= $base[$count] . ' ' . $unit[$i] . ' ';
                    }
                    $number -= $numeric[$i] * $count;
                    $i++;
                }
                $str = preg_replace('/Satu Puluh (\w+)/i', '\1 Belas', $str);
                $str = preg_replace('/Satu (Ribu|Ratus|Puluh|Belas)/', 'se\1', $str);
                $str = preg_replace('/\s{2,}/', ' ', trim($str));
            }
            return $str;
        }

        $query = \DB::table('peminjaman')->where('peminjaman.id', $ids)
            ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
            ->join('beritaacara', 'beritaacara.id_peminjaman','=' ,'peminjaman.id')
            ->select('stokbarang.nama_barang', 'peminjaman.*','beritaacara.wilayah')
            ->first();

        $data = $query;

        $TBA = \TANGGAL::TGL($data->tanggal_ba);
        $TanggalBA = Tblg($TBA);
        $YBA = \TANGGAL::Tahun($data->tanggal_ba);
        $TahunBA = Tblg($YBA);

        return view('beritaacara.bauserk', compact('data', 'TanggalBA', 'TahunBA'));
    }

  
  
  public function fetchajaxttd($id)
{
  // $decodedIdArray = Hashids::decode($id);
  // if (empty($decodedIdArray)) {
  //     return response()->json(['error' => 'Invalid ID'], 404);
  // }
  
  // $ids = $decodedIdArray[0];
  $serialNumbers = \DB::table('barangmasuk')
      ->where('id_peminjaman', $id)
      ->pluck('serial_number')
      ->toArray();

  return response()->json(['serial_numbers' => $serialNumbers]);

}
  public function delete(Request $req, $id)
  {
   
    $Pinjam = Peminjaman::find($id);
    $Barang = StokBarang::where('id', $Pinjam->id_barang)->first();
    $Beritaacara = BeritaAcara::where('id_peminjaman', $Pinjam->id)->first();
    $BarangMasuk =  BarangMasuk::where('id_peminjaman',$Pinjam->id)->get();

    foreach ($BarangMasuk as $item) {
      $item->where(['id',$Pinjam->id_barang]);
      $item->update([
          'stat_barangmasuk' => 2,
          'id_peminjaman' => null,
      ]);
      //dd($item);
  }


    $pinjambarang = $Pinjam->jumlah_pinjam + $Barang->jumlah_sekarang;
    $Barang->jumlah_sekarang = $pinjambarang;
    $Barang->save();
    $Beritaacara->delete();
    $Pinjam->delete();
    
    Alert::success('Data Berhasil Dihapus');
    
    Logging::create([
      'log' => "BPINJAM",
      'id_barang' => $Pinjam->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menghapus Data dari list Keluar Barang. Nama Barang : " . $Barang->nama_barang
    ]);

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $Pinjam->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menambah Stok Barang dari Data yang dihapus pada Keluar Barang. Nama Barang : " . $Barang->nama_barang . " Sebanyak " . $pinjambarang . " " . $Pinjam->satuan
    ]);

    return redirect('/peminjaman');
  }
  public function kembali(Request $req, $id)
{

  //dd($req->all());
    $Pinjam = Peminjaman::find($id);
    $Barang = StokBarang::where('id', $Pinjam->id_barang)->first();
    $Log = LogKembali::where('id_peminjaman', $Pinjam->id)->sum('jumlah_kembali');

    $totalKembali = $Log + count($req->serial_number);
    $jumlah_pinjam = is_array($req->serial_number) ? count($req->serial_number) : 0;
    $pinjambarang = $Barang->jumlah_sekarang + $jumlah_pinjam;

    if ($jumlah_pinjam > $Pinjam->jumlah_pinjam) {
        Alert::warning('Jumlah Barang Yang dikembalikan tidak sesuai dengan Barang yang dipinjam');
    } else {
        $serialNumbers = $req->serial_number;
        $idQrs = $req->id_qr;

        foreach ($serialNumbers as $index => $serial_number) {
            $id_qr = isset($idQrs[$index]) ? $idQrs[$index] : null;
            $update = BarangMasuk::where('serial_number', $serial_number)
                      ->where('id_barang', $Pinjam->id_barang)
                      ->where('aktif', 1)
                      ->update([
                          'stat_barangmasuk' => 2,
                          'id_peminjaman' => null,
                      ]);

//dd($update);
        }

        $Barang->jumlah_sekarang = $pinjambarang;
        $Barang->save();

        $Pinjam->jumlah_kembali = $jumlah_pinjam;
        $Pinjam->tanggal_kembali = $req->tanggal_kembali;
        $Pinjam->nomor = $req->nomor;
        $Pinjam->tanggal_ba = $req->tanggal_ba;

        $Pinjam->nama_p1 = $req->nama_p1;
        $Pinjam->ket1 = $req->ket1;
        $Pinjam->ket2 = $req->ket2;
        $Pinjam->ket3 = $req->ket3;
        $Pinjam->nip_p1 = $req->nip_p1;
        $Pinjam->nrk_p1 = $req->nrk_p1;
        $Pinjam->jabatan_p1 = $req->jabatan_p1;

        $Pinjam->nama_p2 = $req->nama_p2;
        $Pinjam->statuskerja_p2 = $req->statuskerja_p2;
        $Pinjam->nip_p2 = $req->nip_p2;
        $Pinjam->nrk_p2 = $req->nrk_p2;
        $Pinjam->nik_p2 = $req->nik_p2;
        $Pinjam->lokasikerja = $req->lokasikerja;
        $Pinjam->jabatan_p2 = $req->jabatan_p2;

        // $Pinjam->serialnumber = $req->serial_number;
        $Pinjam->jumlah = $req->jumlah;

        if (empty($Pinjam->ttd_p1)) {
            $Pinjam->ttd_p1 = $req->ttd_p1;
        }

        if (empty($Pinjam->ttd_p2)) {
            $Pinjam->ttd_p2 = $req->ttd_p2;
        }

        if (empty($Pinjam->ttd_kasatpel)) {
            $Pinjam->ttd_kasatpel = $req->ttd_kasatpel;
        }

        Alert::success('Data Berhasil terinput');
        $Pinjam->save();

        if (!empty($totalKembali)) {
            LogKembali::create([
                'id_peminjaman' => $Pinjam->id,
                'id_barang' => $Pinjam->id_barang,
                'jumlah_kembali' => $jumlah_pinjam,
                'nama_pengembali' => $Pinjam->nama_p2,
                'tanggal_kembali' => $req->tanggal_kembali,
                'serialnumber' => implode(', ', $req->serial_number),
                'id_user' => \Auth::user()->id,
                'ttd' => $req->ttd
            ]);
        }
    }

    return redirect('/peminjaman');
}


  public function edit($id)
  {
    $decodeedit = Hashids::decode($id);
    if (empty($decodeedit)) {
        abort(404, 'Invalid ID');
    }
    $ids = $decodeedit[0];

    $data = \DB::table('peminjaman')
      ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id') //sudah
      ->join('beritaacara', 'peminjaman.id', '=', 'beritaacara.id_peminjaman') //sudah fix
      ->leftjoin('beritaacara_upload', 'peminjaman.id', '=', 'beritaacara_upload.id_peminjaman')
      ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id') //sudah fix
      ->select(
        'stokbarang.nama_barang',
        'stokbarang.jenisbarang',
        'stokbarang.tahun_anggaran',
        'peminjaman.*',
        'beritaacara.id AS BA_ID',
        'users.username as NAMA',
        'beritaacara_upload.filename AS NAMAFILE'
      ) //sudah fix
      ->orWhere('peminjaman.aktif', 1) //sudah fix
      ->where('stokbarang.jenisbarang', 1) //sudah fix
      ->where('peminjaman.id', $ids) //sudah fix
      ->first();

      $serialNumbers = \DB::table('barangmasuk')
      ->where('id_peminjaman', $ids)
      ->pluck('serial_number')
      ->toArray();
      
    $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();
    // DD($data);
    $title = 'E-Aset | Edit Peminjaman';
    return view('peminjaman.editpeminjaman', compact('data', 'select','title','serialNumbers'));
  }

  public function update(Request $req, $id)
  {

    $data = Peminjaman::find($id);
    // $data->diambil = $req->diambil;

    $data->keperluan = $req->keperluan;
    $data->save();

    return redirect('/peminjaman');
  }



  // BERITA ACARA PENGEMBALIAN BARANG
  public function output($id)
  {
      $decodeedit = Hashids::decode($id);
    if (empty($decodeedit)) {
        abort(404, 'Invalid ID');
    }
    $ids = $decodeedit[0];
      $querys = \DB::table('logkembali')
          ->where('logkembali.id_peminjaman', $ids)
          ->join('stokbarang', 'logkembali.id_barang', '=', 'stokbarang.id')
          ->leftJoin('peminjaman', 'logkembali.id_peminjaman', '=', 'peminjaman.id')
          ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'peminjaman.*','logkembali.jumlah_kembali','logkembali.tanggal_kembali','logkembali.serialnumber')
          ->get();

      $pdf = new TCPDF();
      $pdf->SetAuthor('BA - DATAKOM SARPRAS TI');
      $pdf->SetTitle('BERITA ACARA SERAH TERIMA');
      $pdf->SetSubject('BERITA ACARA - SARPRAS TI');
      $pdf->SetMargins(15, 56, 9, 9);
      $pdf->SetFontSubsetting(false);
      $pdf->SetFontSize('9px');
      $pdf->SetAutoPageBreak(TRUE, 0);
      // $pdf->importPage(1);
  
      foreach ($querys as $query) {
          $dataBA = $query;
          $TBA = \TANGGAL::TGL($dataBA->tanggal_kembali);
          $TanggalBA = \TERBILANG::TBLG($TBA);
          $YBA = \TANGGAL::Tahun($dataBA->tanggal_kembali);
          $TahunBA = \TERBILANG::TBLG($YBA);
  
          $JumlahBA = \TERBILANG::TBLG($dataBA->jumlah_kembali);
          $Ket1 = ($dataBA->ket1);
          $ket2 = ($dataBA->ket2);
          $ket3 = ($dataBA->ket3);
  
          $out_1 = view('beritaacara.outEmpat', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA', 'query'));
          $out_2 = view('beritaacara.outDua', compact('dataBA'));
          $out_3 = view('beritaacara.outTiga', compact('dataBA'));
  
          $pdf->AddPage('P', 'F4');
          $pdf->Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
          $pdf->writeHTML($out_1, true, false, true, false, '');
  
          if (!empty($dataBA->ttd_p1)) {
              $TTD_1 = base64_decode($dataBA->ttd_p1);
              $pdf->Image('@' . $TTD_1, 140, '', 30);
          }
  
          if (!empty($dataBA->ttd_p2)) {
              $TTD_2 = base64_decode($dataBA->ttd_p2);
              $pdf->Image('@' . $TTD_2, 42, '', 30);
          }
  
          $pdf->Ln(30);
          $pdf->writeHTML($out_2, true, false, true, false, '');
  
          if (!empty($dataBA->ttd_kasatpel)) {
              $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
              $pdf->Image('@' . $TTD_3, 90, '', 30);
          }
  
          $pdf->Ln(25);
          $pdf->writeHTML($out_3, true, false, true, false, '');
      }
    
        // dd($querys);
      $filename = 'BA-' . date('YmdHis') . '.pdf';
      $pdf->Output($filename, 'I');
      exit;
  }

  public function download($id)
  {
      $decodeedit = Hashids::decode($id);
    if (empty($decodeedit)) {
        abort(404, 'Invalid ID');
    }
    $ids = $decodeedit[0];
      $querys = \DB::table('logkembali')
          ->where('logkembali.id_peminjaman', $ids)
          ->join('stokbarang', 'logkembali.id_barang', '=', 'stokbarang.id')
          ->leftJoin('peminjaman', 'logkembali.id_peminjaman', '=', 'peminjaman.id')
          ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'peminjaman.*','logkembali.jumlah_kembali','logkembali.tanggal_kembali')
          ->get();

      $pdf = new TCPDF();
      $pdf->SetAuthor('BA - DATAKOM SARPRAS TI');
      $pdf->SetTitle('BERITA ACARA SERAH TERIMA');
      $pdf->SetSubject('BERITA ACARA - SARPRAS TI');
      $pdf->SetMargins(15, 56, 9, 9);
      $pdf->SetFontSubsetting(false);
      $pdf->SetFontSize('9px');
      $pdf->SetAutoPageBreak(TRUE, 0);
      // $pdf->importPage(1);
  
      foreach ($querys as $query) {
          $dataBA = $query;
          $TBA = \TANGGAL::TGL($dataBA->tanggal_kembali);
          $TanggalBA = \TERBILANG::TBLG($TBA);
          $YBA = \TANGGAL::Tahun($dataBA->tanggal_kembali);
          $TahunBA = \TERBILANG::TBLG($YBA);
  
          $JumlahBA = \TERBILANG::TBLG($dataBA->jumlah_kembali);
          $Ket1 = ($dataBA->ket1);
          $ket2 = ($dataBA->ket2);
          $ket3 = ($dataBA->ket3);
  
          $out_1 = view('beritaacara.outEmpat', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA', 'query'));
          $out_2 = view('beritaacara.outDua', compact('dataBA'));
          $out_3 = view('beritaacara.outTiga', compact('dataBA'));
  
          $pdf->AddPage('P', 'F4');
          $pdf->Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
          $pdf->writeHTML($out_1, true, false, true, false, '');
  
          if (!empty($dataBA->ttd_p1)) {
              $TTD_1 = base64_decode($dataBA->ttd_p1);
              $pdf->Image('@' . $TTD_1, 140, '', 30);
          }
  
          if (!empty($dataBA->ttd_p2)) {
              $TTD_2 = base64_decode($dataBA->ttd_p2);
              $pdf->Image('@' . $TTD_2, 42, '', 30);
          }
  
          $pdf->Ln(30);
          $pdf->writeHTML($out_2, true, false, true, false, '');
  
          if (!empty($dataBA->ttd_kasatpel)) {
              $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
              $pdf->Image('@' . $TTD_3, 90, '', 30);
          }
  
          $pdf->Ln(25);
          $pdf->writeHTML($out_3, true, false, true, false, '');
      }
    
        // dd($querys);
      $filename = 'BA-' . date('YmdHis') . '.pdf';
      $pdf->Output($filename, 'D');
      exit;
  }

  // public function download($id)
  // {
  //   // $decodedIdArray = Hashids::decode($id);
  //   // if (empty($decodedIdArray)) {
  //   //     abort(404, 'Invalid ID'); 
  //   // }
  //   // $ids = $decodedIdArray[0];

  //   $query = \DB::table('peminjaman')->where('peminjaman.id', $id)
  //     ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
  //     // ->join('barangkeluar', 'beritaacara.id_barangkeluar', '=', 'barangkeluar.id')
  //     ->leftjoin('logkembali', 'logkembali.id_peminjaman', '=', 'peminjaman.id')
  //     ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'peminjaman.*', 'peminjaman.id', 'peminjaman.keperluan')
  //     ->first();
  //     // $serialNumbers = \DB::table('barangmasuk')
  //     // ->where('id_peminjaman', $query->id_peminjaman)
  //     // ->pluck('serial_number')
  //     // ->toArray();
  //   $log = LogKembali::where('id_barang', $query->id_barang)->where('tanggal_kembali', $query->tanggal_kembali)->sum('jumlah_kembali');


  //   $dataBA = $query;
  //   $TBA = \TANGGAL::TGL($dataBA->tanggal_ba);
  //   $TanggalBA = \TERBILANG::TBLG($TBA);
  //   $YBA = \TANGGAL::Tahun($dataBA->tanggal_ba);
  //   $TahunBA = \TERBILANG::TBLG($YBA);


  //   $data = $log;
  //   $JumlahBA = \TERBILANG::TBLG($data);

  //   $out_1 = view('beritaacara.outSatu', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA', 'log', 'data'));
  //   $out_2 = view('beritaacara.outDua', compact('dataBA'));
  //   $out_3 = view('beritaacara.outTiga', compact('dataBA'));


  //   // TTPDF::setJPEGQuality(75);
  //   PDF::SetAuthor('BA - DATAKOM SARPRAS TI');
  //   PDF::SetTitle('BERITA ACARA SERAH TERIMA - ' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2);
  //   PDF::SetSubject('BERITA ACARA - SARPRAS TI');
  //   PDF::SetMargins(17, 56, 9, 9);
  //   PDF::SetFontSubsetting(false);
  //   PDF::SetFontSize('9px');
  //   PDF::SetAutoPageBreak(TRUE, 0);

  //   // ------------------------------------------------------
  //   PDF::AddPage('P', 'F4');
  //   PDF::Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
  //   //------------------------------------------------------- BUAT TULISAN BASTB
  //   PDF::writeHTML($out_1, true, false, true, false, '');

  //   //-------------------------------------------------------
  //   // PDF::Ln(10);
  //   // PDF::writeHTML($out_2, true, false, true, false, '');
  //   if (empty($dataBA->ttd_p1)) {
  //     "";
  //   } else {
  //     $TTD_1 = base64_decode($dataBA->ttd_p1);
  //     PDF::Image('@' . $TTD_1, 140, '', 30);
  //   }

  //   if (empty($dataBA->ttd_p2)) {
  //     "";
  //   } else {
  //     $TTD_2 = base64_decode($dataBA->ttd_p2);
  //     PDF::Image('@' . $TTD_2, 42, '', 30);
  //   }

  //   PDF::Ln(25);
  //   PDF::writeHTML($out_2, true, false, true, false, '');

  //   if (empty($dataBA->ttd_kasatpel)) {
  //     "";
  //   } else {
  //     $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
  //     PDF::Image('@' . $TTD_3, 90, '', 30);
  //   }

  //   PDF::Ln(25);
  //   PDF::writeHTML($out_3, true, false, true, false, '');

  //   PDF::Output('BA-' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2 . '.pdf', 'D');
  //   exit;

  //   // dd($dataBA);


  // }
  





    // public function outputk($id)
    // {
    //   $query = \DB::table('logkembali')->where('logkembali.id', $id)
    //     ->join('stokbarang', 'logkembali.id_barang', '=', 'stokbarang.id')
    //     // ->leftjoin('logkembali', 'logkembali.id_peminjaman', '=', 'peminjaman.id')
    //     ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'logkembali.*')
    //     ->first();

    //   $log = LogKembali::where('id_barang', $query->id_barang)->where('tanggal_kembali', $query->tanggal_kembali)->sum('jumlah_kembali');
    
    //   $dataBA = $query;
    //   $TBA = \TANGGAL::TGL($dataBA->tanggal_kembali);
    //   $TanggalBA = \TERBILANG::TBLG($TBA);
    //   $YBA = \TANGGAL::Tahun($dataBA->tanggal_kembali);
    //   $TahunBA = \TERBILANG::TBLG($YBA);


    //   $data = $log;
    //   $JumlahBA = \TERBILANG::TBLG($data);
    //   $Ket1 = ($dataBA->ket1);
    //   $ket2 = ($dataBA->ket2);
    //   $ket3 = ($dataBA->ket3);

    //   $out_1 = view('beritaacara.outEmpat', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA', 'log'));
    //   $out_2 = view('beritaacara.outDua', compact('dataBA'));
    //   $out_3 = view('beritaacara.outTiga', compact('dataBA'));


    //   // TTPDF::setJPEGQuality(75);
    //   PDF::SetAuthor('BA - DATAKOM SARPRAS TI');
    //   PDF::SetTitle('BERITA ACARA SERAH TERIMA - ') . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2;
    //   PDF::SetSubject('BERITA ACARA - SARPRAS TI');
    //   PDF::SetMargins(15, 56, 9, 9);
    //   PDF::SetFontSubsetting(false);
    //   PDF::SetFontSize('9px');
    //   PDF::SetAutoPageBreak(TRUE, 0);

    //   // ------------------------------------------------------
    //   PDF::AddPage('P', 'F4');
    //   PDF::Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
    //   //------------------------------------------------------- BUAT TULISAN BASTB
    //   PDF::writeHTML($out_1, true, false, true, false, '');
    //   if (empty($dataBA->ttd_p1)) {
    //     "";
    //   } else {
    //     $TTD_1 = base64_decode($dataBA->ttd_p1);
    //     PDF::Image('@' . $TTD_1, 140, '', 30);
    //   }

    //   if (empty($dataBA->ttd_p2)) {
    //     "";
    //   } else {
    //     $TTD_2 = base64_decode($dataBA->ttd_p2);
    //     PDF::Image('@' . $TTD_2, 42, '', 30);
    //   }

    //   PDF::Ln(30);
    //   PDF::writeHTML($out_2, true, false, true, false, '');

    //   if (empty($dataBA->ttd_kasatpel)) {
    //     "";
    //   } else {
    //     $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
    //     PDF::Image('@' . $TTD_3, 90, '', 30);
    //   }

    //   PDF::Ln(25);
    //   PDF::writeHTML($out_3, true, false, true, false, '');

    //   PDF::Output('BA-' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2 . '.pdf');
    //   exit;
    // }
  

  // KERANJANG PEMINJAMAN CONTROLLER

  // public function index()
  // {
  //   if ((\Auth::user()->level == 'user')) {
  //     Alert::warning('Anda dilarang masuk ke area ini');
  //     return redirect()->to(url('/home'));
  //   }
  //   // $query = BarangMasuk::stok()->get();
  //   $query = \DB::table('peminjaman')
  //     ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
  //     ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id')
  //     ->join('beritaacara', 'peminjaman.id', '=', 'beritaacara.id_peminjaman')
  //     ->select('stokbarang.nama_barang', 'peminjaman.jumlah_pinjam', 'peminjaman.tanggal_pinjam', 'peminjaman.id', 'peminjaman.id_barang', 'peminjaman.jumlah_kembali', 'peminjaman.tanggal_kembali', 'peminjaman.dipinjam', 'peminjaman.satuan', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'peminjaman.keperluan', 'beritaacara.nomor')
  //     ->orWhere('peminjaman.aktif', 2)
  //     ->orderBy('peminjaman.created_at', 'DESC')
  //     ->get();
  //   $select = StokBarang::select('id', 'nama_barang')->get();

  //   // $log = LogKembali::where('id_barang', $query->id_barang)->sum('jumlah_kembali');


  //   // dd($query);
  //   return view('peminjaman.keranjangpeminjaman', compact('query', 'select'));
  // }


  // public function editkeranjang($id)
  // {
  //   $data = \DB::table('peminjaman')
  //     ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id') //sudah
  //     ->join('beritaacara', 'peminjaman.id', '=', 'beritaacara.id_peminjaman') //sudah fix
  //     ->leftjoin('beritaacara_upload', 'peminjaman.id', '=', 'beritaacara_upload.id_peminjaman')
  //     ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id') //sudah fix
  //     ->select(
  //       'stokbarang.nama_barang',
  //       'stokbarang.jenisbarang',
  //       'stokbarang.tahun_anggaran',
  //       'peminjaman.*',
  //       'beritaacara.id AS BA_ID',
  //       'users.username as NAMA',
  //       'beritaacara_upload.filename AS NAMAFILE'
  //     ) //sudah fix
  //     ->orWhere('peminjaman.aktif', 2) //sudah fix
  //     ->where('stokbarang.jenisbarang', 1) //sudah fix
  //     ->where('peminjaman.id', $id) //sudah fix
  //     ->first();
  //   $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();
  //   // DD($data);
  //   return view('peminjaman.editpeminjaman', compact('data', 'select'));
  // }

  // public function updatekeranjang($id)
  // {
  //   $data = Peminjaman::find($id);

  //   if ($data) {
  //     $data->aktif = 1;
  //     $data->save();

  //     // Optional: Add a success message or perform additional actions
  //     Alert::success('Data updated successfully');
  //     return redirect()->back();
  //   } else {
  //     // Handle the case when the specified $id does not exist

  //     return redirect()->back()->with('error', 'Data not found');
  //   }
  // }

}