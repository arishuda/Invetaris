<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StokBarang extends Model
{
    //
    protected $table = 'stokbarang';

    protected $fillable = ['nama_barang', 'jumlah_sekarang', 'jumlah_awal', 'stokupdate',
                            'satuan', 'jenisbarang', 'lokasi', 'tahun_anggaran', 'aktif', 'last', 'image','kib','id_qrs','asal_brg'];

    public function masuk()
    {
        return $this->hasMany(BarangMasuk::class);
    }

}
