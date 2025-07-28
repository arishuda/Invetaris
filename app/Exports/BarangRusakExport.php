<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;


class BarangRusakExport implements FromQuery, WithMapping, WithHeadings
{
    use Exportable;

    protected $start_date;
    protected $end_date;

    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }
    public function query()
    {
        $query = DB::table('barangrusak')
            ->join('stokbarang', 'barangrusak.id_barang', '=', 'stokbarang.id')
            ->join('users', 'barangrusak.id_user', '=', 'users.id')
            ->select(
                'stokbarang.nama_barang',
                'stokbarang.jenisbarang',
                'stokbarang.tahun_anggaran',
                'barangrusak.*',
                'users.username as NAMA'
            )
            ->where('stokbarang.jenisbarang', 1)
            ->orderBy('barangrusak.created_at', 'DESC');




        if ($this->start_date && $this->end_date) {
            $query->whereDate('barangrusak.created_at', '>=', $this->start_date);
            $query->whereDate('barangrusak.updated_at', '<=', $this->end_date);
        }

        return $query;
    }

    public function map($rusak) : array
    {
        return [
            $rusak->nama_barang,
            $rusak->created_at,
            $rusak->jumlah_rusak,
            $rusak->sn,
            $rusak->status,
            $rusak->NAMA
        ];
    }

    public function headings() : array
    {
        return [
            "nama barang",
            "tanggal rusak",
            "jumlah rusak",
            "serial number",
            "status",
            "user log input"
        ];
    }
}