<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StokBarang;
use App\BarangMasuk;
use App\Logging;
use Illuminate\Support\Str;
use App\Exports\BarangMasukExport;
use Maatwebsite\Excel\Facades\Excel;
// use DataTables;
use Yajra\DataTables\Facades\DataTables;
use Vinkla\Hashids\Facades\Hashids;
use RealRashid\SweetAlert\Facades\Alert;
use App\BarangMasukImport;
use App\Exports\BarangMasukTemplateExport;


class BarangMasukController extends Controller
{
  //
  public function export()
  {
    return Excel::download(new BarangMasukExport, 'BarangMasuk.xlsx');
  }
  
  public function main(request $request)
  {
    // if ((\Auth::user()->level == 'user')) {
    //   \Alert::warning('Anda dilarang masuk ke area ini');
    //   return redirect()->to(url('/home'));
    // }

    $query = \DB::table('barangmasuk')
      ->join('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
      ->leftjoin('users', 'barangmasuk.id_user', '=', 'users.id')
      // ->leftjoin('log_easet', 'barangmasuk.id', '=', 'log_barangmasuk.id_barangmasuk')
      ->select('stokbarang.nama_barang', 'barangmasuk.id AS IDBM', 'barangmasuk.jumlah_masuk', 'barangmasuk.stat_barangmasuk AS STATUS', 'barangmasuk.tanggal_masuk', 'barangmasuk.last AS last_update', 'barangmasuk.aktif', 'users.name as NAMA', 'barangmasuk.filename', 'stokbarang.kib')
      ->where('barangmasuk.aktif', 1)
      ->orderBy('barangmasuk.created_at', 'DESC')
      ->get();

    $select['data'] = StokBarang::select('id', 'nama_barang')->where('aktif', 1)->get();

  
  
    
    //  dd($select);
    $title = 'E-Aset | Barang Masuk';
    return view('barangmasuk', compact('query', 'select', 'title'));
  }


      public function import(Request $request)
      {
       
          // Check if the request has a file
          if ($request->hasFile('file' )) {
              $request->validate([
                'file' => 'required|mimes:xlsx,xls',
            ]);
              try {
                  // Initialize the import class and perform the import
                  $import = new BarangMasukImport;
  
                  // Import the file and store it temporarily
                  $imported = Excel::import($import, $request->file('file')->store('temp'));
  
                  // Check if there were any duplicate serial numbers
                  if (count($import->duplicates) > 0) {
                      // Get the duplicates and show an error message
                      $duplicates = implode(', ', $import->duplicates);
                      Alert::error('Duplicate Serial Numbers', "The following serial numbers already exist: $duplicates");
                  } else {
                      // Success message if no duplicates
                      Alert::success('Success', 'Data successfully imported');
                  }
  
                  // Redirect back to the previous page
                  return redirect()->back();
  
              } catch (\Exception $e) {
                  // Handle exceptions if something goes wrong
                  Alert::error('Import Failed', 'There was an error while processing the file: ' . $e->getMessage());
                  return redirect()->back();
              }
  
          } else {
              Alert::error('Import Failed', 'No file found for import');
              return redirect()->back();
          }
      }
  
  
  
  
public function get_barang() {
  $barangMasuk = BarangMasuk::query()
      ->leftJoin('users', 'barangmasuk.id_user', '=', 'users.id')
      ->leftJoin('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
      ->select(
          'stokbarang.nama_barang', 
          'barangmasuk.id AS IDBM', 
          'barangmasuk.jumlah_masuk', 
          'barangmasuk.stat_barangmasuk AS STATUS', 
          'barangmasuk.tanggal_masuk', 
          'barangmasuk.last AS last_update', 
          'barangmasuk.aktif', 
          'users.name as nama', 
          'barangmasuk.filename', 
          'stokbarang.kib',
          'barangmasuk.serial_number',
          'barangmasuk.id_qr',
          'stokbarang.tahun_anggaran',
          'stokbarang.id_qrs'
      )
      ->where('barangmasuk.aktif', 1)
      ->orderBy('barangmasuk.created_at', 'DESC');
      // ->get();
      return DataTables::eloquent($barangMasuk)
      ->addIndexColumn()
      ->filter(function ($query) {
          if (request()->has('search')) {
              $search = request('search')['value'];
              $query->where(function ($q) use ($search) {
                  $q->where('stokbarang.kib', 'like', "%{$search}%")
                    ->orWhere('barangmasuk.serial_number', 'like', "%{$search}%")
                    ->orWhere('stokbarang.nama_barang', 'like', "%{$search}%")
                    ->orWhere('barangmasuk.jumlah_masuk', 'like', "%{$search}%")
                    ->orWhere('barangmasuk.tanggal_masuk', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%")
                    ->orWhere('barangmasuk.last', 'like', "%{$search}%");
              });
          }
      })
      ->toJson();
}

public function downloadTemplate()
{
    return Excel::download(new BarangMasukTemplateExport, 'Template barang Masuk.xlsx');
}


  public function fetchajax($id)
  {
    $ajaxData['data'] = StokBarang::select('id', 'satuan', 'jumlah_sekarang', 'tahun_anggaran')->where('id', $id)->get();
    // return response()->json(['ajaxData' => $ajaxData]);
    echo json_encode($ajaxData);
    exit;
  }

  public function input(Request $req)
  {
    $id_barang = $req->id_barang;
    $serial_numbers = array_column($req->addMoreInputFields, 'serial_number');
    $checkData = BarangMasuk::where('id_barang', $id_barang)
    ->where('aktif',1)
    ->whereIn('serial_number', $serial_numbers)
    ->pluck('serial_number')
    ->toArray();
    //dd($req->all());
    if (!empty($checkData)) {
        Alert::warning('Serial Number Sudah Ada', 'Gagal Menambahkan Barang');
        return redirect()->back();
    } else {
    if (!empty($req["dokumen"])) {
      $req->validate([
        'dokumen' => 'mimes:pdf,xlx,csv|max:2048',
      ]);
      $fileName = time() . '.' . $req->dokumen->getClientOriginalExtension();
      $req->dokumen->move(public_path('uploadsa'), $fileName);
    } else {
      $fileName = "";
    }

    if (!empty($req->file('image'))) {
      $req->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
      ]);
      $imageName = time() . '.' . $req->file('image')->getClientOriginalExtension();
      $req->file('image')->move(public_path('stokbarang'), $imageName);
    } else {
      $imageName = "";
    }

    $req->validate([
      'nama_barang' => 'required',
      'satuan' => 'required',
      'addMoreInputFields.*.serial_number' => 'required_if:jumlah_masuk,null' 
  ]);

  // Create or update the stock item
  
  $short = substr($req->nama_barang, 0, 3);
  $jumlah_awal = intval($req->jumlah_awal);

  $Barang = StokBarang::create([
      'nama_barang' => $req->nama_barang,
      'jumlah_sekarang' => $req->jumlah_awal ?: count($req->addMoreInputFields),
      'jumlah_awal' => $req->jumlah_awal ?: count($req->addMoreInputFields),
      'satuan' => $req->satuan,
      'stokupdate' => now()->format('Y-m-d'),
      'jenisbarang' => $req->jenisbarang,
      'tahun_anggaran' => $req->tahun,
      'lokasi' => $req->lokasi,
      'kib' => $req->kib,
      'image' => $imageName,
      'aktif' => 1,
      'asal_brg' => $req->asal_brg,
      'id_qrs' => $short . $req->tahun . $req->id . $jumlah_awal,
      'last' => now()->format('Y-m-d H:i:s')
  ]);

  
  $QBarang = $Barang->id;

  if ($req->jumlah_awal) {
    $jumlah_awal = intval($req->jumlah_awal);
    $short = substr($req->nama_barang, 0, 3);
    if ($jumlah_awal > 0) {
        for ($i = 0; $i < $jumlah_awal; $i++) {
          $serial_number = "PSDTN" .$short .$QBarang .($i + 1);
          $BM = BarangMasuk::create([
                'id_barang' => $QBarang,
                'stat_barangmasuk' => 1,
                'jumlah_masuk' => 1,
                'serial_number' => $serial_number ,
                'satuan' => $req->satuan,
                'tanggal_masuk' => now(),
                'id_user' => auth()->id(),
                'id_qr' => Str::upper(Str::random(5)),
                'aktif' => 1,
                'last' => now(),
                'filename' => $fileName
            ]);
        }

        Logging::create([
            'log' => "BMASUK",
            'id_barang' => $QBarang,
            'id_user' => auth()->id(),
            'last' => now(),
            'desc' => "Menambah Stok Barang. Nama Barang: " . $Barang->nama_barang . " Sebanyak " . $jumlah_awal . " " . $req->satuan
        ]);
    }

} else {
  $total_jumlah_masuk = 0;
    foreach ($req->addMoreInputFields as $key => $value) {
    $total_jumlah_masuk += isset($value['serial_number']) ? 1 : 0;
    // $serialNumber = isset($value['serial_number']) ? trim($value['serial_number'], '"') : null;
      $BM = BarangMasuk::create([
      'id_barang' => $QBarang,
      'stat_barangmasuk' => 1,
      'jumlah_masuk' => isset($req->addMoreInputFields[$key]['serial_number']) ? 1 : 0,
      'serial_number' => isset($value['serial_number']) ? trim(json_encode($value['serial_number']), '"') : null,
      'satuan' => $req->satuan,
      'id_qr' => Str::upper(Str::random(5)),
      'tanggal_masuk' => date("Y-m-d"),
      'id_user' => \Auth::user()->id,
      'aktif' => 1,
      'last' => date('Y-m-d H:i:s'),
      'filename' => $fileName
    ]);
  }

    $QBM = $BM->id;
    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $QBarang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menginput Stok Barang. Nama Barang : " . $req->nama_barang . " Sebanyak " . $total_jumlah_masuk . " " . $req->satuan
    ]);

  
    $Barang->save();
    $BM->save();
  }
}

    $Redirect = route('barangmasuk');
    return redirect($Redirect);
  }

  public function qrcode($id)
  {
    $decodedIdArray = Hashids::decode($id);
    if (empty($decodedIdArray)) {
        abort(404, 'Invalid ID'); 
    }
    $ids = $decodedIdArray[0];
    $qrcode = BarangMasuk::findOrFail($ids)
      ->leftjoin('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
      ->select('stokbarang.nama_barang', 'barangmasuk.id','barangmasuk.aktif','barangmasuk.serial_number','barangmasuk.id_qr')
      ->findOrFail($ids);
      $title = 'E-Aset | Detail QR'; 
  return view('qrcode', compact('qrcode','title'));
  }

  public function tambah(Request $req)
  {
    $id_barang = $req->id_barang;
    $serial_numbers = array_column($req->addMoreInputFields, 'serial_number');
    $checkData = BarangMasuk::where('id_barang', $id_barang)
    ->where('aktif', 1)
    ->whereIn('serial_number', $serial_numbers)
    ->pluck('serial_number')
    ->toArray();
    //dd($req->all());
    if (!empty($checkData)) {
        Alert::warning('Serial Number Sudah Ada', 'Gagal Menambahkan Barang');
        return redirect()->back();
    } else {
    if ($req->jumlah_masuk) {
      $jumlah_masuk = intval($req->jumlah_masuk);
    
      if ($jumlah_masuk > 0) {
          $Barang = StokBarang::find($req->id_barang);
          $Barang->jumlah_sekarang += $jumlah_masuk;
          $Barang->last = now();
          $Barang->stokupdate = now();
        
          $Barang->save();

        $short = substr($Barang->nama_barang, 0, 3);
          for ($i = 0; $i < $jumlah_masuk; $i++) {
            $random_number = rand(1, 99999);
            $serial_number = "PSDTN" . $short . $req->id_barang . $random_number;

              BarangMasuk::create([
                  'id_barang' => $req->id_barang,
                  'stat_barangmasuk' => 2,
                  'jumlah_masuk' => 1,
                  'serial_number' => $serial_number,
                  'satuan' => $req->satuan,
                  'tanggal_masuk' => now(),
                  'id_user' => auth()->id(),
                  'id_qr' => Str::upper(Str::random(5)),
                  'aktif' => 1,
                  'last' => now()
              ]);
          }

          Logging::create([
              'log' => "BMASUK",
              'id_barang' => $req->id_barang,
              'id_user' => auth()->id(),
              'last' => now(),
              'desc' => "Menambah Stok Barang. Nama Barang: " . $Barang->nama_barang . " Sebanyak " . $jumlah_masuk . " " . $req->satuan
          ]);
      }

  //     $count_by_jumlah_awal = StokBarang::where('id_barang', $req->id_barang)
  //     ->sum('jumlah_awal');

  // // Display or use $count_by_jumlah_awal as needed
  // dd($count_by_jumlah_awal);
  
} else {
  
    $total_jumlah_masuk = 0;
    foreach ($req->addMoreInputFields as $key => $value) {
        $total_jumlah_masuk += isset($value['serial_number']) ? 1 : 0;
        $Barang = StokBarang::where('id', $req->id_barang)->first();
        $jumlah_masuk = isset($req->addMoreInputFields[$key]['serial_number']) ? 1 : 0;
        $tambahbarang = $Barang->jumlah_sekarang + $jumlah_masuk;
        $Barang->jumlah_sekarang = $tambahbarang;
        $Barang->last = date('Y-m-d H:i:s');
        $Barang->stokupdate = date("Y-m-d");
        $Barang->save();

        $QBarang = $Barang->id;

        $BM = BarangMasuk::create([
            'id_barang' => $req->id_barang,
            'stat_barangmasuk' => 2,
            //bukan barang stok
            'jumlah_masuk' => isset($req->addMoreInputFields[$key]['serial_number']) ? 1 : 0,
            'satuan' => $req->satuan,
            'serial_number' => isset($value['serial_number']) ? trim(json_encode($value['serial_number']), '"') : null,
            'tanggal_masuk' => date("Y-m-d"),
            'id_user' => \Auth::user()->id,
            'id_qr' => Str::upper(Str::random(5)),
            'aktif' => 1,
            'last' => date('Y-m-d H:i:s')
        ]);
        $QBM = $BM->id;
    }

    Logging::create([
        'log' => "BMASUK",
        'id_barang' => $req->id_barang,
        'id_user' => \Auth::user()->id,
        'last' => date('Y-m-d H:i:s'),
        'desc' => "Menambah Stok Barang. Nama Barang : " . $Barang->nama_barang . " Sebanyak " . $total_jumlah_masuk . " " . $req->satuan
    ]);
  }
}
// dd($jumlah_masuk);
    return redirect('/barangmasuk');

    // dd($tambahbarang);
  }

  public function delete($id)
  {
    // $decodedIdArray = Hashids::decode($id);
    // if (empty($decodedIdArray)) {
    //     abort(404, 'Invalid ID'); 
    // }
    // $ids = $decodedIdArray[0];

    $BarangMasuk = BarangMasuk::find($id);
    $BarangStok = StokBarang::where('id', $BarangMasuk->id_barang)->first();
    // $BarangBA       = BeritaAcara::where('id_barangkeluar', $id)->first();

    $StokKembali = $BarangStok->jumlah_sekarang - $BarangMasuk->jumlah_masuk;
    // $StokAwal = $BarangStok->jumlah_awal - $BarangMasuk->jumlah_masuk;
    // Update Data Barang Stok
    $BarangStok->jumlah_sekarang = $StokKembali;
    // $BarangStok->jumlah_awal = $StokAwal;
    $BarangStok->save();

    Logging::create([
      'log' => "BMASUK",
      'id_barang' => $BarangMasuk->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Menghapus List Barang Masuk. Nama Barang : " . $BarangStok->nama_barang . " Sebanyak " . $BarangMasuk->jumlah_masuk . " " . $BarangMasuk->satuan
    ]);

    Logging::create([
      'log' => "BSTOK",
      'id_barang' => $BarangMasuk->id_barang,
      'id_user' => \Auth::user()->id,
      'last' => date('Y-m-d H:i:s'),
      'desc' => "Mengurangi Stok Barang. Nama Barang : " . $BarangStok->nama_barang . " Sebanyak " . $BarangMasuk->jumlah_masuk . " " . $BarangMasuk->satuan
    ]);


    // Hapus Data Berita Acara
    // if(empty($BarangBA->foto)){
    // } else{
    //   \File::delete(public_path("fotobarang/".$BarangBA->foto));
    // }

    // $BarangBA->delete();

    // Hapus Data Barang Keluar
    $BarangMasuk->aktif = 0;
    $BarangMasuk->save();




    return redirect('/barangmasuk');
  }
}