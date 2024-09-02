<?php

namespace App\Http\Controllers;

use App\Models\BukaDispensasi;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TerbitkanSKController extends Controller
{
    public function index()
    {
        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }
        $user = session('user_name');
        $mode = session('user_mode');
        $unit = session('user_unit');
        $periode = BukaDispensasi::where('aktif', '1')->first();
        $now = new DateTime("now");
        if ($periode) {
            $semester = $periode->semester;
        } else {
            $semester = "All";
        }

        $pengajuan = DB::table('tb_pengajuan_dispensasi')
            ->where('semester', trim($semester))
            ->where('status_pengajuan', '=', '3')
            ->orderBy('id','desc')
            ->get();

        foreach ($pengajuan as $ajuan) {
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        $arrData = [
            'title'             => 'Home',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'unit'              => $unit,
            'subtitle'          => 'Penerbitan SK',
            'home_active'       => '',
            'dispen_active'     => '',
            'dataukt_active'    => '',
            'laporan_active'    => '',
            'periode_active'    => '',
            'penerbitan_active' => 'active',
            'penerima_active'   => '',
            'users'             => session('user_username'),
            'semester'          => $semester,
            'pengajuan'         => $pengajuan,
        ];
        
        return view('hutalak.index', $arrData);


    }
}
