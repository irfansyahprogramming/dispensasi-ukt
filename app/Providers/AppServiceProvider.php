<?php

namespace App\Providers;

use App\Models\BukaDispensasi;
use App\Models\PengajuanDispensasiUKTModel;
use App\Models\ViewPengajuanData;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale'    => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');

        View::composer(['layouts.infobox','layouts.menu'], function ($view) {
            $count = 0;
            
            $periode = BukaDispensasi::where('aktif', '1')->first();
            $semester = $periode->semester;
            if ($semester == ""){
                $param = "";
            }else{
                $param = array('semester'=>$semester);
            }
            $cmode = session('user_cmode');
            $unit = session('user_unit');
            if ($cmode == "2"){
                $dispensasi = ViewPengajuanData::where('kode_prodi','=',trim($unit))->where($param)->get();    
            }elseif ($cmode == "3" || ($cmode == "14")){
                $dispensasi = ViewPengajuanData::where('kode_prodi','like',trim($unit).'%')->where($param)->get();    
            }else{
                $dispensasi = ViewPengajuanData::where($param)->get();
            }

            $count_total_pengajuan = $dispensasi->where('mode','2')->count('id');
            $count_total_verifikasi_fakultas = $dispensasi->where('mode','3')->count('id');
            $count_total_verifikasi_dekan = $dispensasi->where('mode','14')->count('id');
            $count_total_verifikasi_wr2 = $dispensasi->where('mode','20')->count('id');
            $count_total_verifikasi_hutalak = $dispensasi->where('mode','22')->count('id');
            $count_total_verifikasi_bakhum = $dispensasi->where('mode','4')->count('id');
            $count_total_verifikasi_upttik = $dispensasi->where('mode','13')->count('id');
            
            $count_total_setuju_dekan = $dispensasi->where('mode','14')->where('status_ajuan','1')->count('id');
            $count_total_setuju_wr2 = $dispensasi->where('mode','20')->where('status_ajuan','1')->count('id');

            $total_jenis1 = $dispensasi->where('mode',14)->where('id_jenis_dispensasi', 1)->where('status_ajuan','1')->count();
            $total_jenis4 = $dispensasi->where('mode',14)->where('id_jenis_dispensasi', 4)->where('status_ajuan','1')->count();
            $total_jenis5 = $dispensasi->where('mode',14)->where('id_jenis_dispensasi', 5)->where('status_ajuan','1')->count();
            $total_jenis7 = $dispensasi->where('mode',14)->where('id_jenis_dispensasi', 7)->where('status_ajuan','1')->count();

            if ($cmode == '3'){
                $badges = $count_total_pengajuan - $count_total_verifikasi_fakultas;
            }elseif ($cmode == '14'){
                $badges = $count_total_verifikasi_fakultas - $count_total_verifikasi_dekan;
            }elseif ($cmode == '20'){
                $badges = $count_total_verifikasi_dekan - $count_total_verifikasi_wr2;
            }elseif ($cmode == '22'){
                $badges = $count_total_verifikasi_wr2 - $count_total_verifikasi_hutalak;
            }elseif ($cmode == '4'){
                $badges = $count_total_verifikasi_hutalak - $count_total_verifikasi_bakhum;
            }elseif ($cmode == '13'){
                $badges = $count_total_verifikasi_wr2 - $count_total_verifikasi_upttik;
            }else{
                $badges = 0;
            }
            $count_all = array (
                'total_ajuan'       => $count_total_pengajuan,
                'total_fakultas'    => $count_total_verifikasi_fakultas,
                'total_dekan'       => $count_total_setuju_dekan,
                'total_wr2'         => $count_total_setuju_wr2,
                'total_hutalak'     => $count_total_verifikasi_hutalak,
                'total_bakhum'      => $count_total_verifikasi_bakhum,
                'total_upttik'      => $count_total_verifikasi_upttik,
                'semester'          => $semester,
                'notif_sisa'        => $badges,
                'total_50'          => $total_jenis1,
                'total_cicilan'     => $total_jenis4,
                'total_tangguh'     => $total_jenis5,
                'total_turun'       => $total_jenis7
            );
            // @dd($count_all);
            
            return $view->with($count_all); 
        });
    }
}
