<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromArray;

class BarangMasukTemplateExport implements WithHeadings, FromArray
{
    /**
     * Define the headers for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Nama Barang',        // Column A
            'Serial Number',      // Column C
            'Satuan',             // Column D
            'Lokasi',             // Column E
            'Asal Barang',        // Column G
            'KIB',                // Column H
            'Tahun Anggaran',     // Column I
            'Jenis Barang',         // Column J
        ];
    }

    /**
     * Example data rows for illustration.
     */
    public function array(): array
    {
        return [
            ['Contoh Barang', 'SN12345', 'Pcs', 'Gudang A', 'Pengadaan', 'KIB A', 2024,'Aset / Barang Habis Pakai / Barang Pinjaman / Lainnya'],
        ];
    }
}
