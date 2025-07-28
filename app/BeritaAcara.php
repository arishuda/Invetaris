<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeritaAcara extends Model
{
    //
    protected $table = 'beritaacara';

    protected $fillable = ['id_barangkeluar', 
                            'id_barang',
                            'id_peminjaman', 
                            'nomor',
                            'hari',
                            'tanggal',
                            'bulan',
                            'tahun',
                            'tanggal_ba',
                            'nama_p1',
                            'nip_p1',
                            'nrk_p1',
                            'jabatan_p1',
                            'ttd_p1',
                            'nama_p2',
                            'ket1',
                            'ket2',
                            'ket3',
                            'statuskerja_p2',
                            'nip_p2',
                            'nrk_p2',
                            'nik_p2',
                            'lokasikerja',
                            'jabatan_p2',
                            'ttd_p2',
                            'wilayah',
                            'serialnumber',
                            'jumlah',
                            'ttd_kasatpel',
                            'tgl_kembali'];
}
