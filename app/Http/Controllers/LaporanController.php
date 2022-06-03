<?php

namespace App\Http\Controllers;

use App\Models\BukaDispensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    public function index()
    {
        if(!Session::has('isLoggedIn')){
            return redirect()->to('login');
        }
                
        $periode = BukaDispensasi::checkOpenPeriode();
        
        $user = session ('user_name');
        $mode = session ('user_mode');
        $unit = session ('user_unit');
        
        $periode = BukaDispensasi::where('aktif','1')->first();
        if ($periode){
            $tombol = "";
            $semester = $periode->semester;
        }else{
            $tombol = "disabled";
            $semester = "";
        }

        $pengajuan = DB::table('tb_pengajuan_dispensasi')
        ->where('kode_prodi','like',trim(session('user_unit')).'%')
        ->where('semester',trim($semester))
        ->get();

        $listSemester = DB::table('ref_periode')->get();
        $listJenis = DB::table('ref_jenisdipensasi')->get();
        $listStatus = DB::table('ref_status_pengajuan')->get();

        // get mengajar from siakad
        $url = "http://103.8.12.212:36880/siakad_api/api/as400/programStudi/". trim(session ('user_unit'));
        //echo $url;
        $response = Http::get($url);
        $listProdi = json_decode($response);
        //var_dump($listProdi);

        foreach($pengajuan as $ajuan){
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt,0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        $arrData = [
            'title'             => 'Laporan',
            'active'            => 'Laporan Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Laporan',
            'home_active'       => '',
            'penerima_active'   => '',
            'dispen_active'     => '',
            'laporan_active'    => 'active',
            'nim'               => session('user_username'),
            'semester'          => $semester,
            'listSemester'      => $listSemester,
            'listProdi'         => $listProdi,
            'listJenis'         => $listJenis,
            'listStatus'         => $listStatus,
            'tombol'            => $tombol,
            'pengajuan'         => $pengajuan
        ];

        return view('laporan.index',$arrData);
        //return view('pengajuan_dispensasi',compact('list_dispensasi','kel_ukt','pengajuan','subtitle'));
    }
}
