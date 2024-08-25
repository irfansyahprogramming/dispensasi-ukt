<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }


        $user = session('user_name');
        $mode = session('user_mode');
        $unit = session('user_unit');

        $periode = BukaDispensasi::where('aktif', '1')->first();
        if ($periode) {
            $tombol = "";
            $semester = $periode->semester;
        } else {
            $tombol = "disabled";
            $semester = "";
        }

        $badges = Functions::pengajuan($semester);

        $rekapPengajuan =    DB::table('tr_history_pengajuan')
                        ->join('tb_pengajuan_dispensasi','tr_history_pengajuan.id_pengajuan','=','tb_pengajuan_dispensasi.id')
                        ->join('ref_jenisdipensasi','tb_pengajuan_dispensasi.jenis_dispensasi','=','ref_jenisdipensasi.id')
                        ->select(
                            'ref_jenisdipensasi.jenis_dispensasi',
                            DB::raw('SUM(case when tr_history_pengajuan.status_pengajuan = 0 then 1 else 0 end) as ajuan0'),
                            DB::raw('SUM(case when tr_history_pengajuan.status_pengajuan = 1 then 1 else 0 end) as ajuan1'),
                            DB::raw('SUM(case when tr_history_pengajuan.status_pengajuan = 2 then 1 else 0 end) as ajuan2'),
                            DB::raw('SUM(case when tr_history_pengajuan.status_pengajuan = 3 then 1 else 0 end) as ajuan3'),
                            DB::raw('sum(case when tr_history_pengajuan.status_pengajuan = 4 then 1 else 0 end) as ajuan4'),
                            DB::raw('sum(case when tr_history_pengajuan.status_pengajuan = 5 then 1 else 0 end) as ajuan5'),
                            DB::raw('sum(case when tr_history_pengajuan.status_pengajuan = 6 then 1 else 0 end) as ajuan6'),
                            DB::raw('sum(case when tr_history_pengajuan.status_pengajuan = 7 then 1 else 0 end) as ajuan7')
                        )
                        ->where('ref_jenisdipensasi.aktif','=','1')
                        ->where('tb_pengajuan_dispensasi.semester','=',$semester)
                        ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim(session('user_unit')) . '%')
                        ->groupBy('ref_jenisdipensasi.jenis_dispensasi')
                        ->orderBy('ref_jenisdipensasi.id')
                        ->get();
        

        $arrData = [
            'title'             => 'Home',
            'active'            => 'Rekapitulasi Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Rekap Dispensasi UKT',
            'home_active'       => '',
            'penerima_active'   => '',
            'dataukt_active'    => '',
            'dispen_active'     => '',
            'periode_active'    => '',
            'laporan_active'    => 'active',
            'nim'               => session('user_username'),
            'semester'          => $semester,
            'rekap'             => $rekapPengajuan,
            'badges'            => $badges
        ];

        return view('laporan.rekap', $arrData);
        //return view('pengajuan_dispensasi',compact('list_dispensasi','kel_ukt','pengajuan','subtitle'));
    }
}
