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


class StokBarangExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $Bquery = StokBarang::where('aktif', 1)->where('jenisbarang', 2)->orWhere('jenisbarang', 3)->orderBy('created_at', 'DESC')->get();

        return $Bquery;
    }

    public function map($barangkeluar): array
    {
        return [
            $barangkeluar->kib,
            $barangkeluar->nama_barang,
            $barangkeluar->tahun_anggaran,
            \DB::table('historybarangkeluar')->where('id_barang', $barangkeluar->id)->where('aktif', 1)->sum('jumlah_keluar') ?? '0',
            $barangkeluar->jumlah_sekarang ?? '0',
            $barangkeluar->jumlah_awal ?? '0',
            $barangkeluar->lokasi
        ];
    }
    
    public function headings(): array
    {
        return [
            "kib",
            "Nama Barang",
            "Tahun Anggaran",
            "Barang Keluar",
            "Stok Sekarang",
            "Stok Awal",
            "Lokasi"
        ];
    }
}
