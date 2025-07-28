<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatKeluarBExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    protected $start_date_1;
    protected $end_date_1;

    public function __construct($start_date_1 = null, $end_date_1 = null)
    {
        $this->start_date_1 = $start_date_1;
        $this->end_date_1 = $end_date_1;
    }

    public function query()
    {
        $query = \DB::table('historybarangkeluar')
            ->join('stokbarang', 'historybarangkeluar.id_barang', '=', 'stokbarang.id')
            ->leftjoin('users', 'historybarangkeluar.id_user', '=', 'users.id')
            ->select('stokbarang.nama_barang', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'historybarangkeluar.*', 'users.username as NAMA', 'historybarangkeluar.last')
            ->where(function ($query) {
                $query->where('stokbarang.jenisbarang', 2)
                    ->orWhere('stokbarang.jenisbarang', 3);
            })
            ->orderBy('historybarangkeluar.created_at', 'DESC');

        if ($this->start_date_1 && $this->end_date_1) {
            $query->whereDate('historybarangkeluar.created_at', '>=', $this->start_date_1);
            $query->whereDate('historybarangkeluar.updated_at', '<=', $this->end_date_1);

        }

        return $query;
    }

    public function headings() : array
    {
        return [
            "NAMA BARANG",
            "TAHUN ANGGARAN",
            "JUMLAH KELUAR",
            "USER LOG INPUT",
            "TANGGAL KELUAR",
            "DIAMBIL",
            "KEPERLUAN",
            "LAST UPDATE"
        ];
    }


    public function map($Hbarangkeluar) : array
    {
        return [
            $Hbarangkeluar->nama_barang,
            $Hbarangkeluar->tahun_anggaran,
            $Hbarangkeluar->jumlah_keluar,
            $Hbarangkeluar->NAMA,
            $Hbarangkeluar->tanggal_keluar,
            $Hbarangkeluar->diambil,
            $Hbarangkeluar->keperluan,
            $Hbarangkeluar->last
        ];
    }
}