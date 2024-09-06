<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Helpers\Services;
use App\Models\BukaDispensasi;
use App\Models\DataUKT;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DataUKTController extends Controller
{
    public function index()
    {
        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }
        $user = session('user_name');
        $mode = session('user_mode');

        // get mengajar from siakad
        $url = "http://103.8.12.212:36880/siakad_api/api/as400/programStudi/" . trim(session('user_unit'));
        //echo $url;
        $response = Http::get($url);
        $listProdi = json_decode($response);

        $listUkt = DB::table('data_ukt')
            ->where('kode_prodi', 'like', trim(session('user_unit')) . '%')
            ->get();

        $periode = BukaDispensasi::where('aktif', '1')->first();
        if ($periode) {
            $tombol = "";
            $semester = $periode->semester;
        } else {
            $tombol = "disabled";
            $semester = "";
        }
        $badges = Functions::pengajuan($semester);

        foreach ($listUkt as $ukt) {

            // get mengajar from siakad
            $getDataProdi = Services::getProdi(trim($ukt->kode_prodi));
            // @dd($getDataProdi['isi'][0]);
            if (!isset($getDataProdi['isi'])){
                $ukt->namaprodi = $ukt->kode_prodi;
            }else{
                $arrProdi = $getDataProdi['isi'][0];
                $ukt->namaprodi = $arrProdi['namaProdi'];
            }
            
            
            // $namaProdi = $getDataProdi['isi'][0]['jenjangProdi']. " ".$getDataProdi['isi'][0]['namaProdi'];
            // $url = "http://103.8.12.212:36880/siakad_api/api/as400/programStudi/" . trim($ukt->kode_prodi);
            // //echo $url;
            // $response = Http::get($url);
            // $kdprodi = json_decode($response);
            // foreach ($kdprodi->isi as $kd) {
            //     $ukt->namaprodi = $kd->jenjangProdi . " " . $kd->namaProdi;
            // }
            
        }

        $arrUKT = array(
            'title'             => 'Dispensasi',
            'active'            => 'Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Database UKT',
            'home_active'       => '',
            'dispen_active'     => '',
            'dataukt_active'    => 'active',
            'laporan_active'    => '',
            'penerima_active'   => '',
            'user'              => session('user_username'),
            'list_ukt'          => $listUkt,
            'listProdi'         => $listProdi,
            'badges'            => $badges
        );
        return view('ukt.dataukt', $arrUKT);
    }

    public function delete($id)
    {
        $data = DataUKT::findOrFail($id);

        $data->delete();

        return redirect()->back()->with('toast_success', 'Data telah dihapus')->with('dispen_active', 'active');
    }

    public function simpan(Request $request)
    {
        //print_r($request->all());
        $credentials = $request->validate([
            'kode_prodi'          => ['required'],
            'angkatan'            => ['required'],
            'ukt_1'               => ['required'],
            'ukt_2'               => ['required'],
            'ukt_3'               => ['required'],
            'ukt_4'               => ['required'],
            'ukt_5'               => ['required'],
            'ukt_6'               => ['required'],
            'ukt_7'               => ['required'],
            'ukt_8'               => ['required'],
            'spp_awal'            => ['required'],
            'spp_lanjut'          => ['required'],
        ]);

        try {
            DB::beginTransaction();

            DataUKT::updateOrCreate(
                [
                    'kode_prodi'    => $request->kode_prodi,
                    'angkatan'      => $request->angkatan
                ],
                [
                    'ukt_1'         => $request->ukt_1,
                    'ukt_2'         => $request->ukt_2,
                    'ukt_3'         => $request->ukt_3,
                    'ukt_4'         => $request->ukt_4,
                    'ukt_5'         => $request->ukt_5,
                    'ukt_6'         => $request->ukt_6,
                    'ukt_7'         => $request->ukt_7,
                    'ukt_8'         => $request->ukt_8,
                    'ukt_beasiswa'  => $request->ukt_beasiswa,
                    'ukt_kerjasama' => $request->ukt_kerjasama,
                    'spp_awal'      => $request->spp_awal,
                    'spp_lanjut'    => $request->spp_lanjut
                ]
            );

            DB::commit();
            return redirect()->route('dataUKT.index')->with('toast_success', 'Simpan Data UKT berhasil');
        } catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('dataUKT.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function edit($id)
    {
        $data = DataUKT::findOrFail($id);
        return json_encode($data);
    }
}
