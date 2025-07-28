<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    //
    protected $table = 'peminjaman';

    protected $fillable = ['id_barangkeluar', 'id_barang', 'jumlah_pinjam', 'satuan', 'dipinjam', 'tanggal_pinjam', 'keperluan', 
                            'jumlah_kembali', 'tanggal_kembali', 'id_user', 'aktif', 'last','buatba','nomor',
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
                            'serialnumber',
                            'jumlah',
                            'ttd_kasatpel',
                            'tgl_kembali'];
                            public function barangMasuk()
                            {
                                return $this->belongsTo(BarangMasuk::class);
                            }
}
