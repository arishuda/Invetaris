<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Regions extends Model
{
    protected $table = 'regions';
    protected $fillable = ['level','code','city','name','latitude','longitude','detail','created_at','updated_at'];
// use HasFactory;
public $incrementing = false; // UUIDs are not auto-incrementing
protected $keyType = 'string'; // UUIDs are strings
}
