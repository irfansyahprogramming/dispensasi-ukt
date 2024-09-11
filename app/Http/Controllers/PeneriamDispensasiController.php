<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanDispensasiUKTModel;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;
use PDF;

class PeneriamDispensasiController extends Controller
{
    public function index()
    {

        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }
        $user = session('user_name');
        $mode = session('user_mode');
        $cmode = session('user_cmode');
        $unit = session('user_unit');

        $periode = BukaDispensasi::where('aktif', '1')->first();
        $list_dispensasi = DB::table('ref_jenisdipensasi')
            ->where('aktif', '1')
            ->get();
        $kel_ukt = DB::table('ref_kelompok_ukt')
            ->get();

        $now = new DateTime("now");
        if ($periode) {
            
            $semester = $periode->semester;
            $awal = new DateTime($periode->start_date);
            $akhir = new DateTime($periode->end_date);
            if ($now->getTimestamp() >= $awal->getTimestamp() && $now->getTimestamp() <= $akhir->getTimestamp()){
                $tombol = "";
            }else{
                $tombol = "disabled";
            }
        } else {
            $tombol = "disabled";
            $semester = "All";
        }

        // 
        if ($mode == 'Program Studi'){
            $getDataMhs = Services::getMahasiswaPerProdi($unit,$semester,session('user_token'));
            // print_r ($getDataMhs);
            $lenMhs = count($getDataMhs['isi']);
            $arrMhs = $getDataMhs['isi'];
        }else{
            $lenMhs = 0;
            $arrMhs = "";
        }
        
        $dispensasi = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');

        $unit = trim(session('user_unit'));
        if ($cmode == '3' || $cmode == '14'){
            $pengajuan = $dispensasi->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '0')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $pengajuan = $dispensasi->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '0')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $pengajuan = $dispensasi
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '0')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }
        
        $dispensasiFak = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiFak = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $verval_dekan = $dispensasiFak->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '1')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '<=', '2')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $verval_dekan = $dispensasiFak->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '1')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '<=', '2')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $verval_dekan = $dispensasiFak->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '1')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '<=', '2')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }

        $dispensasiWR2 = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiWR2 = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $verval_wr2 = $dispensasiWR2
            ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>', '2')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $verval_wr2 = $dispensasiWR2
            ->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>', '2')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $verval_wr2 = $dispensasiWR2
            ->where('status_pengajuan', '>', '2')
            ->orderBy('id','desc')
            ->get();
        }

        $dispensasiWR1 = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiWR1 = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $verval_wr1 = $dispensasiWR1
            ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $verval_wr1 = $dispensasiWR1
            ->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $verval_wr1 = $dispensasiWR1
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }
        
        $dispensasiHutalak = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiHutalak = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $verval_hutalak = $dispensasiHutalak
            ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $verval_hutalak = $dispensasiHutalak
            ->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $verval_hutalak = $dispensasiHutalak
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }
        
        $dispensasiBAKH = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiBAKH = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $verval_bakh = $dispensasiBAKH
            ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $verval_bakh = $dispensasiBAKH
            ->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $verval_bakh = $dispensasiBAKH
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '>=', '3')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }
        
        $dispensasiFinish = DB::table('tb_pengajuan_dispensasi')
        ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
        ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
        ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner');
        // $dispensasiFinish = PengajuanDispensasiUKTModel::where('semester',$semester);
        if ($cmode == '3' || $cmode == '14'){
            $finish = $dispensasiFinish
            ->where('tb_pengajuan_dispensasi.kode_prodi','like',trim($unit).'%')
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '7')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }elseif($cmode == '2'){
            $finish = $dispensasiFinish
            ->where('tb_pengajuan_dispensasi.kode_prodi',trim($unit))
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '7')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }else{
            $finish = $dispensasiFinish
            ->where('tb_pengajuan_dispensasi.status_pengajuan', '=', '7')
            ->orderBy('tb_pengajuan_dispensasi.id','desc')
            ->get();
        }
        
        if (Session::has('dekan_active')) {
            Session::forget('ajuan_active');
            Session::forget('wr2_active');
            Session::forget('wr1_active');
            Session::forget('bakhum_active');
            Session::forget('selesai');
        } else if (Session::has('wr2_active')) {
            Session::forget('ajuan_active');
            Session::forget('dekan_active');
            Session::forget('wr1_active');
            Session::forget('bakhum_active');
            Session::forget('selesai');
        } else if (Session::has('wr1_active')) {
            Session::forget('ajuan_active');
            Session::forget('dekan_active');
            Session::forget('wr2_active');
            Session::forget('bakhum_active');
            Session::forget('selesai');
        } else if (Session::has('bakhum_active')) {
            Session::forget('ajuan_active');
            Session::forget('dekan_active');
            Session::forget('wr2_active');
            Session::forget('wr1_active');
            Session::forget('selesai');
        } else if (Session::has('selesai')) {
            Session::forget('ajuan_active');
            Session::forget('dekan_active');
            Session::forget('wr2_active');
            Session::forget('wr1_active');
            Session::forget('bakhum_active');
        } else {
            Session::flash('ajuan_active', 'active');
        }

        $arrData = [
            'title'             => 'Home',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'cmode'             => $cmode,
            'unit'              => $unit,
            'subtitle'          => 'Dispensasi UKT',
            'home_active'       => '',
            'dispen_active'     => '',
            'dataukt_active'    => '',
            'laporan_active'    => '',
            'periode_active'    => '',
            'penerima_active'   => 'active',
            'users'             => session('user_username'),
            'tombol'            => $tombol,
            'semester'          => $semester,
            'kelompok_ukt'      => $kel_ukt,
            'pengajuan'         => $pengajuan,
            'verval_dekan'      => $verval_dekan,
            'verval_wr2'        => $verval_wr2,
            'verval_wr1'        => $verval_wr1,
            'verval_bakh'       => $verval_bakh,
            'finish'            => $finish,
            // 'badges'            => $badges,
            'list_dispensasi'   => $list_dispensasi,
            'countMhs'          => $lenMhs,
            'arrMhs'            => $arrMhs
        ];
        // @dd($arrData) ;
        // return $arrData;
        // if ($semester == ""){
        //     return view('penerimaDispensasi.close', $arrData);
        // }else{
        //     return view('penerimaDispensasi.index', $arrData);
        // }
        return view('penerimaDispensasi.index', $arrData);
    }

    public function store(Request $request)
    {
        // print_r($request->all());
        $credentials = $request->validate([
            'semester'                  => ['required'],
            'jenis_dispensasi'          => ['required'],
            'nim'                       => ['required'],
            'nama_lengkap'              => ['required', 'string'],
            'kode_program_studi'        => ['required'],
            'program_studi'             => ['required'],
            'jenjang'                   => ['required'],
            'semester_ke'               => ['required'],
            'alamat'                    => ['required', 'string', 'max:255'],
            'nomor_hp'                  => ['required'],
            'email'                     => ['required', 'email'],
            'kelompok_ukt'              => ['required'],
            'nominal_ukt'               => ['required'],
            'pekerjaan'                 => ['required'],
            'jabatan'                   => ['required']
            // 'file_permohonan'           => ['file|max:512'],
            // 'file_pernyataan'           => ['file|max:512'],
            // 'file_pra_transkrip'        => ['file|max:512'],
            // 'file_penghasilan'          => ['file|max:512'],
            // 'file_kurang_penghasilan'   => ['file|max:512']
        ]);

        $id = $request->id;
        $semester = $request->semester;
        $jenis_dispensasi = $request->jenis_dispensasi;
        $nim = $request->nim;
        $nama_lengkap = $request->nama_lengkap;
        $prodi = $request->kode_program_studi;
        $namaprodi = $request->program_studi;
        $jenjang = $request->jenjang;
        $hp = $request->nomor_hp;
        $email = $request->email;
        $alamat = $request->alamat;
        $kelompok_ukt = $request->kelompok_ukt;
        $nominal = $request->nominal_ukt;
        
        $lenMoney = strlen($nominal);
        $money = substr($nominal, 4, ($lenMoney - 3));
        if (isset($request->id)){
            // @dd($lenMoney);
            $money = substr($nominal, 3, ($lenMoney - 3));
            // @dd($lenMoney."-".$money);
        }
        $nominal_ukt = intval(str_replace('.', '', $money));
        // $money = explode($nominal," ");
        // @dd($money."-".$nominal_ukt);
        
        if (isset($request->semester_ke)) {
            $semesterke = $request->semester_ke;
            $sks_belum = $request->sks_belum;
        } else {
            $semesterke = 0;
            $sks_belum = 0;
        }

        $pekerjaan = $request->pekerjaan;
        $jabatan = $request->jabatan;
        $status = '0';
        // $cekSetuju = $request->cekSetuju;

        if ($jenis_dispensasi == '1' && $request->sks_belum =="" ) {
            return redirect()->back()->with('toast_error', 'jumlah SKS yang belum selesai masih kosong ');
            exit;
        }

        //get status mahasiswa 
        $status_mahasiswa = Services::getStatusMahasiswa($semester,$nim,session('user_token'));
        $response = $status_mahasiswa['isi'];
        
        
        if ($response[0]['beasiswa'] == 'Ya'){
            return redirect()->back()->with('toast_error', 'Beasiswa tidak mendapatkan dispensasi');
            exit;
        }

        if ($response[0]['bayaran_sebelumnya'] == 0){
            return redirect()->back()->with('toast_error', 'Lunasi dahulu pembayaran sebelumnya');
            exit;
        }

        if ($response[0]['habis_studi'] <= 0){
            return redirect()->back()->with('toast_error', 'Masa Studi Sudah Habis');
            exit;
        }
        
        if ($response[0]['pembayaran'] != null){
            return redirect()->back()->with('toast_error', 'Anda Sudah Membayar');
            exit;
        }

        try {
            DB::beginTransaction();
            
            $simpan = PengajuanDispensasiUKTModel::updateOrCreate(
                [
                    'semester'          => $semester,
                    'nim'               => $nim
                ],
                [
                    'jenis_dispensasi'  => $jenis_dispensasi,
                    'nama'              => $nama_lengkap,
                    'kode_prodi'        => $prodi,
                    'nama_prodi'        => $namaprodi,
                    'jenjang_prodi'     => $jenjang,
                    'alamat'            => $alamat,
                    'no_hp'             => $hp,
                    'email'             => $email,
                    'semesterke'        => $semesterke,
                    'sks_belum'         => $sks_belum,
                    'kelompok_ukt'      => $kelompok_ukt,
                    'nominal_ukt'       => $nominal_ukt,
                    'status_pengajuan'  => $status,
                    'pekerjaan'         => $pekerjaan,
                    'jabatan_kerja'     => $jabatan
    
                ]
            );
            
            $pengajuan = DB::table('tb_pengajuan_dispensasi')
            // ->where('semester', trim($semester))
            ->where('nim', '=', $nim)
            ->where('semester','=',$semester)
            ->get();
            
            $path = 'file_pendukung/' . $semester . '/' . $nim;
                
            if ($pengajuan){
                foreach ($pengajuan as $ajuan) {
                    $path_pernyataan_saved = $ajuan->file_pernyataan;
                    $path_permohonan_saved = $ajuan->file_permohonan;
                    $path_penghasilan_saved = $ajuan->file_penghasilan;
                    $path_phk_saved = $ajuan->file_phk;
                    $path_pailit_saved = $ajuan->file_pailit;
                    $path_pratranskrip_saved = $ajuan->file_pratranskrip;
                }
            }else{
                $path_pernyataan_saved = null;
                $path_permohonan_saved = null;
                $path_penghasilan_saved = null;
                $path_phk_saved = null;
                $path_pailit_saved = null;
                $path_pratranskrip_saved = null;
            }
            
            if (isset($request->file_permohonan)) {
                $nama_dok = $request->file_permohonan->getClientOriginalName();
                $slug = Functions::seo_friendly_url($nama_dok);
                $ext = $request->file_permohonan->extension();
                $filename = 'f_permohonan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                $path_permohonan_saved = $request->file_permohonan->storeAs($path, $filename, 'public');
            }
    
            if (!$path_permohonan_saved) {
                return redirect()->back()->with('toast_error', 'Gagal Upload File Permohonan');
            }
    
            if (isset($request->file_pernyataan)) {
                $nama_dok = $request->file_pernyataan->getClientOriginalName();
                $slug = Functions::seo_friendly_url($nama_dok);
                $ext = $request->file_pernyataan->extension();
                $filename = 'f_pernyataan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                $path_pernyataan_saved = $request->file_pernyataan->storeAs($path, $filename, 'public');
            }
    
            if (!$path_pernyataan_saved) {
                return redirect()->back()->with('toast_error', 'Gagal Upload File Pernyataan');
            }
    
            if ($jenis_dispensasi === '1') {
                
                if (isset($request->file_pra_transkrip)) {
                    // var_dump($request->file_pra_transkrip);
                    $nama_dok = $request->file_pra_transkrip->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_pra_transkrip->extension();
                    $filename = 'f_pratranskrip_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_pratranskrip_saved = $request->file_pra_transkrip->storeAs($path, $filename, 'public');
                }
    
                if (!$path_pratranskrip_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Pra Transkrip');
                }
            } elseif ($jenis_dispensasi === '2') {
                // if (isset($request->file_keterangan)) {
                //     $nama_dok = $request->file_keterangan->getClientOriginalName();
                //     $slug = Functions::seo_friendly_url($nama_dok);
                //     $ext = $request->file_keterangan->extension();
                //     $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                //     $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                // }
                // if (!$path_keterangan_saved) {
                //     return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                // }
    
                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }
    
                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan');
                }
    
                if (isset($request->file_bukti_pailit)) {
                    $nama_dok = $request->file_bukti_pailit->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_bukti_pailit->extension();
                    $filename = 'f_pailit_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_pailit_saved = $request->file_bukti_pailit->storeAs($path, $filename, 'public');
                }
    
                if (!$path_pailit_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Kebangkrutan');
                }
            } elseif ($jenis_dispensasi === '3' || $jenis_dispensasi === '4' || $jenis_dispensasi === '5' || $jenis_dispensasi === '6') {
                // if (isset($request->file_keterangan)) {
                //     $nama_dok = $request->file_keterangan->getClientOriginalName();
                //     $slug = Functions::seo_friendly_url($nama_dok);
                //     $ext = $request->file_keterangan->extension();
                //     $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                //     $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                // }
                // if (!$path_keterangan_saved) {
                //     return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                // }
    
                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }
    
                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan');
                }
            } else {
                // if (isset($request->file_keterangan)) {
                //     $nama_dok = $request->file_keterangan->getClientOriginalName();
                //     $slug = Functions::seo_friendly_url($nama_dok);
                //     $ext = $request->file_keterangan->extension();
                //     $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                //     $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                // }
                // if (!$path_keterangan_saved) {
                //     return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                // }
                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }
    
                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan');
                }
                if (isset($request->file_kurang_penghasilan)) {
                    $nama_dok = $request->file_kurang_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_kurang_penghasilan->extension();
                    $filename = 'f_phk_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_phk_saved = $request->file_kurang_penghasilan->storeAs($path, $filename, 'public');
                }
    
                if (!$path_phk_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan PHK/Kematian');
                }
            }
            // @dd($path_pratranskrip_saved);
    
            PengajuanDispensasiUKTModel::where([
                'semester'          => $semester,
                'nim'               => $nim
            ])->update([
                'file_permohonan'   => $path_permohonan_saved,
                'file_pernyataan'   => $path_pernyataan_saved,
                // 'file_keterangan'   => $path_keterangan_saved,
                'file_penghasilan'  => $path_penghasilan_saved,
                'file_phk'          => $path_phk_saved,
                'file_pailit'       => $path_pailit_saved,
                'file_pratranskrip' => $path_pratranskrip_saved
            ]);
            
            $dataAjuan = PengajuanDispensasiUKTModel::where('semester', $semester)->where('nim', $nim)->first();
            $history = HistoryPengajuan::updateOrCreate(
                [
                    'id_pengajuan'      => $dataAjuan->id,
                    'v_mode'            => trim(session('user_cmode'))
                ],
                [
                    'alasan_verif'      => '',
                    'status_ajuan'      => '0',
                    'status_pengajuan'  => '0'
                ]
            );
            
            // return $history;
            DB::commit();
            return redirect()->route('penerima_dispensasi.index')->with('toast_success', 'Pengajuan Dispensasi berhasil ');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('penerima_dispensasi.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function delete($id)
    {
        $data = PengajuanDispensasiUKTModel::findOrFail($id);

        $data->delete();

        return redirect()->back()->with('toast_success', 'Data telah dihapus')->with('dispen_active', 'active');
    }
    public function edit($id)
    {
        $data = PengajuanDispensasiUKTModel::findOrFail($id);
        return json_encode($data);
    }
    public function getData($nim)
    {
        $getDataMhs = Services::getDataMahasiswa($nim,session('user_token'));
        $lenMhs = count($getDataMhs['isi']);
        $arrMhs = $getDataMhs['isi'];

        // print_r($arrMhs);
        return json_encode($arrMhs);
    }
    public function getDataEdit($id)
    {
        $getDataMhs = DB::table('tb_pengajuan_dispensasi')->where('id',$id)->get();
        $arrMhs = $getDataMhs;

        // print_r($arrMhs);
        return json_encode($arrMhs);
    }

}
