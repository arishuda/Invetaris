<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarangRusak extends Model
{
    protected $table = 'barangrusak';

    protected $fillable = [
        'id_barang',
        'jumlah_rusak',
        'satuan',
        'id_user',
        'sn',
        'filename',
        'status',
        'sn',
        'aktif',
        'wilayah',
        'id_barangmasuk',
        'created_at'
    ];
    public function barangMasuk()
                            {
                                return $this->belongsTo(BarangMasuk::class);
                            }
}