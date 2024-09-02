<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {

        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }

        $user = session('user_name');
        $mode = session('user_mode');
        $cmode = session('user_cmode');
        
        // $periode = BukaDispensasi::where('aktif','1')->first();
        // if ($periode){
        //     $tombol = "";
        //     $semester = $periode->semester;
        // }else{
        //     $tombol = "disabled";
        //     $semester = "";
        // }
        $semester = "";
        $badges = Functions::pengajuan($semester);
        // @dd($badges);
        // $pengajuan = DB::table('tb_pengajuan_dispensasi')->where('semester',$semester)->get();
        // if ($semester == ''){
        //     if ($cmode == '2' || $cmode == '3'){
        //         // $pengajuan = collect(PengajuanDispensasiUKTModel::where('semester',$semester)->get());
        //         $pengajuan = DB::table('tb_pengajuan_dispensasi')->leftJoin('tr_history_pengajuan','tr_history_pengajuan.id_pengajuan','tb_pengajuan_dispensasi.id')->where('tb_pengajuan_dispensasi.kode_prodi', 'like', trim(session('user_unit')) .'%')->get();
        //     }else{
        //         $pengajuan = DB::table('tb_pengajuan_dispensasi')->leftJoin('tr_history_pengajuan','tr_history_pengajuan.id_pengajuan','tb_pengajuan_dispensasi.id')->get();
        //         // $pengajuan = PengajuanDispensasiUKTModel::where('kode_prodi', 'like', trim(session('user_unit')) . '%')->where('semester',$semester)->get();
                
        //     }
        // }else{
        //     if ($cmode == '2' || $cmode == '3'){
        //         // $pengajuan = collect(PengajuanDispensasiUKTModel::where('semester',$semester)->get());
        //         $pengajuan = DB::table('tb_pengajuan_dispensasi')->leftJoin('tr_history_pengajuan','tr_history_pengajuan.id_pengajuan','tb_pengajuan_dispensasi.id')->where('tb_pengajuan_dispensasi.kode_prodi', 'like', trim(session('user_unit')) .'%')->where('tb_pengajuan_dispensasi.semester',$semester)->get();
        //     }else{
        //         $pengajuan = DB::table('tb_pengajuan_dispensasi')->leftJoin('tr_history_pengajuan','tr_history_pengajuan.id_pengajuan','tb_pengajuan_dispensasi.id')->where('tb_pengajuan_dispensasi.semester',$semester)->get();
        //         // $pengajuan = PengajuanDispensasiUKTModel::where('kode_prodi', 'like', trim(session('user_unit')) . '%')->where('semester',$semester)->get();
                
        //     }
        // }
        
        // @dd($pengajuan);
        $arrData = [
            'title'         => 'Home',
            'active'        => 'home',
            'user'          => $user,
            'mode'          => $mode,
            'cmode'          => $cmode,
            'subtitle'      => 'Dashboard',
            'home_active'   => 'active',
            'periode_active' => '',
            'dataukt_active'    => '',
            'dispen_active' => '',
            'penerima_active'   => '',
            'laporan_active' => '',
            'penerbitan_active' => '',
            // 'pengajuan' => $pengajuan,
            'badges' => $badges
            //'posts' => Post::latest()->get()
        ];

        return view('home', $arrData);
    }
}
