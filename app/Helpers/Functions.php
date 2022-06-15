<?php

namespace App\Helpers;

use App\Models\BukaDispensasi;
use Illuminate\Support\Facades\DB;

class Functions
{
  public static function dateFormatId($date)
  {
    if ($date == '' || $date == '0000-00-00') {
      return '-';
    }

    setlocale(LC_ALL, 'id_ID');

    $tgl_part = explode('-', $date);

    $date_id = strftime('%d %B %Y', mktime(0, 0, 0, $tgl_part[1], $tgl_part[2], $tgl_part[0]));

    return $date_id;
  }

  public static function seo_friendly_url($string)
  {
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/'), '-', $string);

    return strtolower(trim($string, '-'));
  }

  public static function str_to_int($string_int)
  {
    if ($string_int != null or $string_int != '') {
      $string_int = str_replace('.', '', $string_int);
      $string_int = str_replace(',', '.', $string_int);

      return $string_int;
    }

    return 0;
  }

  public static function pengajuan($semester)
  {
    $pengajuan = DB::table('tb_pengajuan_dispensasi')
    ->where('kode_prodi','like',trim(session('user_unit')).'%')
    ->where('semester',trim($semester))
    ->get();

    return $pengajuan;
  }
}
