<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogStok extends Model
{
    protected $table = 'log_stokbarang';

    protected $fillable = ['id_stokbarang', 'id_barang', 'id_user', 'last_update', 'keterangan'];
}
