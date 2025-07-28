<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    //
    protected $table = 'barangkeluar';

    protected $fillable = ['id_barang', 'jumlah_keluar', 
                            'satuan', 'diambil', 'tanggal_keluar', 
                            'keperluan', 'buatba', 'id_user', 'aktif', 'last'];
                            public function barangMasuk()
                            {
                                return $this->belongsTo(BarangMasuk::class);
                            }
}
