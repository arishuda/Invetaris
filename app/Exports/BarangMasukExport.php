<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;


use App\StokBarang;
use App\BarangKeluar;
use App\BeritaAcara;
use App\LogBarangKeluar;
use App\HistoryBarangKeluar;
use App\Logging;
use App\Peminjaman;


class BarangMasukExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = \DB::table('barangmasuk')
                ->join('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
                ->leftjoin('users', 'barangmasuk.id_user', '=', 'users.id') 
                // ->leftjoin('log_easet', 'barangmasuk.id', '=', 'log_barangmasuk.id_barangmasuk')
                ->select('stokbarang.nama_barang', 'barangmasuk.id AS IDBM', 'barangmasuk.jumlah_masuk','barangmasuk.stat_barangmasuk AS STATUS', 'barangmasuk.tanggal_masuk','barangmasuk.last AS last_update', 'barangmasuk.aktif', 'users.username as NAMA')
                ->where('barangmasuk.aktif', 1)
                ->orderBy('barangmasuk.created_at', 'DESC')
                ->get();
        return $query;
    }

    public function map($barangkeluar): array
    {
        return [
            $barangkeluar->nama_barang,
            $barangkeluar->jumlah_masuk,
            $barangkeluar->tanggal_masuk,
            $barangkeluar->NAMA,
            $barangkeluar->last_update
        ];
    }

    public function headings(): array
    {
        return [
            "Nama Barang",
            "Jumlah Masuk",
            "Tanggal Masuk",
            "User Log",
            "Last Update"
        ];
    }
}
