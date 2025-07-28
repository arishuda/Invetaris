<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;

// use PhpOffice\PhpSpreadsheet\Shared\Date;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


use App\StokBarang;
use App\BarangKeluar;
use App\BeritaAcara;
use App\LogBarangKeluar;
use App\HistoryBarangKeluar;
use App\Logging;
use App\Peminjaman;


class BarangKeluarExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = \DB::table('barangkeluar')
            ->join('stokbarang', 'barangkeluar.id_barang', '=', 'stokbarang.id')
            ->join('beritaacara', 'barangkeluar.id', '=', 'beritaacara.id_barangkeluar')
            ->join('peminjaman', 'barangkeluar.id', '=', 'peminjaman.id_barangkeluar')
            ->leftjoin('beritaacara_upload', 'barangkeluar.id', '=', 'beritaacara_upload.id_barangkeluar')
            ->leftjoin('users', 'barangkeluar.id_user', '=', 'users.id') 
            ->select('stokbarang.nama_barang','stokbarang.jenisbarang','stokbarang.tahun_anggaran', 
                    'barangkeluar.*', 'beritaacara.id AS BA_ID', 'users.username as NAMA', 
                    'beritaacara_upload.filename AS NAMAFILE', 
                    'peminjaman.id AS IDPEMINJAMAN', 'peminjaman.jumlah_kembali', 'peminjaman.jumlah_pinjam')
            ->orWhere('barangkeluar.aktif', 1)
            ->where('stokbarang.jenisbarang', 1)
            ->orderBy('barangkeluar.created_at', 'DESC')
            ->get();
        return $query;

        
    }

    public function map($barangkeluar): array
    {
        return [
            $barangkeluar->nama_barang,
            $barangkeluar->tahun_anggaran,
            $barangkeluar->jumlah_keluar,
            $barangkeluar->tanggal_keluar,
            $barangkeluar->diambil,
            $barangkeluar->keperluan
        ];
    }

    public function headings(): array
    {
        return [
            "Nama Barang",
            "Tahun Anggaran",
            "Jumlah Keluar",
            "Tanggal Keluar",
            "Diambil",
            "Keperluan"
        ];
    }
}
