<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    //
    protected $table = 'barangmasuk';
    
    protected $fillable = ['id_barang','stat_barangmasuk', 'jumlah_masuk', 'satuan', 'tanggal_masuk', 'id_user', 'aktif', 'last','filename','kib','serial_number','id_barangkeluar','id_qr','id_peminjaman','id_barangrusak'];

    
    public function stok()
    {
        return $this->belongsTo(StokBarang::class);
        
    }
    
    

}
