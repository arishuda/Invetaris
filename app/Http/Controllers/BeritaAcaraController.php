<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\BeritaAcara;
use App\BarangKeluar;
use App\Peminjaman;
use App\BeritaAcaraUpload;
use App\LogKembali;
use Vinkla\Hashids\Facades\Hashids;
class BeritaAcaraController extends Controller
{
    public function main($id)
    {
        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $query = \DB::table('beritaacara')->where('beritaacara.id', $ids)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            ->join('barangkeluar', 'beritaacara.id_barangkeluar', '=', 'barangkeluar.id')
            // ->join('barangmasuk', 'beritaacara.id_barangkeluar', '=', 'barangmasuk.id_barangkeluar')
            ->select('beritaacara.*', 'stokbarang.nama_barang', 'barangkeluar.id AS BK_ID', 'barangkeluar.keperluan', 'barangkeluar.jumlah_keluar as JK')
            ->first();

        $serialNumbers = \DB::table('barangmasuk')
            ->where('id_barangkeluar', $query->id_barangkeluar)
            ->pluck('serial_number')
            ->toArray();

        $data = $query;

        //   $log = LogKembali::where('id_peminjaman', $query->id)->sum('jumlah_kembali');

        //   dd($data);
        return view('beritaacara', compact('data', 'serialNumbers'));
    }

    public function pinjampakai($id)
    {

        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $query = \DB::table('beritaacara')->where('beritaacara.id', $ids)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            ->join('peminjaman', 'beritaacara.id_peminjaman', '=', 'peminjaman.id')
            ->select('beritaacara.*', 'stokbarang.nama_barang', 'peminjaman.id AS BP_ID', 'peminjaman.keperluan', 'peminjaman.jumlah_pinjam as JK','peminjaman.serialnumber')
            ->first();



        $data = $query;
        return view('beritaacara_pinjampakai', compact('data'));
    }

    public function kembali($id)
    {

        //   $ids = Crypt::decryptString($id);

        $query = \DB::table('beritaacara')->where('beritaacara.id', $id)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            ->join('logkembali', 'beritaacara.id_logkembali', '=', 'logkembali.id')
            ->leftjoin('peminjaman', 'beritaacara.id_peminjaman', '=', 'peminjaman.id')
            ->select('beritaacara.*', 'stokbarang.nama_barang', 'logkembali.id AS LK_ID', 'peminjaman.keperluan', 'peminjaman.jumlah_pinjam as JK')
            ->first();

            $serialNumbers = \DB::table('barangmasuk')
            ->where('id_peminjaman', $query->id_barangkeluar)
            ->pluck('serial_number')
            ->toArray();

        $data = $query;

        //   $log = LogKembali::where('id_peminjaman', $query->id)->sum('jumlah_kembali');

        dd($data);
        return view('beritaacara_kembali', compact('data','serialNumbers'));
    }

    public function mainuser($id)
    {
        // $ids = Crypt::decryptString($id);
        $ids = Hashids::decode($id);
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

        $query = \DB::table('beritaacara')->where('beritaacara.id', $ids)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            // ->leftjoin('peminjaman', 'beritaacara.id_peminjaman', '=', 'peminjaman.id')
            // ->join('barangkeluar', 'beritaacara.id_barangkeluar', '=', 'barangkeluar.id')
            ->select('stokbarang.nama_barang', 'beritaacara.*')
            ->first();

        $data = $query;

        $TBA = \TANGGAL::TGL($data->tanggal_ba);
        $TanggalBA = Tblg($TBA);
        $YBA = \TANGGAL::Tahun($data->tanggal_ba);
        $TahunBA = Tblg($YBA);

        return view('beritaacara.bauser', compact('data', 'TanggalBA', 'TahunBA'));
    }


    public function update(Request $req, $id)
    {

        $Data = BeritaAcara::find($id);

        if ($req->has(["id_barangkeluar"])) {
            ($BK = BarangKeluar::where('id', $req->id_barangkeluar)->first());
            $BK->buatba = $req->buatba; //0 = No BA, 1 = Serah Terima, 2 = Pinjam Pakai, 3 = Upload
            $BK->save();
        } else if ($req->has(["id_peminjaman"])) {
            $BP = Peminjaman::where('id', $req->id_peminjaman)->first();
            $BP->buatba = $req->buatba; //0 = No BA, 1 = Serah Terima, 2 = Pinjam Pakai, 3 = Upload
            $BP->save();
        }


        $Data->id_peminjaman = $req->id_peminjaman;
        $Data->id_barangkeluar = $req->id_barangkeluar;
        $Data->id_barang = $req->id_barang;
        $Data->nomor = $req->nomor;
        $Data->tanggal_ba = $req->tanggal_ba;

        $Data->nama_p1 = $req->nama_p1;
        $Data->ket1 = $req->ket1;
        $Data->ket2 = $req->ket2;
        $Data->ket3 = $req->ket3;
        $Data->nip_p1 = $req->nip_p1;
        $Data->nrk_p1 = $req->nrk_p1;

        $Data->jabatan_p1 = $req->jabatan_p1;

        $Data->nama_p2 = $req->nama_p2;
        $Data->statuskerja_p2 = $req->statuskerja_p2;
        $Data->nip_p2 = $req->nip_p2;
        $Data->nrk_p2 = $req->nrk_p2;
        $Data->nik_p2 = $req->nik_p2;
        $Data->lokasikerja = $req->lokasikerja;
        $Data->jabatan_p2 = $req->jabatan_p2;

        $Data->wilayah = $req->wilayah;
        $Data->serialnumber = $req->serialnumber;
        $Data->jumlah = $req->jumlah;
        $Data->tgl_kembali = $req->tgl_kembali;
        // $Data->foto             = ;

        if ($req->file('foto') == '') {
            $fileName = $Data->foto;
        } else {
            \File::delete(public_path("fotobarang/" . $Data->foto));
            $filefoto = $req->file('foto');
            $fileName = rand(11111111, 99999999) . '-' . $req->tanggal_ba . '.' . $filefoto->getClientOriginalExtension();
            \Image::make($filefoto)->save(public_path("fotobarang/" . $fileName), 50);
        }

        $Data->foto = $fileName;

        if (empty($Data->ttd_p1)) {
            $Data->ttd_p1 = $req->ttd_p1;

            if ($BK = BarangKeluar::where('id', $req->id_barangkeluar)->first()) {
                ;
                $BK->buatba = 1;
                $BK->save();
            } else if ($BP = Peminjaman::where('id', $req->id_peminjaman)->first()) {
                ;
                $BP->buatba = 2; //0 = No BA, 1 = Serah Terima, 2 = Pinjam Pakai, 3 = Upload
                $BP->save();
            } else {
                $LK = LogKembali::where('id', $req->id_logkembali)->first();
                $LK->buatba = 4;
                $LK->save();
            }

        } else {
            "";
        }

        if (empty($Data->ttd_p2)) {
            $Data->ttd_p2 = $req->ttd_p2;
        } else {
            "";
        }

        if (empty($Data->ttd_kasatpel)) {
            $Data->ttd_kasatpel = $req->ttd_kasatpel;
        } else {
            "";
        }

        $Data->save();

        if ($req->has(["id_barangkeluar"])) {
            $BK = "/barangkeluar";
            return redirect($BK);
        } elseif ($req->has(["id_peminjaman"])) {
            $BP = "/peminjaman";
            return redirect($BP);
        }
    }

    public function updateuser(Request $req, $id)
    {
        // $ids = \Crypt::decryptString($id);
        $ids = Hashids::decode($id);
        $Data = BeritaAcara::find($ids);

        if (empty($Data->ttd_p1)) {
            $Data->ttd_p1 = $req->ttd_p1;
        } else {
            "";
        }

        if (empty($Data->ttd_p2)) {
            $Data->ttd_p2 = $req->ttd_p2;
        } else {
            "";
        }

        if (empty($Data->ttd_kasatpel)) {
            $Data->ttd_kasatpel = $req->ttd_kasatpel;
        } else {
            "";
        }

        $Data->save();


        return back();
    }

    public function uploadBA(Request $req)
    {
        $req->validate([
            'dokumen' => 'required|mimes:pdf,xlx,csv|max:2048',
        ]);
        $fileName = time() . '.' . $req->dokumen->getClientOriginalExtension();
        $req->dokumen->move(public_path('uploadba'), $fileName);


        BeritaAcaraUpload::create([
            'id_peminjaman' => $req->id_peminjaman,
            'id_barangkeluar' => $req->id_barangkeluar,
            'id_barang' => $req->id_barang,
            'filename' => $fileName
        ]);

        if (!empty(["id_peminjaman"])) {
            $BK = BarangKeluar::where('id', $req->id_barangkeluar)->first();
            $BK->buatba = 3; //0 = No BA, 1 = Serah Terima, 2 = Pinjam Pakai, 3 = Upload
            $BK->save();

        } elseif (!empty(["id_barangkeluar"])) {
            $BP = Peminjaman::where('id', $req->id_peminjaman)->first();
            $BP->buatba = 3;
            $BP->save();
        }
        // BeritaAcaraUpload::create([
        //     'id_peminjaman'       => $req->id_peminjaman,
        //     'id_barang'             => $req->id_barang,
        //     'filename'              => $fileName
        // ]);

        // $BP = Peminjaman::where('id', $req->id_peminjaman)->first();
        // $BP->buatba = 3;    //0 = No BA, 1 = Serah Terima, 2 = Pinjam Pakai, 3 = Upload
        // $BP->save();

        // $Redirect = route('barangkeluar');
        return redirect()->back();
    }
}