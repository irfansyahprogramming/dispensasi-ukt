<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use App\Models\HistoryPengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Models\PengajuanDispensasiUKTModel;
use PhpOffice\PhpSpreadsheet\Calculation\Web\Service;

class DispensasiController extends Controller
{
    public function index()
    {
        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }

        $periode = BukaDispensasi::checkOpenPeriode();

        if ($periode) {
            $tombol = "";
            $semester = $periode->semester;
        } else {
            $tombol = "disabled";
            $semester = "";
        }

        // get juknis
        $list_dispensasi = DB::table('ref_jenisdipensasi')->where('aktif','1')
            ->get();

        $kel_ukt = DB::table('ref_kelompok_ukt')
            ->get();

        $pengajuan = DB::table('tb_pengajuan_dispensasi')->where('nim', session('user_username'))
            ->get();

        foreach ($pengajuan as $ajuan) {
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        // $url = env('SIAKAD_URI') . "/dataMahasiswa/" . session('user_username') . "/" . session('user_token');
        // $response = Http::get($url);
        // $dataMhs = json_decode($response);
        $getDataMhs = Services::getDataMahasiswa(session('user_username'),session('user_token'));
        $arrMhs = $getDataMhs['isi'];

        if ($getDataMhs['status'] == true) {
            foreach ($arrMhs as $mhs) {
                $nama_lengkap = $mhs['nama'];
                $kodeProdi = $mhs['kodeProdi'];
                $nama_prodi = $mhs['namaProdi'];
                $jenjang = $mhs['jenjangProdi'];
                $hp = $mhs['hpm'];
                $email = $mhs['email'];
                $jalan = trim($mhs['alamat']);
                $rt = trim($mhs['rt']);
                $rw = trim($mhs['rw']);
                $kelurahan = trim($mhs['lurah']);
                $kecamatan = trim($mhs['namaKecamatan']);
                $kabkot = trim($mhs['namaKabkot']);
                $propinsi = trim($mhs['namaPropinsi']);
                $alamat = $jalan." RT/RW ".$rt."/".$rw." ".$kelurahan." ".$kecamatan." ".$kabkot." ".$propinsi; 
                $biayaKuliah = $mhs['biayaKuliah'];
                }
            } else {
                $nama_lengkap = 'Kosong';
                $kodeProdi = 'Kosong';
                $nama_prodi = 'Kosong';
                $jenjang = 'Kosong';
                $hp = 'Kosong';
                $biayaKuliah = 0;
                $alamat = "";
            $email = '<b>[Email UNJ anda tidak ada, silakan mengajukan pembuatan email ke UPT.TIK]</b>';
        }

        //cek status kerjasama dan bidikmisi di SIAKAD
        $urlb = env('SIAKAD_URI') . "/beasiswaMahasiswaPerSemester/" . session('user_username') . "/" . $semester . "/" . session('user_token');
        $responseb = Http::get($urlb);
        $dataBeasiswa = json_decode($responseb);
        //echo $urlb;

        $dataBeasiswa = Services::getBeasiswa(session('user_username'),$semester,session('user_toke'));


        // print_r ($dataBeasiswa);
        if ($dataBeasiswa['status'] == true) {
            foreach ($dataBeasiswa['isi'] as $bea) {
                $kipk = $bea['beasiswa'];
                $kerjasama = $bea['kerjasama'];
            }
        } else {
            $kipk = 'no';
            $kerjasama = 'no';
        }

        $user = session('user_name');
        $mode = session('user_mode');
        $status = '0';
        $arrData = [
            'title'             => 'Dispensasi',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Pengajuan',
            'home_active'       => '',
            'dispen_active'     => 'active',
            'list_dispensasi'   => $list_dispensasi,
            'kelompok_ukt'      => $kel_ukt,
            'nim'               => session('user_username'),
            'nama_lengkap'      => $nama_lengkap,
            'alamat'            => $alamat,
            'biayaKuliah'       => $biayaKuliah,
            'kodeProdi'         => $kodeProdi,
            'nama_prodi'        => $nama_prodi,
            'jenjang'           => $jenjang,
            'hp'                => $hp,
            'semester'          => $semester,
            'email'             => $email,
            'tombol'            => $tombol,
            'kipk'              => $kipk,
            'kerjasama'         => $kerjasama,
            'pengajuan'         => $pengajuan,
            'status'            => $status
        ];

        return view('pengajuan_dispensasi', $arrData);
        //return view('pengajuan_dispensasi',compact('list_dispensasi','kel_ukt','pengajuan','subtitle'));
    }

    public function simpan(Request $request)
    {
        //print_r($request->all());
        $credentials = $request->validate([
            'semester'          => ['required'],
            'nim'               => ['required'],
            'nama'              => ['required', 'string'],
            'prodi'             => ['required'],
            'namaprodi'         => ['required'],
            'hp'                => ['required'],
            'email'             => ['required'],
            'alamat'            => ['required', 'string', 'max:255'],
            'jenis_dispensasi'  => ['required'],
            'kelompok_ukt'      => ['required'],
            'nominal_ukt'       => ['required', 'string'],
            'pekerjaan'         => ['required'],
            'jabatan'           => ['required'],
            'cekSetuju'         => ['required']
        ]);

        $semester = $request->semester;
        $nim = $request->nim;
        $nama_lengkap = $request->nama;
        $prodi = $request->prodi;
        $namaprodi = $request->namaprodi;
        $jenjang = $request->jenjang;
        $hp = $request->hp;
        $email = $request->email;
        $alamat = $request->alamat;
        $jenis_dispensasi = $request->jenis_dispensasi;
        $kelompok_ukt = $request->kelompok_ukt;
        $nominal = trim($request->nominal_ukt);
        $nom = preg_replace('/[^0-9.]/', '', $nominal);
        $nominal_ukt = intval(str_replace('.', '', $nom));
        if ($nominal_ukt == 0 || $nominal_ukt >= 20000000) {
            return redirect()->back()->with('toast_error', 'Nominal UKT 0 atau lebih besar dari UKT '.$nominal);
            exit;
        }
        // dd($nominal_ukt);        

        if (isset($request->semesterke)) {
            $semesterke = $request->semesterke;
            $sks_belum = $request->sks_belum;
        } else {
            $semesterke = 0;
            $sks_belum = 0;
        }

        $pekerjaan = $request->pekerjaan;
        $jabatan = $request->jabatan;
        $status = '0';
        $cekSetuju = $request->cekSetuju;

        if (!isset($request->cekSetuju)) {
            return redirect()->back()->with('toast_error', 'Belum Cek Persetujuan');
        }

        $file_permohonan = null;
        $file_pernyataan = null;
        $file_keterangan = null;
        $file_penghasilan = null;
        $file_phk = null;
        $file_pailit = null;
        $file_pratranskrip = null;

        $pengajuan = DB::table('tb_pengajuan_dispensasi')->where('nim', session('user_username'))
            ->get();
        foreach($pengajuan as $ajuan){
            $file_permohonan = $ajuan->file_permohonan;
            $file_pernyataan = $ajuan->file_pernyataan;
            $file_keterangan = $ajuan->file_keterangan;
            $file_penghasilan = $ajuan->file_penghasilan;
            $file_phk = $ajuan->file_phk;
            $file_pailit = $ajuan->file_pailit;
            $file_pratranskrip = $ajuan->file_pratranskrip;
        }
        
        try {
            DB::beginTransaction();
            PengajuanDispensasiUKTModel::updateOrCreate(
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
                    'pekerjaan'         => $pekerjaan,
                    'jabatan_kerja'     => $jabatan,
                    'status_pengajuan'  => $status

                ]
            );

            $path = 'file_pendukung/' . $semester . '/' . $nim;
            $path_permohonan_saved = null;
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
                $size = $request->file_permohonan->getSize();
                $filename = 'f_permohonan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                if ($size > 204800){
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Permohonan - Ukuran File '.$size.' lebih besar dari 200 KB');
                }
                $path_permohonan_saved = $request->file_permohonan->storeAs($path, $filename, 'public');
            }else{
                $path_permohonan_saved = $file_permohonan;
            }

            if (!$path_permohonan_saved) {
                return redirect()->back()->with('toast_error', 'Gagal Upload File Permohonan - File Permohonan tidak ditemukan');
            }

            if (isset($request->file_pernyataan)) {
                $nama_dok = $request->file_pernyataan->getClientOriginalName();
                $slug = Functions::seo_friendly_url($nama_dok);
                $ext = $request->file_pernyataan->extension();
                $size = $request->file_pernyataan->getSize();
                $filename = 'f_pernyataan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                if ($size > 204800){
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Pengajuan - Ukuran File '.$size.' lebih besar dari 200 KB');
                }
                $path_pernyataan_saved = $request->file_pernyataan->storeAs($path, $filename, 'public');
            }else{
                $path_pernyataan_saved = $file_pernyataan;
            }

            if (!$path_pernyataan_saved) {
                return redirect()->back()->with('toast_error', 'Gagal Upload File Pengajuan - File Pengajuan tidak ditemukan');
            }

            if ($jenis_dispensasi === '1') {
                if (isset($request->file_pra_transkrip)) {
                    $nama_dok = $request->file_pra_transkrip->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_pra_transkrip->extension();
                    $filename = 'f_pratranskrip_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    
                    $size = $request->file_pra_transkrip->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Pra Transkrip - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_pratranskrip_saved = $request->file_pra_transkrip->storeAs($path, $filename, 'public');
                }else{
                    $path_pratranskrip_saved = $file_pratranskrip;
                }

                if (!$path_pratranskrip_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Pra Transkrip - File PraTranskrip tidak ditemukan');
                }
            } elseif ($jenis_dispensasi === '2') {
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_keterangan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }else{
                    $path_keterangan_saved = $file_keterangan;
                }

                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan - File tidak ditemukan');
                }

                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_penghasilan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }else{
                    $path_penghasilan_saved = $file_penghasilan;
                }

                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - File Penghasilan kosong');
                }

                if (isset($request->file_bukti_pailit)) {
                    $nama_dok = $request->file_bukti_pailit->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_bukti_pailit->extension();
                    $filename = 'f_pailit_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_bukti_pailit->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_pailit_saved = $request->file_bukti_pailit->storeAs($path, $filename, 'public');
                }else{
                    $path_pailit_saved = $file_pailit;
                }

                if (!$path_pailit_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan Kematian/Surat Keterangan PHK/SK Pensiun/Keterangan Dokter jika sakit permanen - File tidak ditemukan');
                }
            } elseif ($jenis_dispensasi === '3' || $jenis_dispensasi === '4' || $jenis_dispensasi === '5' || $jenis_dispensasi === '6') {
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_keterangan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan dari Kelurahan untuk yang terdampak - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }else{
                    $path_keterangan_saved = $file_keterangan;
                }

                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan - File tidak ditemukan');
                }

                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_penghasilan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }else{
                    $path_penghasilan_saved = $file_penghasilan;
                }

                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - File tidak ditemukan');
                }
            } else {
                if (isset($request->file_keterangan)) {
                    $nama_dok = $request->file_keterangan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_keterangan->extension();
                    $filename = 'f_keterangan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_keterangan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan dari Kelurahan untuk yang terdampak - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_keterangan_saved = $request->file_keterangan->storeAs($path, $filename, 'public');
                }else{
                    $path_keterangan_saved = $file_keterangan;
                }
                
                if (!$path_keterangan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan - File tidak ditemukan');
                }

                if (isset($request->file_penghasilan)) {
                    $nama_dok = $request->file_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_penghasilan->extension();
                    $filename = 'f_penghasilan_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_penghasilan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_penghasilan_saved = $request->file_penghasilan->storeAs($path, $filename, 'public');
                }else{
                    $path_penghasilan_saved = $file_penghasilan;
                }

                if (!$path_penghasilan_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan');
                }
                if (isset($request->file_kurang_penghasilan)) {
                    $nama_dok = $request->file_kurang_penghasilan->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_kurang_penghasilan->extension();
                    $filename = 'f_phk_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $size = $request->file_kurang_penghasilan->getSize();
                    if ($size > 204800){
                        return redirect()->back()->with('toast_error', 'Gagal Upload File Penghasilan - Ukuran File '.$size.' lebih besar dari 200 KB');
                    }
                    $path_phk_saved = $request->file_kurang_penghasilan->storeAs($path, $filename, 'public');
                }else{
                    $path_phk_saved = $file_phk;
                }

                if (!$path_phk_saved) {
                    return redirect()->back()->with('toast_error', 'Gagal Upload File Keterangan PHK/Kematian - File tidak ditemukan');
                }
            }
            // @dd($path_phk_saved);

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
                    'alasan_verif'      => "Keringanan UKT",
                    'status_pengajuan'  => "0",
                    'status_ajuan'      => '0'
                ]
            );
            // return $history;
            DB::commit();
            return redirect()->route('dispensasi.index')->with('toast_success', 'Pengajuan Dispensasi berhasil ');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('dispensasi.index')->with('toast_error', 'Error : ' . $ex->getMessage());
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
}
