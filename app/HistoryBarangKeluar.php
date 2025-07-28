<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HistoryBarangKeluar extends Model
{
    protected $table = 'historybarangkeluar';

    protected $fillable = ['id_barangkeluar','id_barang', 'jumlah_keluar', 
                            'satuan', 'diambil', 'tanggal_keluar', 
                            'keperluan', 'id_user', 'aktif', 'last'];
}
