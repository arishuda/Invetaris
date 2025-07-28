<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Support\Facades\DB;


class PeminjamanExport implements FromQuery, WithMapping, WithHeadings
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
        $query = DB::table('peminjaman')
            ->join('stokbarang', 'peminjaman.id_barang', '=', 'stokbarang.id')
            ->leftjoin('users', 'peminjaman.id_user', '=', 'users.id')
            ->select('stokbarang.nama_barang', 'peminjaman.jumlah_pinjam', 'peminjaman.tanggal_pinjam', 'peminjaman.id', 'peminjaman.id_barang', 'peminjaman.jumlah_kembali', 'peminjaman.tanggal_kembali', 'peminjaman.dipinjam', 'peminjaman.satuan', 'stokbarang.jenisbarang', 'stokbarang.tahun_anggaran', 'peminjaman.keperluan')
            ->orWhere('peminjaman.aktif', 1)
            ->orderBy('peminjaman.created_at', 'DESC');

        if ($this->start_date && $this->end_date) {
            $query->whereDate('peminjaman.created_at', '>=', $this->start_date);
            $query->whereDate('peminjaman.updated_at', '<=', $this->end_date);
        }

        return $query;
    }

    public function map($peminjaman) : array
    {
        $kembaliStatus = '';
        if ($peminjaman->jumlah_kembali == null) {
            $kembaliStatus = 'Belum Kembali';
        } elseif ($peminjaman->jumlah_pinjam != \DB::table('logkembali')->where('id_peminjaman', $peminjaman->id)->sum('jumlah_kembali')) {
            $kembaliStatus = 'Kembali Sebagian';
        } else {
            $kembaliStatus = 'Sudah Kembali';
        }
        return [
            $peminjaman->nama_barang,
            $peminjaman->tahun_anggaran,
            $peminjaman->jumlah_pinjam,
            $peminjaman->tanggal_pinjam,
            $peminjaman->dipinjam,
            $kembaliStatus,
            $peminjaman->keperluan
        ];
    }

    public function headings() : array
    {
        return [
            "Nama Barang",
            "Tahun ANggaran",
            "Jumlah Pinjam",
            "Tanggal Pinjam",
            "Dipinjam",
            "Status",
            "Keperluan"
        ];
    }
}