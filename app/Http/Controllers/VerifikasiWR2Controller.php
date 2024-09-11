<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use App\Models\HistoryPengajuan;
use App\Models\PengajuanDispensasiUKTModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\TryCatch;
use Exception;
use Illuminate\Support\Facades\DB;
use Mockery\VerificationDirector;

class VerifikasiWR2Controller extends Controller
{
    public function index(Request $request)
    {

        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }
        $user = session('user_name');
        $mode = session('user_mode');
        $periode = BukaDispensasi::where('aktif', '1')->first();
        if ($periode) {
            $tombol = "";
            $semester = $periode->semester;
        } else {
            $tombol = "disabled";
            $semester = "";
        }

        // $badges = Functions::pengajuan($semester);
        $listSemester = DB::table('ref_periode')->get();
        $listJenis = DB::table('ref_jenisdipensasi')->get();
        $listStatus = DB::table('ref_status_pengajuan')->get();
        $listProdi = Services::getProdi('All');

        $pengajuan = DB::table('tb_pengajuan_dispensasi')
        ->select('tb_pengajuan_dispensasi.id', 'tb_pengajuan_dispensasi.semester', 'tb_pengajuan_dispensasi.nim', 'tb_pengajuan_dispensasi.nama', 'tb_pengajuan_dispensasi.kode_prodi', 'tb_pengajuan_dispensasi.nama_prodi', 'tb_pengajuan_dispensasi.jenjang_prodi', 'tb_pengajuan_dispensasi.kelompok_ukt', 'tb_pengajuan_dispensasi.nominal_ukt','tb_pengajuan_dispensasi.alamat', 'tb_pengajuan_dispensasi.no_hp','tb_pengajuan_dispensasi.email','tb_pengajuan_dispensasi.pekerjaan', 'tb_pengajuan_dispensasi.jabatan_kerja','tb_pengajuan_dispensasi.pengalihan', 'tb_pengajuan_dispensasi.awal_pengajuan','tb_pengajuan_dispensasi.status_pengajuan', 'tb_pengajuan_dispensasi.semesterke','tb_pengajuan_dispensasi.sks_belum', 'tb_pengajuan_dispensasi.file_pernyataan','tb_pengajuan_dispensasi.file_keterangan', 'tb_pengajuan_dispensasi.file_permohonan','tb_pengajuan_dispensasi.file_penghasilan', 'tb_pengajuan_dispensasi.file_phk','tb_pengajuan_dispensasi.file_pailit', 'tb_pengajuan_dispensasi.file_pratranskrip','tb_pengajuan_dispensasi.potongan' ,'tb_pengajuan_dispensasi.ditagihkan', 'tb_pengajuan_dispensasi.angsuran1', 'tb_pengajuan_dispensasi.angsuran2', 'tb_pengajuan_dispensasi.kel_ukt_baru', 'ref_jenisdipensasi.jenis_dispensasi', 'ref_status_pengajuan.status_ajuan', 'ref_kelompok_ukt.kelompok');
        
        if (isset($request->semester) and $request->semester != 'All') {
            $pengajuan = $pengajuan->where('tb_pengajuan_dispensasi.semester', trim($request->semester));
        } else {
            $pengajuan = $pengajuan->where('tb_pengajuan_dispensasi.semester', trim($semester));
        }

        // by prodi
        if (isset($request->prodi) and $request->prodi != 'All') {
            $pengajuan = $pengajuan->where('tb_pengajuan_dispensasi.kode_prodi', trim($request->prodi));
        }

        // by jenis pengajuan
        if (isset($request->jenis) and $request->jenis != 'All') {
            $pengajuan = $pengajuan->where('tb_pengajuan_dispensasi.jenis_dispensasi', $request->jenis);
        }

        // get data pengajuan
        $pengajuan = $pengajuan
            
            ->join('ref_jenisdipensasi','ref_jenisdipensasi.id', '=' ,'tb_pengajuan_dispensasi.jenis_dispensasi')
            ->join('ref_status_pengajuan','ref_status_pengajuan.id', '=', 'tb_pengajuan_dispensasi.status_pengajuan','inner')
            ->join('ref_kelompok_ukt','ref_kelompok_ukt.id', '=', 'tb_pengajuan_dispensasi.kelompok_ukt','inner')
            ->where(function ($query) {
                $query->where('tb_pengajuan_dispensasi.status_pengajuan', '2')
                    ->orWhere('tb_pengajuan_dispensasi.status_pengajuan', '3')
                    ->orWhere('tb_pengajuan_dispensasi.status_pengajuan', '23');
            })->orderBy('tb_pengajuan_dispensasi.status_pengajuan','asc')->get();

        // foreach ($pengajuan as $ajuan) {
        //     $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
        //     $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
        //     $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
        //     $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        // }
        
        // flash request data
        $request->flash();

        $arrData = [
            'title'             => 'Home',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Verifikasi Wakil Rektor II',
            'home_active'       => '',
            'periode_active'       => '',
            'dispen_active'     => 'active',
            'laporan_active'    => '',
            'penerima_active'   => '',
            'user'              => session('user_username'),
            'semester'          => $semester,
            'pengajuan'         => $pengajuan,
            'listSemester'      => $listSemester,
            'listProdi'         => $listProdi,
            'listJenis'         => $listJenis,
            // 'badges'            => $badges
        ];

        return view('wr2.verifikasi_dispensasi', $arrData);
    }

    public function delete($id)
    {
        $data = PengajuanDispensasiUKTModel::findOrFail($id);

        $data->delete();

        return redirect()->back()->with('toast_success', 'Data telah dihapus')->with('dispen_active', 'active');
    }

    public function simpan(Request $request)
    {
        $nim = $request->nim;
        $semester = $request->semester;
        $kelayakan = $request->sellayak;
        $id = $request->id;
        $alasan = $request->txtAlasan;

        try {
            DB::beginTransaction();

            if ($kelayakan == '1') {
                $status_pengajuan = '3';
            } elseif ($kelayakan == '2') {
                $status_pengajuan = '23';
            } else {
                return redirect()->back()->with('toast_error', 'Belum Ada Pilihan Kelayakan Berkas Dokumen');
            }

            $store = PengajuanDispensasiUKTModel::where([
                'nim'    => $nim,
                'semester'  => $semester
            ])->update([
                'status_pengajuan'  => $status_pengajuan
            ]);

            if ($store) {
                HistoryPengajuan::updateOrCreate(
                    [
                        'id_pengajuan'      => $id,
                        'v_mode'            => trim(session('user_cmode'))
                    ],
                    [
                        'alasan_verif'      => $alasan,
                        'status_ajuan'      => $kelayakan,
                        'status_pengajuan'  => $status_pengajuan
                    ]
                );
            }

            DB::commit();
            return redirect()->route('verifikasiWR2_dispensasi.index')->with('toast_success', 'Verifikasi Kelayakan Pengajuan Dispensasi berhasil');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('verifikasiWR2_dispensasi.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function detil($id)
    {
        //echo $id;

        $data = PengajuanDispensasiUKTModel::where('id', $id)->first();
        // @dd($id);
        if (isset($data->jenis_dispensasi)) {
            $data->nom_ukt = number_format($data->nominal_ukt, 0);
            $data->jenis = DB::table('ref_jenisdipensasi')->where('id', $data->jenis_dispensasi)->first()->jenis_dispensasi;
            $data->status = DB::table('ref_status_pengajuan')->where('id', $data->status_pengajuan)->first()->status_ajuan;
            $data->kelompok = DB::table('ref_kelompok_ukt')->where('id', $data->kelompok_ukt)->first()->kelompok;

            $data->file_pendukung = "<a href = " . asset('storage/' . $data->file_permohonan) . " target='_blank'>File Permohonan</a>";

            if ($data->file_pernyataan <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_pernyataan) . " target='_blank'>File pernyataan</a>";
            }
            if ($data->file_keterangan <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_keterangan) . " target='_blank'>File Keterangan Terdampak</a>";
            }
            if ($data->file_penghasilan <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_penghasilan) . " target='_blank'>File Slip Gaji/Keterangan Penghasilan</a>";
            }
            if ($data->file_pailit <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_pailit) . " target='_blank'>File Surat Pengadilan/Surat Keterangan Pailit/Bangkrut</a>";
            }
            if ($data->file_phk <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_phk) . " target='_blank'>File Surat Kematian/PHK/Cacat Permanen</a>";
            }
            if ($data->file_pratranskrip <> null) {
                $data->file_pendukung .= "<br/><a href = " . asset('storage/' . $data->file_pratranskrip) . " target='_blank'>File Pratranskrip</a>";
            }

            $history = HistoryPengajuan::where('id_pengajuan', $data->id)->where('v_mode', trim(session('user_cmode')))->first();
            //return count($history);
            if ($history) {
                $data->alasan_verif = $history->alasan_verif;
                $data->layak = $history->status_ajuan;
            } else {
                $data->alasan_verif = "";
                $data->layak = 0;
            }

            //get data siakad
            // $url = env('SIAKAD_URI') . "/dataMahasiswa/" . $data->nim . "/" . session('user_token');
            // $response = Http::get($url);
            // $dataMhs = json_decode($response);

            $status_mahasiswa = Services::getDataMahasiswa($data->nim,session('user_token'));
            $dataMhs = $status_mahasiswa['isi'];
            // @dd($dataMhs);
            if ($status_mahasiswa['status'] == true) {
                foreach ($dataMhs as $mhs) {
                    $data->nim_siakad = $mhs['nim'];
                    $data->nama_siakad = $mhs['namaLengkap'];
                    $data->prodi_siakad = $mhs['jenjangProdi'] . " " . $mhs['namaProdi'];
                    $data->kontak_siakad = $mhs['hpm'] . " / " . $mhs['email'];
                    $data->alamat_siakad = $mhs['alamat'] . " RT. " . $mhs['rt'] . " RW." . $mhs['rw'] . "<br/> Kelurahan " . $mhs['lurah'] . "<br/>  " . $mhs['namaKecamatan'] . "<br/>  " . $mhs['namaKabkot'] . "<br/>  " . $mhs['namaPropinsi'] . " Kode pos " . $mhs['kdpos'];
                    $data->nom_ukt_siakad = number_format($mhs['biayaKuliah'], 0);
                }
            } else {
                $data->nim_siakad = "<i class='fas fa-x'></i>";
                $data->nama_siakad = "<i class='fas fa-x'></i>";
                $data->prodi_siakad = "<i class='fas fa-x'></i>";
                $data->kontak_siakad = "<i class='fas fa-x'></i>";
                $data->alamat_siakad = "<i class='fas fa-x/'></i>";
                $data->nom_ukt_siakad = "<i class='fas fa-x'></i>";
            }
        }
        // @dd($data);
        return json_encode($data);
    }

    public function layakpost(Request $request)
    {
        $data = array();
        $semester = $request->semester;
        $nim = $request->nim;
        $ajuan = $request->idAjuan;
        $pesan = "";
        foreach ($ajuan as $x) {
            $id = $x;
            if ($id == 'deselect') {
                continue;
            } else {
                try {
                    DB::beginTransaction();
                    $store = PengajuanDispensasiUKTModel::where([
                        'id'    => $id
                    ])->update([
                        'status_pengajuan'  => '3'
                    ]);

                    if ($store) {
                        HistoryPengajuan::updateOrCreate(
                            [
                                'id_pengajuan'      => $id,
                                'v_mode'            => trim(session('user_cmode'))
                            ],
                            [
                                'alasan_verif'      => null,
                                'status_ajuan'      => '1',
                                'status_pengajuan'  => '3'
                            ]
                        );
                    }

                    DB::commit();
                    $pesan .= "ID " . $id . " Berhasil input Layak<br/>";
                    //return redirect()->route('verifikasiWR2_dispensasi.index')->with('toast_success', 'Verifikasi Kelayakan Pengajuan Dispensasi berhasil');
                } catch (Exception $ex) {
                    DB::rollBack();
                    $pesan .= "ID " . $id . " Gagal input Layak<br/>";
                }
            }
        }

        $data = [
            'pesan' => $pesan,
            'status' => true
        ];
        return $data;
        // return json_encode($data);
    }

    public function tidaklayakpost(Request $request)
    {
        $data = array();
        $semester = $request->semester;
        $nim = $request->nim;
        $ajuan = $request->idAjuan;
        $pesan = "";
        foreach ($ajuan as $x) {
            $id = $x;
            if ($id == 'deselect') {
                continue;
            } else {
                try {
                    DB::beginTransaction();
                    $store = PengajuanDispensasiUKTModel::where([
                        'id'    => $id
                    ])->update([
                        'status_pengajuan'  => '22'
                    ]);

                    if ($store) {
                        HistoryPengajuan::updateOrCreate(
                            [
                                'id_pengajuan'      => $id,
                                'v_mode'            => trim(session('user_cmode'))
                            ],
                            [
                                'alasan_verif'      => null,
                                'status_ajuan'      => '2',
                                'status_pengajuan'  => '22'
                            ]
                        );
                    }

                    DB::commit();
                    $pesan .= "ID " . $id . " Berhasil input Tidak Layak<br/>";
                    //return redirect()->route('verifikasiWR2_dispensasi.index')->with('toast_success', 'Verifikasi Kelayakan Pengajuan Dispensasi berhasil');
                } catch (Exception $ex) {
                    DB::rollBack();
                    $pesan .= "ID " . $id . " Gagal input Tidak Layak<br/>";
                }
            }
        }

        $data = [
            'pesan' => $pesan,
            'status' => true
        ];
        return $data;
        // return json_encode($data);
    }
}
