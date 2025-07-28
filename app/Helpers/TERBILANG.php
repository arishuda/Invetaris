<?php

class TERBILANG {
    public static function TBLG($nilai)
    {
        $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");

        if ($nilai == 0) {
            return "";
        } elseif ($nilai < 12 && $nilai != 0) {
            return isset($huruf[$nilai]) ? $huruf[$nilai] : "";
        } elseif ($nilai < 20) {
            return self::TBLG($nilai - 10) . " Belas ";
        } elseif ($nilai < 100) {
            return self::TBLG((int)($nilai / 10)) . " Puluh " . self::TBLG($nilai % 10);
        } elseif ($nilai < 200) {
            return " Seratus " . self::TBLG($nilai - 100);
        } elseif ($nilai < 1000) {
            return self::TBLG((int)($nilai / 100)) . " Ratus " . self::TBLG($nilai % 100);
        } elseif ($nilai < 2000) {
            return " Seribu " . self::TBLG($nilai - 1000);
        } elseif ($nilai < 1000000) {
            return self::TBLG((int)($nilai / 1000)) . " Ribu " . self::TBLG($nilai % 1000);
        } elseif ($nilai < 1000000000) {
            return self::TBLG((int)($nilai / 1000000)) . " Juta " . self::TBLG($nilai % 1000000);
        } elseif ($nilai < 1000000000000) {
            return self::TBLG((int)($nilai / 1000000000)) . " Milyar " . self::TBLG($nilai % 1000000000);
        } elseif ($nilai < 100000000000000) {
            return self::TBLG((int)($nilai / 1000000000000)) . " Trilyun " . self::TBLG($nilai % 1000000000000);
        } else {
            return "Maaf Tidak Dapat di Proses Karena Jumlah nilai Terlalu Besar ";
        }
    }
}
