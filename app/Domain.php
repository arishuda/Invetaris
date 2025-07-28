<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    use HasFactory;
    protected $table = 'domain';
    protected $fillable = ['nama','expired_date','registrar','email','password','company','remark'];
}
