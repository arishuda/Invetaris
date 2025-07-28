<?php
namespace App\Helpers;
use Carbon\Carbon;
class Tanggal
{



  function Indo1($tgl)
  {
      $tgl = new Carbon($tgl);
      setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');
      return $tgl->formatLocalized('%d %B %Y');
  }

  public static function Hari($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    if($dt->formatLocalized('%A')=="Monday"){
      return "Senin";
    } elseif($dt->formatLocalized('%A')=="Tuesday"){
      return "Selasa";
    } elseif($dt->formatLocalized('%A')=="Wednesday"){
      return "Rabu";
    } elseif($dt->formatLocalized('%A')=="Thursday"){
      return "Kamis";
    } elseif($dt->formatLocalized('%A')=="Friday"){
      return "Jumat";
    } elseif($dt->formatLocalized('%A')=="Saturday"){
      return "Sabtu";
    } elseif($dt->formatLocalized('%A')=="Sunday"){
      return "Minggu";
    } else {
      return $dt->formatLocalized('%A');
    }
  }

  public static function TGL($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    return $dt->formatLocalized('%e'); // Senin, 3 September 2018 ( 3 Saja )
  }

  public static function Bulan($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    // return $dt->formatLocalized('%B'); // Senin, 3 September 2018
    if($dt->formatLocalized('%B')=="January"){
      return "Januari";
    } elseif($dt->formatLocalized('%B')=="February" || $dt->formatLocalized('%B')=="Pebruari"){
      return "Februari";
    } elseif($dt->formatLocalized('%B')=="March"){
      return "Maret";
    } elseif($dt->formatLocalized('%B')=="April"){
      return "April";
    } elseif($dt->formatLocalized('%B')=="May"){
      return "Mei";
    } elseif($dt->formatLocalized('%B')=="June"){
      return "Juni";
    } elseif($dt->formatLocalized('%B')=="July"){
      return "Juli";
    } elseif($dt->formatLocalized('%B')=="August"){
      return "Agustus";
    } elseif($dt->formatLocalized('%B')=="September"){
      return "September";
    } elseif($dt->formatLocalized('%B')=="October"){
      return "Oktober";
    } elseif($dt->formatLocalized('%B')=="November"){
      return "November";
    } elseif($dt->formatLocalized('%B')=="December"){
      return "Desember";
    } else {
      return $dt->formatLocalized('%B');
    } 
  }

  public static function Tahun($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    return $dt->formatLocalized('%Y'); // Senin, 3 September 2018
  }

  public static function Tanggal($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    return $dt->formatLocalized('%e %B %Y'); // Senin, 3 September 2018
  }

  public static function Indo2($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    return $dt->formatLocalized('%A, %e %B %Y'); // Senin, 3 September 2018
  }


  public static function Indo3($tgl) {
    $dt = new  Carbon($tgl);
    setlocale(LC_ALL, 'id_ID.UTF8', 'id_ID.UTF-8', 'id_ID.8859-1', 'id_ID', 'en_US.UTF8', 'en_US.UTF-8', 'en_US');

    return $dt->formatLocalized('%A, %e %B %Y %H:%M:%S'); // Senin, 3 September 2018 00:00:00
  }


  

//-------------------------------------------------------------------------------------------------------
}
