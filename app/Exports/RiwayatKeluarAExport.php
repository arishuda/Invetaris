<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;

class RiwayatKeluarAExport implements FromQuery, WithHeadings, WithMapping
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
        $query = \DB::table('historybarangkeluar')
            ->join('stokbarang', 'historybarangkeluar.id_barang', '=', 'stokbarang.id')
            ->leftjoin('users', 'historybarangkeluar.id_user', '=', 'users.id')
            ->select('stokbarang.nama_barang', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'historybarangkeluar.*', 'users.username as NAMA', 'historybarangkeluar.last')
            ->whereIn('historybarangkeluar.aktif', [0, 1])
            ->where('stokbarang.jenisbarang', 1)
            ->orderBy('historybarangkeluar.created_at', 'DESC');

        if ($this->start_date && $this->end_date) {
            $query->whereDate('historybarangkeluar.created_at', '>=', $this->start_date);
            $query->whereDate('historybarangkeluar.updated_at', '<=', $this->end_date);
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