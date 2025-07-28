<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;

use App\BarangKeluar;
use App\BeritaAcara;
use Vinkla\Hashids\Facades\Hashids;

class OutputController extends Controller
{
    //
    public function output(Request $request , $id)
    {
        // $dataBA = BeritaAcara::find($id);
        function TBLG($nilai)
        {
            $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            if ($nilai == 0) {
                return "";
            } elseif ($nilai < 12 && $nilai != 0) {
            return isset($huruf[$nilai]) ? $huruf[$nilai] : "";
            } elseif ($nilai < 20) {
                return TBLG($nilai - 10) . " Belas ";
            } elseif ($nilai < 100) {
                return TBLG($nilai / 10) . " Puluh " . TBLG($nilai % 10);
            } elseif ($nilai < 200) {
                return " Seratus " . TBLG($nilai - 100);
            } elseif ($nilai < 1000) {
                return TBLG($nilai / 100) . " Ratus " . TBLG($nilai % 100);
            } elseif ($nilai < 2000) {
                return " Seribu " . TBLG($nilai - 1000);
            } elseif ($nilai < 1000000) {
                return TBLG($nilai / 1000) . " Ribu " . TBLG($nilai % 1000);
            } elseif ($nilai < 1000000000) {
                return TBLG($nilai / 1000000) . " Juta " . TBLG($nilai % 1000000);
            } elseif ($nilai < 1000000000000) {
                return TBLG($nilai / 1000000000) . " Milyar " . TBLG($nilai % 1000000000);
            } elseif ($nilai < 100000000000000) {
                return TBLG($nilai / 1000000000000) . " Trilyun " . TBLG($nilai % 1000000000000);
            } elseif ($nilai <= 100000000000000) {
                return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
            }
        }

        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $query = \DB::table('beritaacara')->where('beritaacara.id', $ids)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            // ->join('barangmasuk', 'beritaacara.id_barangkeluar', '=', 'barangmasuk.id_barangkeluar')
            ->leftjoin('barangkeluar', 'beritaacara.id_barangkeluar', '=', 'barangkeluar.id')
            ->leftjoin('peminjaman', 'beritaacara.id_peminjaman', '=', 'peminjaman.id')
            ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'beritaacara.*', 'peminjaman.id', 'peminjaman.keperluan','peminjaman.serialnumber','barangkeluar.id')
            ->first();

            $serialNumbers = \DB::table('barangmasuk')
            ->where('id_barangkeluar', $query->id_barangkeluar)
            //->orwhere('id_peminjaman', $query->id_peminjaman)
            ->pluck('serial_number')
            ->toArray();

            //dd($query);

        $dataBA = $query;
        $TBA = \TANGGAL::TGL($dataBA->tanggal_ba);
        $TanggalBA = ucwords(TBLG($TBA));
        $YBA = \TANGGAL::Tahun($dataBA->tanggal_ba);
        $TahunBA = TBLG($YBA);
        $JumlahBA = TBLG($dataBA->jumlah);
        $Ket1 = ($dataBA->ket1);
        $ket2 = ($dataBA->ket2);
        $ket3 = ($dataBA->ket3);

        $out_1 = view('beritaacara.outSatu', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA','serialNumbers'));
        $out_2 = view('beritaacara.outDua', compact('dataBA'));
        $out_3 = view('beritaacara.outTiga', compact('dataBA'));
        // $out_4 = view('beritaacara.outEmpat',compact('dataBA'));


        // TTPDF::setJPEGQuality(75);
        PDF::SetAuthor('BA - DATAKOM SARPRAS TI');
        PDF::SetTitle('BERITA ACARA SERAH TERIMA - ' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2);
        PDF::SetSubject('BERITA ACARA - SARPRAS TI');
        PDF::SetMargins(10, 20, 10, true);
        // PDF::SetMargins(15, 56, 9, 9);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('9px');
        PDF::SetAutoPageBreak(TRUE, 0);

        // ------------------------------------------------------
        PDF::AddPage('L', 'F4');
        //image kop surat
        // PDF::Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
        //------------------------------------------------------- BUAT TULISAN BASTB
        PDF::writeHTML($out_1, true, false, true, false, '');

        //-------------------------------------------------------
        // PDF::Ln(10);
        // PDF::writeHTML($out_2, true, false, true, false, '');
        if (empty($dataBA->ttd_p1)) {
            "";
        } else {
            $TTD_1 = base64_decode($dataBA->ttd_p1);
            PDF::Image('@' . $TTD_1, 140, '', 30);
        }

        if (empty($dataBA->ttd_p2)) {
            "";
        } else {
            $TTD_2 = base64_decode($dataBA->ttd_p2);
            PDF::Image('@' . $TTD_2, 42, '', 30);
        }

        PDF::Ln(30);
        PDF::writeHTML($out_2, true, false, true, false, '');

        if (empty($dataBA->ttd_kasatpel)) {
            "";
        } else {
            $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
            PDF::Image('@' . $TTD_3, 90, '', 30);
        }

        PDF::Ln(30);
        PDF::writeHTML($out_3, true, false, true, false, '');

        PDF::Output('BA-' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2 . '.pdf');
        exit;

        // dd($dataBA);


    }

    public function download($id)
    {
        // $dataBA = BeritaAcara::find($id);
        function TBLG($nilai)
        {
            $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
            if ($nilai == 0) {
                return "";
            } elseif ($nilai < 12 && $nilai != 0) {
            return isset($huruf[$nilai]) ? $huruf[$nilai] : "";
            } elseif ($nilai < 20) {
                return TBLG($nilai - 10) . " Belas ";
            } elseif ($nilai < 100) {
                return TBLG($nilai / 10) . " Puluh " . TBLG($nilai % 10);
            } elseif ($nilai < 200) {
                return " Seratus " . TBLG($nilai - 100);
            } elseif ($nilai < 1000) {
                return TBLG($nilai / 100) . " Ratus " . TBLG($nilai % 100);
            } elseif ($nilai < 2000) {
                return " Seribu " . TBLG($nilai - 1000);
            } elseif ($nilai < 1000000) {
                return TBLG($nilai / 1000) . " Ribu " . TBLG($nilai % 1000);
            } elseif ($nilai < 1000000000) {
                return TBLG($nilai / 1000000) . " Juta " . TBLG($nilai % 1000000);
            } elseif ($nilai < 1000000000000) {
                return TBLG($nilai / 1000000000) . " Milyar " . TBLG($nilai % 1000000000);
            } elseif ($nilai < 100000000000000) {
                return TBLG($nilai / 1000000000000) . " Trilyun " . TBLG($nilai % 1000000000000);
            } elseif ($nilai <= 100000000000000) {
                return "Maaf Tidak Dapat di Prose Karena Jumlah nilai Terlalu Besar ";
            }
        }

        $decodedIdArray = Hashids::decode($id);
        if (empty($decodedIdArray)) {
            abort(404, 'Invalid ID'); 
        }
        $ids = $decodedIdArray[0];

        $query = \DB::table('beritaacara')->where('beritaacara.id', $ids)
            ->join('stokbarang', 'beritaacara.id_barang', '=', 'stokbarang.id')
            // ->join('barangkeluar', 'beritaacara.id_barangkeluar', '=', 'barangkeluar.id')
            ->leftjoin('peminjaman', 'beritaacara.id_peminjaman', '=', 'peminjaman.id')
            ->select('stokbarang.nama_barang', 'stokbarang.satuan', 'beritaacara.*', 'peminjaman.id', 'peminjaman.keperluan')
            ->first();

            $serialNumbers = \DB::table('barangmasuk')
            ->where('id_barangkeluar', $query->id_barangkeluar)
            ->pluck('serial_number')
            ->toArray();


        $dataBA = $query;
        $TBA = \TANGGAL::TGL($dataBA->tanggal_ba);
        $TanggalBA = TBLG($TBA);
        $YBA = \TANGGAL::Tahun($dataBA->tanggal_ba);
        $TahunBA = TBLG($YBA);
        $JumlahBA = TBLG($dataBA->jumlah);

        $out_1 = view('beritaacara.outSatu', compact('dataBA', 'TanggalBA', 'TahunBA', 'JumlahBA','serialNumbers'));
        $out_2 = view('beritaacara.outDua', compact('dataBA'));
        $out_3 = view('beritaacara.outTiga', compact('dataBA'));

        // $pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // // Custom Footer
        // TTPDF::setFooterCallback(function($pdf) {

        //     // Position at 15 mm from bottom
        //     $pdf->SetXY(-15,-15);
        //     // Set font
        //     $pdf->SetFont('helvetica', 'I', 8);
        //     // Page number
        //     $pdf->Cell(10, 10, 'Page '.$pdf->getAliasNumPage().'/'.$pdf->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');

        //     $pdf->SetXY(10,318);
        //     // Set font
        //     $pdf->SetFont('helvetica', 'I', 8);
        //     // Page number
        //     $pdf->Cell(127, 0, 'Dokumen ini ditandatangani secara elektronik menggunakan sertifikat elektronik dari BSrE BSSN', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        //     // $pdf->Cell(10, 10, 'Test', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // });

        // TTPDF::setJPEGQuality(75);
        PDF::SetAuthor('BA - DATAKOM SARPRAS TI');
        PDF::SetTitle('BERITA ACARA SERAH TERIMA - ' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2);
        PDF::SetSubject('BERITA ACARA - SARPRAS TI');
        PDF::SetMargins(17, 56, 9, 9);
        PDF::SetFontSubsetting(false);
        PDF::SetFontSize('9px');
        PDF::SetAutoPageBreak(TRUE, 0);

        // ------------------------------------------------------
        PDF::AddPage('P', 'F4');
        PDF::Image(public_path('image/kop_ba_2.png'), 12, 10, 190);
        //------------------------------------------------------- BUAT TULISAN BASTB
        PDF::writeHTML($out_1, true, false, true, false, '');

        //-------------------------------------------------------
        // PDF::Ln(10);
        // PDF::writeHTML($out_2, true, false, true, false, '');
        if (empty($dataBA->ttd_p1)) {
            "";
        } else {
            $TTD_1 = base64_decode($dataBA->ttd_p1);
            PDF::Image('@' . $TTD_1, 140, '', 30);
        }

        if (empty($dataBA->ttd_p2)) {
            "";
        } else {
            $TTD_2 = base64_decode($dataBA->ttd_p2);
            PDF::Image('@' . $TTD_2, 42, '', 30);
        }

        PDF::Ln(25);
        PDF::writeHTML($out_2, true, false, true, false, '');

        if (empty($dataBA->ttd_kasatpel)) {
            "";
        } else {
            $TTD_3 = base64_decode($dataBA->ttd_kasatpel);
            PDF::Image('@' . $TTD_3, 90, '', 30);
        }

        PDF::Ln(25);
        PDF::writeHTML($out_3, true, false, true, false, '');

        PDF::Output('BA-' . $dataBA->tanggal_ba . '-' . $dataBA->nama_barang . '-' . $dataBA->nama_p2 . '.pdf', 'D');
        exit;

        // dd($dataBA);


    }
}