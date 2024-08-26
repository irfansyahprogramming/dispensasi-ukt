<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanDispensasiUKTModel;
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
        $unit = session('user_unit');
        $periode = BukaDispensasi::where('aktif', '1')->first();
        $list_dispensasi = DB::table('ref_jenisdipensasi')
            ->where('aktif', '1')
            ->get();
        $kel_ukt = DB::table('ref_kelompok_ukt')
            ->get();

        if ($periode) {
            $tombol = "";
            $semester = $periode->semester;
        } else {
            $tombol = "disabled";
            $semester = "All";
        }

        // @dd($unit);
        if ($mode == 'Program Studi'){
            $getDataMhs = Services::getMahasiswaPerProdi($unit,$semester,session('user_token'));
            // print_r ($getDataMhs);
    
            $lenMhs = count($getDataMhs['isi']);
            $arrMhs = $getDataMhs['isi'];
        }else{
            $lenMhs = 0;
            $arrMhs = "";
        }
       
        
        $badges = Functions::pengajuan($semester);
        // @dd(session('user_cmode'));
        $unit = trim(session('user_unit'));
        
        $pengajuan = DB::table('tb_pengajuan_dispensasi')
            // ->where('semester', trim($semester))
            ->where('status_pengajuan', '=', '0')
            ->orderBy('id','desc')
            ->get();
        foreach ($pengajuan as $ajuan) {
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        $verval_dekan = DB::table('tb_pengajuan_dispensasi')
        // ->where('semester', trim($semester))
            ->where('status_pengajuan', '=', '1')
            ->orderBy('id','desc')
            ->get();
        foreach ($verval_dekan as $dekan) {
            $dekan->nom_ukt = number_format($dekan->nominal_ukt, 0);
            $dekan->jenis = DB::table('ref_jenisdipensasi')->where('id', $dekan->jenis_dispensasi)->first()->jenis_dispensasi;
            $dekan->status = DB::table('ref_status_pengajuan')->where('id', $dekan->status_pengajuan)->first()->status_ajuan;
            $dekan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $dekan->kelompok_ukt)->first()->kelompok;
        }

        $verval_wr2 = DB::table('tb_pengajuan_dispensasi')
        // ->where('semester', trim($semester))
            ->where('status_pengajuan', '>=', '2')
            ->where('status_pengajuan', '<=', '3')
            ->orderBy('id','desc')
            ->get();
        foreach ($verval_wr2 as $wr2) {
            $wr2->nom_ukt = number_format($wr2->nominal_ukt, 0);
            $wr2->jenis = DB::table('ref_jenisdipensasi')->where('id', $wr2->jenis_dispensasi)->first()->jenis_dispensasi;
            $wr2->status = DB::table('ref_status_pengajuan')->where('id', $wr2->status_pengajuan)->first()->status_ajuan;
            $wr2->kelompok = DB::table('ref_kelompok_ukt')->where('id', $wr2->kelompok_ukt)->first()->kelompok;
        }

        $verval_wr1 = DB::table('tb_pengajuan_dispensasi')
        // ->where('semester', trim($semester))
            ->where('status_pengajuan', '>=', '4')
            ->where('status_pengajuan', '<=', '5')
            ->orderBy('id','desc')
            ->get();
        foreach ($verval_wr1 as $wr1) {
            $wr1->nom_ukt = number_format($wr1->nominal_ukt, 0);
            $wr1->jenis = DB::table('ref_jenisdipensasi')->where('id', $wr1->jenis_dispensasi)->first()->jenis_dispensasi;
            $wr1->status = DB::table('ref_status_pengajuan')->where('id', $wr1->status_pengajuan)->first()->status_ajuan;
            $wr1->kelompok = DB::table('ref_kelompok_ukt')->where('id', $wr1->kelompok_ukt)->first()->kelompok;
        }

        $verval_bakh = DB::table('tb_pengajuan_dispensasi')
        // ->where('semester', trim($semester))
            ->where('status_pengajuan', '>=', '6')
            // ->where('status_pengajuan', '<=', '7')
            ->orderBy('id','desc')
            ->get();
        foreach ($verval_bakh as $bakh) {
            $bakh->nom_ukt = number_format($bakh->nominal_ukt, 0);
            $bakh->jenis = DB::table('ref_jenisdipensasi')->where('id', $bakh->jenis_dispensasi)->first()->jenis_dispensasi;
            $bakh->status = DB::table('ref_status_pengajuan')->where('id', $bakh->status_pengajuan)->first()->status_ajuan;
            $bakh->kelompok = DB::table('ref_kelompok_ukt')->where('id', $bakh->kelompok_ukt)->first()->kelompok;
        }

        $finish = DB::table('tb_pengajuan_dispensasi')
        // ->where('semester', trim($semester))
            // ->where('status_pengajuan', '>=', '6')
            ->where('status_pengajuan', '=', '7')
            ->orderBy('id','desc')
            ->get();
        foreach ($finish as $end) {
            $end->nom_ukt = number_format($end->nominal_ukt, 0);
            $end->jenis = DB::table('ref_jenisdipensasi')->where('id', $end->jenis_dispensasi)->first()->jenis_dispensasi;
            $end->status = DB::table('ref_status_pengajuan')->where('id', $end->status_pengajuan)->first()->status_ajuan;
            $end->kelompok = DB::table('ref_kelompok_ukt')->where('id', $end->kelompok_ukt)->first()->kelompok;
        }
        // @dd ($verval_dekan);

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
            'unit'              => $unit,
            'subtitle'          => 'Dispensasi UKT',
            'home_active'       => '',
            'dispen_active'     => '',
            'dataukt_active'    => '',
            'laporan_active'    => '',
            'periode_active'    => '',
            'penerima_active'   => 'active',
            'users'              => session('user_username'),
            'semester'          => $semester,
            'kelompok_ukt'      => $kel_ukt,
            'pengajuan'         => $pengajuan,
            'verval_dekan'      => $verval_dekan,
            'verval_wr2'        => $verval_wr2,
            'verval_wr1'        => $verval_wr1,
            'verval_bakh'       => $verval_bakh,
            'finish'            => $finish,
            'badges'            => $badges,
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
            'semester'          => ['required'],
            'jenis_dispensasi'  => ['required'],
            'nim'               => ['required'],
            'nama_lengkap'      => ['required', 'string'],
            'kode_program_studi'=> ['required'],
            'program_studi'     => ['required'],
            'jenjang'           => ['required'],
            'semester_ke'       => ['required'],
            'alamat'            => ['required', 'string', 'max:255'],
            'nomor_hp'          => ['required'],
            'email'             => ['required', 'email'],
            'kelompok_ukt'      => ['required'],
            'nominal_ukt'       => ['required'],
            'pekerjaan'         => ['required'],
            'jabatan'           => ['required']
        ]);
        
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
        $nominal_ukt = intval(str_replace('.', '', $money));
        // $money = explode($nominal," ");
        // @dd($nominal_ukt);
        
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
        
        if ($response[0]['pembayaran'] >= 0 || $response[0]['pembayaran'] <> 'null'){
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
    
            // print_r($simpan);
            $path = 'file_pendukung/' . $semester . '/' . $nim;
            $path_pernyataan_saved = null;
            $path_keterangan_saved = null;
            $path_penghasilan_saved = null;
            $path_phk_saved = null;
            $path_pailit_saved = null;
            $path_pratranskrip_saved = null;
    
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
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }
                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                }
    
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
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }
                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                }
    
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
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }
                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan');
                }
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
                if (isset($request->file_phk)) {
                    $nama_dok = $request->file_phk->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_phk->extension();
                    $filename = 'f_phk_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_phk_saved = $request->file_phk->storeAs($path, $filename, 'public');
                }
    
                if (!$path_phk_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan PHK/Kematian');
                }
            }
    
            PengajuanDispensasiUKTModel::where([
                'semester'          => $semester,
                'nim'               => $nim
            ])->update([
                'file_permohonan'   => $path_permohonan_saved,
                'file_pernyataan'   => $path_pernyataan_saved,
                'file_keterangan'   => $path_keterangan_saved,
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
