<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use App\Models\DataUKT;
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



class VerifikasiUKTController extends Controller
{
    public function index()
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

        // get juknis
        $list_dispensasi = DB::table('ref_jenisdipensasi')
            ->get();
        $kel_ukt = DB::table('ref_kelompok_ukt')
            ->get();


        $badges = Functions::pengajuan($semester);

        $pengajuan = DB::table('tb_pengajuan_dispensasi')

            ->where('kode_prodi', 'like', trim(session('user_unit')) . '%')
            ->where('semester', trim($semester))
            ->where(function ($query) {
                $query->where('status_pengajuan', '0')
                    ->orWhere('status_pengajuan', '>=', '1')
                    ->orWhere('status_pengajuan', '>=', '21');
            })->get();

        foreach ($pengajuan as $ajuan) {
            $ajuan->nom_ukt = number_format($ajuan->nominal_ukt, 0);
            $ajuan->jenis = DB::table('ref_jenisdipensasi')->where('id', $ajuan->jenis_dispensasi)->first()->jenis_dispensasi;
            $ajuan->status = DB::table('ref_status_pengajuan')->where('id', $ajuan->status_pengajuan)->first()->status_ajuan;
            $ajuan->kelompok = DB::table('ref_kelompok_ukt')->where('id', $ajuan->kelompok_ukt)->first()->kelompok;
        }

        $arrData = [
            'title'             => 'Dispensasi',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Verifikasi Dispensasi',
            'home_active'       => '',
            'dispen_active'     => 'active',
            'dataukt_active'    => '',
            'laporan_active'    => '',
            'penerima_active'   => '',
            'user'              => session('user_username'),
            'semester'          => $semester,
            'kelompok_ukt'      => $kel_ukt,
            'list_dispensasi'   => $list_dispensasi,
            'pengajuan'         => $pengajuan,
            'badges'            => $badges
        ];

        return view('verifikasi_dispensasi', $arrData);
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
        $id = $request->id;
        $kelayakan = $request->sellayak;
        $alasan = $request->txtAlasan;

        $pengalihan = $request->pengalihan;
        if ($pengalihan == '1') {
            $awal_pengajuan = $request->jenis_dispensasi_awal;
            $jenis_dispensasi = $request->jenis_dispensasi_peralihan;
        } else {
            $awal_pengajuan = 0;
            $jenis_dispensasi = $request->jenis_dispensasi_awal;
        }
        if ($request->kelompok_ukt) {
            $kel_ukt_baru = $request->kelompok_ukt;
        } else {
            $kel_ukt_baru = 0;
        }

        $potongan     = $request->potongan;
        $ditagihkan   = $request->nominal;
        $angsuran1    = $request->angsuran1;
        $angsuran2    = $request->angsuran2;



        try {
            DB::beginTransaction();

            if ($kelayakan == '1') {
                $status_pengajuan = '1';
            } elseif ($kelayakan == '2') {
                $status_pengajuan = '21';
            } else {
                return redirect()->back()->with('toast_error', 'Belum Ada Pilihan Kelayakan Berkas Dokumen');
            }

            $store = PengajuanDispensasiUKTModel::where([
                'nim'    => $nim,
                'semester'  => $semester
            ])->update([
                'status_pengajuan'  => $status_pengajuan,
                'pengalihan'        => $request->pengalihan,
                'awal_pengajuan'    => $request->jenis_dispensasi_awal,
                'jenis_dispensasi'  => $jenis_dispensasi,
                'kel_ukt_baru'      => $kel_ukt_baru,
                'potongan'          => $potongan,
                'ditagihkan'        => $ditagihkan,
                'angsuran1'         => $angsuran1,
                'angsuran2'         => $angsuran2
            ]);

            if ($store) {
                HistoryPengajuan::updateOrCreate(
                    [
                        'id_pengajuan'      => $id,
                        'v_mode'            => trim(session('user_cmode'))
                    ],
                    [
                        'alasan_verif'      => $alasan,
                        'status_ajuan'      => $status_pengajuan
                    ]
                );
            }

            DB::commit();
            return redirect()->route('verifikasi_dispensasi.index')->with('toast_success', 'Verifikasi Kelayakan Pengajuan Dispensasi berhasil');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('verifikasi_dispensasi.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function detil($id)
    {

        $data = PengajuanDispensasiUKTModel::where('id', $id)->first();

        if (isset($data->jenis_dispensasi)) {
            $data->nom_ukt = number_format($data->nominal_ukt, 0);
            $data->jenis = DB::table('ref_jenisdipensasi')->where('id', $data->jenis_dispensasi)->first()->jenis_dispensasi;
            $data->status = DB::table('ref_status_pengajuan')->where('id', $data->status_pengajuan)->first()->status_ajuan;
            $data->kelompok = DB::table('ref_kelompok_ukt')->where('id', $data->kelompok_ukt)->first()->kelompok;

            $data->file_pendukung = "<a href = " . asset('storage/' . $data->file_pernyataan) . " target='_blank'>File Pernyataan Kebenaran</a>";

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
            } else {
                $data->alasan_verif = "";
            }

            //get data siakad
            $url = env('SIAKAD_URI') . "/dataMahasiswa/" . $data->nim . "/" . session('user_token');
            $response = Http::get($url);
            $dataMhs = json_decode($response);

            if ($dataMhs->status == true) {
                foreach ($dataMhs->isi as $mhs) {
                    $data->nim_siakad = $mhs->nim;
                    $data->nama_siakad = $mhs->namaLengkap;
                    $data->prodi_siakad = $mhs->jenjangProdi." ".$mhs->namaProdi;
                    $data->angkatan_siakad = $mhs->angkatan;
                    $data->kontak_siakad = $mhs->hpm." / ".$mhs->email;
                    $data->alamat_siakad = $mhs->alamat." RT. ".$mhs->rt." RW.".$mhs->rw."<br/> Kelurahan ".$mhs->lurah."<br/>  ".$mhs->namaKecamatan."<br/>  ".$mhs->namaKabkot."<br/>  ".$mhs->namaPropinsi." Kode pos ".$mhs->kdpos;
                    $data->nom_ukt_siakad = number_format($mhs->biayaKuliah,0);

                }
            } else {
                $data->nim_siakad = "<i class='fas fa-x'></i>";
                $data->nama_siakad = "<i class='fas fa-x'></i>";
                $data->prodi_siakad = "<i class='fas fa-x'></i>";
                $data->angkatan_siakad = "";
                $data->kontak_siakad = "<i class='fas fa-x'></i>";
                $data->alamat_siakad = "<i class='fas fa-x/'></i>";
                $data->nom_ukt_siakad = "<i class='fas fa-x'></i>";
            }
        }
        return json_encode($data);
    }
    public function dataukt($prodi, $angkatan)
    {
        // return $angkatan;
        $data = array();
        $arrDataUKT = DataUKT::where('kode_prodi', $prodi)->where('angkatan', $angkatan)->first();

        if (isset($arrDataUKT->kode_prodi)) {
            $data['status'] = true;
            $data['ukt_1'] = $arrDataUKT->ukt_1;
            $data['ukt_2'] = $arrDataUKT->ukt_2;
            $data['ukt_3'] = $arrDataUKT->ukt_3;
            $data['ukt_4'] = $arrDataUKT->ukt_4;
            $data['ukt_5'] = $arrDataUKT->ukt_5;
            $data['ukt_6'] = $arrDataUKT->ukt_6;
            $data['ukt_7'] = $arrDataUKT->ukt_7;
            $data['ukt_8'] = $arrDataUKT->ukt_8;
            $data['ukt_beasiswa'] = $arrDataUKT->ukt_beasiswa;
            $data['ukt_kerjasama'] = $arrDataUKT->ukt_kerjasama;
        } else {
            $data['status'] = false;
            $data['pesan'] = 'Belum ada data di Data UKT, Mohon isi dahulu';
        }
        header('Content-type: application/json');
        return json_encode($data);
    }
}
