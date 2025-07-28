<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TablePeminjaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Peminjaman', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_barang');
            $table->integer('jumlah_pinjam');
            $table->string('dipinjam');
            $table->date('tanggal_pinjam');
            $table->text('keperluan');
            $table->integer('jumlah_kembali');
            $table->date('tanggal_kembali');
            $table->text('ttd');
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
        Schema::dropIfExists('Peminjaman');
    }
}
