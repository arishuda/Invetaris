<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    protected $table = 'log_easet';

    protected $fillable = ['log', 'id_barang','id_user', 'desc', 'last'];

}
