<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableBarangkeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('BarangKeluar', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_barang');
            $table->integer('jumlah_keluar');
            $table->string('diambil');
            $table->date('tanggal_keluar');
            $table->text('keperluan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('BarangKeluar');
    }
}
