<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableLogKeluar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('logkembali', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_barang');
            $table->integer('jumlah_pinjam');
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
        //
        Schema::dropIfExists('logkembali');
    }
}
