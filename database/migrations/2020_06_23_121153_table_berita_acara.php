<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TableBeritaAcara extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('beritaacara', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_barangkeluar');
            $table->bigInteger('id_barang');
            $table->string('hari', 10)->nullable();
            $table->string('tanggal')->nullable();
            $table->string('bulan')->nullable();
            $table->string('tahun')->nullable();
            $table->date('tanggal_ba')->nullable();

            $table->string('nama_p1')->nullable();
            $table->string('nipnrk_p1')->nullable();
            $table->text('jabatan_p1')->nullable();
            $table->text('ttd_p1')->nullable();

            $table->string('nama_p2')->nullable();
            $table->string('nipnrk_p2')->nullable();
            $table->text('lokasikerja')->nullable();
            $table->text('jabatan_p2')->nullable();
            $table->text('ttd_p2')->nullable();

            $table->text('wilayah')->nullable();
            $table->text('serialnumber')->nullable();
            $table->integer('jumlah')->nullable();
            $table->text('ket1')->nullable();
            $table->text('ket2')->nullable();
            $table->text('ket3')->nullable();
            $table->text('ttd_kasatpel');
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
        Schema::dropIfExists('beritaacara');
    }
}
