<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\{StokBarang, BarangKeluar, BarangMasuk, BeritaAcara, HistoryBarangKeluar, Logging};

use DataTables;
use App\Exports\BarangKeluarExport;
use Maatwebsite\Excel\Facades\Excel;
// use Hashids\Hashids;
use Vinkla\Hashids\Facades\Hashids;

class BarangKeluarController extends Controller
{
  public function index_server(Request $request)
  {
    $query = \DB::table('barangkeluar')
      ->join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
      ->leftjoin('barangmasuk','barangkeluar.id', '=', 'barangmasuk.id_barangkeluar')
      ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')
      ->leftjoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
      ->leftjoin('users', 'barangkeluar.id_user', '=', 'users.id')
      ->select(
        'stokbarang.nama_barang',
        'stokbarang.jenisbarang',
        'stokbarang.tahun_anggaran',
        'barangkeluar.*',
        // 'barangmasuk.serial_number',
        // 'barangmasuk.*',
        'users.username as NAMA'
      )
      ->orWhere('barangkeluar.aktif', 1)
      ->where('stokbarang.jenisbarang', 1)
      // ->where('barangmasuk.aktif', 1)
      ->orderBy('barangkeluar.created_at', 'DESC')
      // ->groupBy("barangkeluar.id","barangmasuk.id")
      ->groupBy("barangkeluar.id")
      ->get();

    // dd($query);
    $select['data'] = StokBarang::select('id', 'nama_barang')
    ->where('aktif', 1)
    ->whereIn('jenisbarang', [1,2,3])
    ->get();

    //dd($query);
    /////////////////////////////////////////////
    if ($request->ajax()) {
      
      // $data = $query;
      return Datatables::of($query)
        ->addIndexColumn()
        ->addColumn('nama_barang', function ($row) {
          return $row->nama_barang;
        })
        // ->addColumn('serial_number', function ($row){
        //   //  return implode(', ', $row->serial_number);
        //   return $row->serial_number;
        // })
        ->addColumn('tahun_anggaran', function ($row) {
          return $row->tahun_anggaran;
        })
        ->addColumn('jumlah_keluar', function ($row) {
          return $row->jumlah_keluar;
        })
        ->addColumn('tanggal_keluar', function ($row) {
          return $row->tanggal_keluar ?? '-';
        })
        ->addColumn('diambil', function ($row) {
          return $row->diambil ?? '-';
        })
        ->addColumn('keperluan', function ($row) {
          return $row->keperluan ?? '-';
        })
        ->addColumn('action', function ($row) {
          $encodedId = Hashids::encode($row->id);
          $btn = '';
          $btn .= '<a href="./barangkeluar/edit/' . $encodedId  . '" class="btn btn-sm btn-raised btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-eye"></i></a>&nbsp;';
          if(\Auth::user()->level == 'superadmin' && 'admin'){
          $btn .= '<a href="./barangkeluar/delete/' . $row->id . '" class="btn btn-sm btn-raised btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return ConfirmDelete();"><i class="fa fa-trash-alt"></i></a>';
          }
          return $btn;
        })
        ->rawColumns(['nama_barang', 'tahun_anggaran', 'jumlah_keluar', 'tanggal_keluar', 'diambil', 'keperluan', 'action'])
        ->make(true);
    }
    // dd($query);

    $title = 'E-Aset | Barang Keluar';
    
    return view('barangkeluar.barangkeluar', compact('select', 'query','title'));
  }

  

  public function export()
  {
    return Excel::download(new BarangKeluarExport, 'BarangKeluar.xlsx');
  }
  

//   public function get_barangk() {
    
//       $BarangKeluar = BarangKeluar::join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
//       // ->join('barangmasuk','barangkeluar.id_barang', '=', 'barangmasuk.id_barang')
//       ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')
//       ->leftjoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
//       ->leftjoin('users', 'barangkeluar.id_user', '=', 'users.id')
//       ->select(
//         'stokbarang.nama_barang',
//         'stokbarang.jenisbarang',
//         'stokbarang.tahun_anggaran',
//         'barangkeluar.*',
//         // 'barangmasuk.serial_number',
//         // 'barangmasuk.*',
//         'users.username as NAMA'
//       )
//       ->orWhere('barangkeluar.aktif', 1)
//       ->where('stokbarang.jenisbarang', 1)
//       // ->where('barangmasuk.aktif', 1)
//       ->groupBy("barangkeluar.id")
//       ->orderBy('barangkeluar.created_at', 'DESC');

//     return Datatables::eloquent($BarangKeluar)
//         ->addIndexColumn() 
//         ->toJson();
// }

  

  public function fetchajax($id)
  {
      $ajaxData['data'] = StokBarang::select('stokbarang.id', 'stokbarang.satuan', 'stokbarang.jumlah_sekarang', 'stokbarang.tahun_anggaran')
                                      ->where('stokbarang.id', $id)
                                      ->where('stokbarang.aktif', 1)
                                      ->wherein('stokbarang.jenisbarang', [1,2,3])
                                      ->first(); 
  
      if ($ajaxData['data']) {
          $serialNumbers = \DB::table('barangmasuk')
                              ->select('id_qr','serial_number')
                              ->where('id_barang', $ajaxData['data']->id)
                              ->whereIn('stat_barangmasuk', [1,2])
                              ->whereIn('aktif', [1])
                              ->get()
                              // ->pluck('serial_number')
                              ->toArray();
  
          // Add serial numbers to ajaxData
          $ajaxData['serial_numbers'] = $serialNumbers;
      }
  
      return response()->json($ajaxData);
  }
  

  public function kurang(Request $req)
  {
    // dd($req->all());
      $Barang = StokBarang::where('id', $req->id_barang)->first();
      
      $jumlah_keluar = is_array($req->serial_number) ? count($req->serial_number) : 0;
  
      if ($jumlah_keluar == 0) {
        Alert::warning('Pilih setidaknya satu nomor seri.');
    } elseif ($jumlah_keluar > $Barang->jumlah_sekarang) {
        Alert::warning('Jumlah Barang Keluar tidak sesuai dengan Stok Yang ada');
    } else {
          if ($Barang->jenisbarang == "1") {
            if (is_array($req->serial_number)) {
              $total_quantity = count($req->serial_number);
                
                  $BK = BarangKeluar::create([
                      'id_barang' => $req->id_barang,
                      'jumlah_keluar' =>  $total_quantity, 
                      'satuan' => $req->satuan,
                      'diambil' => $req->diambil,
                      'tanggal_keluar' => $req->tanggal_keluar,
                      'keperluan' => $req->keperluan,
                      'buatba' => 0,
                      'id_user' => \Auth::user()->id,
                      'aktif' => 1,
                      'last' => now(),
                  ]);
                  
                  $serialNumbers = $req->serial_number;
                  $idQrs = $req->id_qr;
                  foreach ($serialNumbers as $index => $serial_number) {
                    $id_qr =isset($idQrs[$index]) ? $idQrs[$index] : null;
                  BarangMasuk::where('serial_number', $serial_number)
                      ->where('id_qr', $id_qr)
                      ->where('id_barang',$req->id_barang)
                      ->where('aktif',1)
                      ->update([
                          'stat_barangmasuk' => 3,
                          'id_barangkeluar' => $BK->id,
                      ]);
                    }
                  HistoryBarangKeluar::create([
                      'id_barang' => $req->id_barang,
                      'id_barangkeluar' => $BK->id,
                      'jumlah_keluar' => $total_quantity, 
                      'satuan' => $req->satuan,
                      'diambil' => $req->diambil,
                      'tanggal_keluar' => $req->tanggal_keluar,
                      'keperluan' => $req->keperluan,
                      'id_user' => \Auth::user()->id,
                      'aktif' => 1,
                      'last' => now(),
                  ]);
  
                  BeritaAcara::create([
                      'id_barangkeluar' => $BK->id,
                      'id_barang' => $req->id_barang,
                      'nama_p2' => $req->nama_p2,
                      'statuskerja_p2' => $req->statuskerja_p2,
                      'nip_p2' => $req->nip_p2,
                      'nrk_p2' => $req->nrk_p2,
                      'nik_p2' => $req->nik_p2,
                      'lokasikerja' => $req->lokasikerja,
                      'id_peminjaman' => 0,
                      'serialnumber' => implode(', ', $req->serial_number),
                      'jabatan_p2' => $req->jabatan_p2
                  ]);
                
            }
            //dd($jumlah_keluar);
          } elseif ($Barang->jenisbarang == "2" || $Barang->jenisbarang == "3") {
              HistoryBarangKeluar::create([
                  'id_barang' => $req->id_barang,
                  'id_barangkeluar' => 0,
                  'jumlah_keluar' => $jumlah_keluar,
                  'satuan' => $req->satuan,
                  'diambil' => $req->diambil,
                  'tanggal_keluar' => $req->tanggal_keluar,
                  'keperluan' => $req->keperluan,
                  'id_user' => \Auth::user()->id,
                  'aktif' => 1,
                  'last' => now(),
              ]);
          }
  
          $Barang->jumlah_sekarang -= $jumlah_keluar; // Deduct the quantity from current stock
          $Barang->save();
  
          // Logging actions
          Logging::create([
              'log' => "BKELUAR",
              'id_barang' => $req->id_barang,
              'id_user' => \Auth::user()->id,
              'last' => now(),
              'desc' => "Mengeluarkan Barang. Nama Barang : " . $Barang->nama_barang . " Sebanyak " . $jumlah_keluar . " " . $req->satuan . " (Keperluan :" . $req->keperluan . " diambil :" . $req->diambil . ")"
          ]);
  
          Logging::create([
              'log' => "BSTOK",
              'id_barang' => $req->id_barang,
              'id_user' => \Auth::user()->id,
              'last' => now(),
              'desc' => "Mengurangi Barang. Nama Barang : " . $Barang->nama_barang . " Sebanyak " . $jumlah_keluar . " " . $req->satuan . " (Keperluan :" . $req->keperluan . " diambil :" . $req->diambil . ")"
          ]);
      }
  
      // Redirecting after processing
      $Redirect = route('barangkeluar');
      return redirect($Redirect);
  }
  



  public function update(Request $req, $id)
  {

    $Barang = StokBarang::where('id', $req->id_barang)->first();
    $data = BarangKeluar::find($id);
    // $data->diambil = $req->diambil;

    Logging::create([
      'log' => "BKELUAR",
      'id_barang' => $req->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Memperbarui Data Keluar Barang. Nama Barang : " . $Barang->nama_barang . " (" . $req->keperluan . ")"
    ]);

    $data->keperluan = $req->keperluan;
    $data->save();

    return redirect('/barangkeluar');
  }

  public function delete($id)
  {

    $BarangKeluar = BarangKeluar::find($id);
    $BarangStok = StokBarang::where('id', $BarangKeluar->id_barang)->first();
    $HBarangKeluar = HistoryBarangKeluar::where('id_barangkeluar', $BarangKeluar->id)->first();
    $Beritaacara = BeritaAcara::where('id_barangkeluar', $BarangKeluar->id)->first();
    $BarangMasuk =  BarangMasuk::where('id_barangkeluar',$BarangKeluar->id)->get();

    // $BarangBA       = BeritaAcara::where('id_barangkeluar', $id)->first();
    foreach ($BarangMasuk as $item) {
      $item->where(['id',$BarangKeluar->id_barang]);
      $item->update([
          'stat_barangmasuk' => 2,
          'id_barangkeluar' => null,
      ]);
      // dd($item);  
  }

    $StokKembali = $BarangKeluar->jumlah_keluar + $BarangStok->jumlah_sekarang;


    $BarangStok->jumlah_sekarang = $StokKembali;
    $BarangStok->save();


    $BarangKeluar->delete();

    $Beritaacara->delete();
    $HBarangKeluar->delete();
    Alert::success('Data Berhasil Dihapus');



    Logging::create([
      'log' => "BKELUAR",
      'id_barang' => $BarangKeluar->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menghapus Data dari list Keluar Barang. Nama Barang : " . $BarangStok->nama_barang
    ]);

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $BarangKeluar->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menambah Stok Barang dari Data yang dihapus pada Keluar Barang. Nama Barang : " . $BarangStok->nama_barang . " Sebanyak " . $BarangKeluar->jumlah_keluar . " " . $BarangKeluar->satuan
    ]);

    return redirect('/barangkeluar');
  }

  public function edit($id)
  {
      $decodedIdArray = Hashids::decode($id);
      if (empty($decodedIdArray)) {
          abort(404, 'Invalid ID'); 
      }
      $ids = $decodedIdArray[0];
  
      $data = \DB::table('barangkeluar')
          ->join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
          ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')
          ->leftJoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
          ->leftJoin('users', 'barangkeluar.id_user', '=', 'users.id')
          ->select(
              'stokbarang.nama_barang',
              'stokbarang.jenisbarang',
              'stokbarang.tahun_anggaran',
              'barangkeluar.*',
              'beritaacara.id AS BA_ID',
              'users.username as NAMA',
              'beritaacara_upload.filename AS NAMAFILE'
          )
          ->orWhere('barangkeluar.aktif', 1)
          ->where('stokbarang.jenisbarang', 1)
          ->where('barangkeluar.id', $ids)
          ->first();
  
      $serialNumbers = \DB::table('barangmasuk')
          ->where('id_barangkeluar', $ids)
          ->pluck('serial_number')
          ->toArray();
  
      $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();
  
      $title = 'E-Aset | Edit';
      
      return view('barangkeluar.editbarangkeluar', compact('data', 'serialNumbers', 'select', 'title'));
  }

  //Keranjang Barang Keluar

  // public function index(Request $request)
  // { {
  //     if ((\Auth::user()->level == 'user')) {
  //       \Alert::warning('Anda dilarang masuk ke area ini');
  //       return redirect()->to(url('/home'));
  //     }
  //     $query = \DB::table('barangkeluar')
  //       ->join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
  //       ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')
  //       ->leftjoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
  //       ->leftjoin('users', 'barangkeluar.id_user', '=', 'users.id')
  //       ->select(
  //         'stokbarang.nama_barang',
  //         'stokbarang.jenisbarang',
  //         'stokbarang.tahun_anggaran',
  //         'barangkeluar.*',
  //         'beritaacara.nomor',
  //         'users.username as NAMA'
  //       )
  //       ->orWhere('barangkeluar.aktif', 2)
  //       ->where('stokbarang.jenisbarang', 1)
  //       ->orderBy('barangkeluar.created_at', 'DESC')
  //       ->get();

  //     // dd($query);
  //     $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();

  //     /////////////////////////////////////////////
  //     if ($request->ajax()) {
  //       // $data = $query;
  //       return Datatables::of($query)
  //         ->addIndexColumn()
  //         ->addColumn('nama_barang', function ($row) {
  //           return $row->nama_barang;
  //         })
  //         ->addColumn('tahun_anggaran', function ($row) {
  //           return $row->tahun_anggaran;
  //         })
  //         ->addColumn('jumlah_keluar', function ($row) {
  //           return $row->jumlah_keluar;
  //         })
  //         ->addColumn('tanggal_keluar', function ($row) {
  //           return $row->tanggal_keluar ?? '-';
  //         })
  //         ->addColumn('diambil', function ($row) {
  //           return $row->diambil ?? '-';
  //         })
  //         ->addColumn('keperluan', function ($row) {
  //           return $row->keperluan ?? '-';
  //         })
  //         ->addColumn('action', function ($row) {
  //           $btn = '';

  //           $btn .= '<a href="./barangkeluar/edit/keranjang/' . $row->id . '" class="btn btn-sm btn-raised btn-success" data-toggle="tooltip" data-placement="bottom" title="Edit"><i class="fa fa-pencil-alt"></i></a>&nbsp;';
  //           $btn .= '<a href="./barangkeluar/delete/' . $row->id . '" class="btn btn-sm btn-raised btn-danger" data-toggle="tooltip" data-placement="bottom" title="Delete" onclick="return ConfirmDelete();"><i class="fa fa-trash-alt"></i></a>&nbsp;';

  //           if (is_null($row->nomor)) {
  //             $btn .= '<button type="submit" class="btn btn-sm  btn-warning" data-toggle="tooltip" data-placement="bottom">';
  //             $btn .= '<i class="fa fa-times"></i>';
  //             $btn .= '</button>';
  //           } else {
  //             $btn .= '<form action="' . route('updatekeranjang', $row->id) . '" method="post">';
  //             $btn .= csrf_field(); // Add CSRF token field for form protection
  //             $btn .= method_field('post'); // Add method field for POST request
  //             $btn .= '<button type="submit" class="btn btn-sm btn-raised btn-primary" data-toggle="tooltip" data-placement="bottom">';
  //             $btn .= '<i class="fas fa-check"></i>';
  //             $btn .= '</button>';
  //             $btn .= '</form>';
  //           }
  //           return $btn;
  //         })

  //         ->rawColumns(['nama_barang', 'tahun_anggaran', 'jumlah_keluar', 'tanggal_keluar', 'diambil', 'keperluan', 'action'])
  //         ->make(true);
  //     }

  //     return view('barangkeluar.keranjangkeluar', compact('select', 'query'));
  //   }
  // }

  // public function editk($id)
  // {
    
  //   $data = \DB::table('barangkeluar')
  //     ->join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
  //     ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')

  //     ->leftjoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
  //     ->leftjoin('users', 'barangkeluar.id_user', '=', 'users.id')
  //     ->select(
  //       'stokbarang.nama_barang',
  //       'stokbarang.jenisbarang',
  //       'stokbarang.tahun_anggaran',
  //       'barangkeluar.*',
  //       'beritaacara.id AS BA_ID',
  //       'users.username as NAMA',
  //       'beritaacara_upload.filename AS NAMAFILE'
  //     )
  //     ->orWhere('barangkeluar.aktif', 2)
  //     ->where('stokbarang.jenisbarang', 1)
  //     ->where('barangkeluar.id', $id)
  //     ->first();
  //   $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();
  //   // DD($data);
  //   return view('barangkeluar.editbarangkeluar', compact('data', 'select'));
  // }
  // public function updatek($id)
  // {
  //   $data = BarangKeluar::find($id);

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
