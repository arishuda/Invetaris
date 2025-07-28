<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeritaAcaraUpload extends Model
{
    protected $table = 'beritaacara_upload';

    protected $fillable = ['id_barangkeluar', 
                            'id_barang',
                            'id_peminjaman',
                            'filename'];
}
