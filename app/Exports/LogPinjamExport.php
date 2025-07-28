<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;


class LogPinjamExport implements FromQuery, WithMapping, WithHeadings
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
        $query = DB::table('logkembali')
            ->join('stokbarang', 'logkembali.id_barang', '=', 'stokbarang.id')
            ->select('stokbarang.nama_barang', 'logkembali.*')
            ->orderBy('logkembali.created_at', 'DESC');

        if ($this->start_date_1 && $this->end_date_1) {
            $query->whereDate('logkembali.created_at', '>=', $this->start_date_1);
            $query->whereDate('logkembali.updated_at', '<=', $this->end_date_1);
        }

        return $query;
    }

    public function map($rusak) : array
    {
        return [
            $rusak->nama_barang,
            $rusak->jumlah_kembali,
            $rusak->tanggal_kembali,
        ];
    }

    public function headings() : array
    {
        return [
            "Nama Barang",
            "Jumlah Kembali",
            "Tanggal Kembali",
        ];
    }
}