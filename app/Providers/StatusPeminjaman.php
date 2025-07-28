<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class StatusPeminjaman extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('kembaliStatus', function ($data) {
            return "<?php
        if ({$data}->jumlah_kembali === NULL) {
            echo 'Belum Kembali';
        } elseif ({$data}->jumlah_pinjam !== \\DB::table('logkembali')->where('id_peminjaman', {$data}->id)->sum('jumlah_kembali')) {
            echo 'Kembali Sebagian';
        } else {
            echo 'Sudah Kembali';
        }
        ?>";
        });
    }
}