<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogKembali extends Model
{
    //
    protected $table = 'logkembali';

    protected $fillable = ['id_peminjaman','id_barang','jumlah_kembali', 'nama_pengembali', 'tanggal_kembali', 'ttd','buatba','serialnumber'];
}
