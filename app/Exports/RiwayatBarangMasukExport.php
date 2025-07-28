<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection\whereDate;

class RiwayatBarangMasukExport implements FromQuery, WithMapping, WithHeadings
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
        $query = DB::table('barangmasuk')
            ->join('stokbarang', 'barangmasuk.id_barang', '=', 'stokbarang.id')
            ->leftjoin('users', 'barangmasuk.id_user', '=', 'users.id')
            ->select('stokbarang.nama_barang', 'barangmasuk.id AS IDBM', 'barangmasuk.jumlah_masuk', 'barangmasuk.stat_barangmasuk AS STATUS', 'barangmasuk.tanggal_masuk', 'barangmasuk.last AS last_update', 'barangmasuk.aktif', 'users.name as NAMA', 'stokbarang.kib', 'barangmasuk.satuan', 'barangmasuk.last')
            ->where('barangmasuk.aktif', 1)
            ->orderBy('barangmasuk.created_at', 'DESC');



        if ($this->start_date && $this->end_date) {
            $query->whereDate('barangmasuk.created_at', '>=', $this->start_date);
            $query->whereDate('barangmasuk.updated_at', '>=', $this->end_date);

        }


        return $query;
    }

    public function headings() : array
    {
        return [
            "Nama Barang",
            "Jumlah Masuk",
            "Tanggal Masuk",
            "User Log Input",
            "Last Update"
        ];
    }

    public function map($HBarangmasuk) : array
    {
        return [
            $HBarangmasuk->nama_barang,
            $HBarangmasuk->jumlah_masuk,
            $HBarangmasuk->tanggal_masuk,
            $HBarangmasuk->NAMA,
            $HBarangmasuk->last_update
        ];
    }


}