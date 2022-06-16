<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
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
        $list_dispensasi = DB::table('ref_jenisdipensasi')
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

        $url = env('SIAKAD_URI') . "/dataMahasiswa/" . session('user_username') . "/" . session('user_token');
        $response = Http::get($url);
        $dataMhs = json_decode($response);

        if ($dataMhs->status == true) {
            foreach ($dataMhs->isi as $mhs) {
                $nama_lengkap = $mhs->nama;
                $kodeProdi = $mhs->kodeProdi;
                $nama_prodi = $mhs->namaProdi;
                $jenjang = $mhs->jenjangProdi;
                $hp = $mhs->hpm;
                $email = $mhs->email;
            }
        } else {
            $nama_lengkap = 'Kosong';
            $kodeProdi = 'Kosong';
            $nama_prodi = 'Kosong';
            $jenjang = 'Kosong';
            $hp = 'Kosong';
            $email = '<b>[Email UNJ anda tidak ada, silakan mengajukan pembuatan email ke UPT.TIK]</b>';
        }

        //cek status kerjasama dan bidikmisi di SIAKAD
        $urlb = env('SIAKAD_API') . "/beasiswaMahasiswaPerSemester/" . session('user_username') . "/" . $semester . "/" . session('user_token');
        $responseb = Http::get($urlb);
        $dataBeasiswa = json_decode($responseb);
        //echo $urlb;

        // print_r ($dataBeasiswa);
        if ($dataBeasiswa->status == true) {
            foreach ($dataBeasiswa->isi as $bea) {
                $kipk = $bea->beasiswa;
                $kerjasama = $bea->kerjasama;
            }
        } else {
            $kipk = 'no';
            $kerjasama = 'no';
        }

        $user = session('user_name');
        $mode = session('user_mode');

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
            'kodeProdi'         => $kodeProdi,
            'nama_prodi'        => $nama_prodi,
            'jenjang'           => $jenjang,
            'hp'                => $hp,
            'semester'          => $semester,
            'email'             => $email,
            'tombol'            => $tombol,
            'kipk'              => $kipk,
            'kerjasama'         => $kerjasama,
            'pengajuan'         => $pengajuan
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
        $nominal = $request->nominal_ukt;
        $lenMoney = strlen($nominal);
        $money = substr($nominal, 2, ($lenMoney - 2));
        $nominal_ukt = intval(str_replace(',', '', $money));

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
            $path_pernyataan_saved = null;
            $path_keterangan_saved = null;
            $path_penghasilan_saved = null;
            $path_phk_saved = null;
            $path_pailit_saved = null;
            $path_pratranskrip_saved = null;

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
                if (isset($request->file_pratranskrip)) {
                    $nama_dok = $request->file_pratranskrip->getClientOriginalName();
                    $slug = Functions::seo_friendly_url($nama_dok);
                    $ext = $request->file_pratranskrip->extension();
                    $filename = 'f_pratranskrip_' . mt_rand(1000, 9999) . '_' . $slug . '.' . $ext;
                    $path_pratranskrip_saved = $request->file_pratranskrip->storeAs($path, $filename, 'public');
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
