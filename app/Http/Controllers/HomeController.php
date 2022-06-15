<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use App\Models\PengajuanDispensasiUKTModel;
use Illuminate\Http\Request;
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
        
        $periode = BukaDispensasi::where('aktif','1')->first();
        if ($periode){
            $tombol = "";
            $semester = $periode->semester;
        }else{
            $tombol = "disabled";
            $semester = "";
        }

        $badges = Functions::pengajuan($semester);

        if ($cmode == '20'){
            // $pengajuan = collect(PengajuanDispensasiUKTModel::where('semester',$semester)->get());
            $pengajuan = DB::table('v_list_pengajuan')->where('semester',$semester)->get();
        }else{
            $pengajuan = DB::table('v_list_pengajuan')->where('kode_prodi', 'like', trim(session('user_unit')) .'%')->where('semester',$semester)->get();
            // $pengajuan = PengajuanDispensasiUKTModel::where('kode_prodi', 'like', trim(session('user_unit')) . '%')->where('semester',$semester)->get();
            
        }
        // return $pengajuan;
        $arrData = [
            'title'         => 'Home',
            'active'        => 'home',
            'user'          => $user,
            'mode'          => $mode,
            'cmode'          => $cmode,
            'subtitle'      => 'Dashboard',
            'home_active'   => 'active',
            'periode_active' => '',
            'dispen_active' => '',
            'penerima_active'   => '',
            'laporan_active' => '',
            'pengajuan' => $pengajuan,
            'badges' => $badges
            //'posts' => Post::latest()->get()
        ];

        return view('home', $arrData);
    }
}
