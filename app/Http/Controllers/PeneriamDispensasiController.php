<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PeneriamDispensasiController extends Controller
{
    public function index()
    {
        
        if(!Session::has('isLoggedIn')){
            return redirect()->to('login');
        }
        $user = session ('user_name');
        $mode = session ('user_mode');
        $periode = BukaDispensasi::where('aktif','1')->first();
        
        if ($periode){
            $tombol = "";
            $semester = $periode->semester;
        }else{
            $tombol = "disabled";
            $semester = "";
        }

        $badges = Functions::pengajuan($semester);
        
        if (session ('user_cmode') != '4' && session ('user_cmode') != '11' && session ('user_cmode') != '13' && session ('user_cmode') != '20'){
            $pengajuan = DB::table('tb_pengajuan_dispensasi')
            ->where('kode_prodi','like',trim(session('user_unit')).'%')
            ->where('semester',trim($semester))
            ->where('status_pengajuan','>=','3')
            ->where('status_pengajuan','<=','6')
            ->get();
            $unit = trim(session('user_unit'));
        }else{
            $pengajuan = DB::table('tb_pengajuan_dispensasi')
            ->where('semester',trim($semester))
            ->where('status_pengajuan','>=','3')
            ->where('status_pengajuan','<=','6')
            ->get();
            $unit = "All";
        }
        // return $pengajuan;

        foreach($pengajuan as $ajuan){
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt,0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        $arrData = [
            'title'             => 'Dispensasi',
            'active'            => 'Penerima Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'unit'              => $unit,
            'subtitle'          => 'Penerima Dispensasi',
            'home_active'       => '',
            'dispen_active'     => '',
            'laporan_active'    => '',
            'periode_active'    => '',
            'penerima_active'   => 'active',
            'user'              => session('user_username'),
            'semester'          => $semester,
            'pengajuan'         => $pengajuan,
            'badges'            => $badges
        ];
        // return $arrData;
        return view('penerimaDispensasi.index',$arrData);
    }
}
