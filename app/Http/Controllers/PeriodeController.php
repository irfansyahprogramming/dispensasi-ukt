<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use App\Models\PeriodeModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PeriodeController extends Controller
{
    public function index()
    {

        if(!Session::has('isLoggedIn')){
            return redirect()->to('login');
        }
        
        $user = session ('user_name');
        $mode = session ('user_mode');
        $cmode = session ('user_cmode');
                
        $periode = DB::table('ref_periode')->get();
        
        $aktifPeriode = BukaDispensasi::where('aktif','1')->first();
        if ($aktifPeriode){
            $semester = $aktifPeriode->semester;
        }else{
            $semester = "";
        }
        // $badges = Functions::pengajuan($semester);

        $arrData = [
            'title'             => 'Dispensasi',
            'active'            => 'Periode Pengajuan Dispensasi UKT',
            'user'              => $user,
            'mode'              => $mode,
            'subtitle'          => 'Periode',
            'home_active'       => '',
            'dataukt_active'    => '',
            'dispen_active'     => '',
            'laporan_active'    => '',
            'periode_active'    => 'active',
            'penerima_active'   => '',
            'user'              => session('user_username'),
            'periode'           => $periode
        ];
        // return $arrData;
        return view('periode.index',$arrData);
    }
    public function aktifin (Request $request){
        $id_periode = $request->id_periode;
        $status_aktif = ($request->aktifCheck == '1')? '0':'1';
        
        try {
            DB::beginTransaction();
            PeriodeModel::where([
                'id'        => $id_periode
            ])->update([
                'aktif'  => $status_aktif
            ]);

            DB::commit();
            if ($status_aktif == '1'){
                return redirect()->route('periode.index')->with('toast_success', 'Aktif Semester berhasil');
            }else{
                return redirect()->route('periode.index')->with('toast_success', 'Unaktif Semester berhasil');
            }
            

        }catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('periode.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function simpan (Request $request){
        $credentials = $request->validate([
            'semester'          => ['required'],
            'des_semester'      => ['required'],
            'start_date'        => ['required','date'],
            'end_date'          => ['required','date']
        ]);

        $semester = $request->semester;
        $des_semester = $request->des_semester;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        if (!isset($request->aktif) || $request->aktif == "off"){
            $aktif = 0;    
        }else{
            $status_aktif = $request->aktif;
            if ($request->aktif == "on"){
                $aktif = "1";
            }else{
                $aktif = "0";
            }
        }
        
        $start = date("Y-m-d H:i:s", strtotime($start_date));
        $end = date("Y-m-d H:i:s", strtotime($end_date));
        
        try {
            DB::beginTransaction();
            
            PeriodeModel::updateOrCreate (
                [
                    'semester'          => $semester
                ],
                [
                    'semester'      => $semester,
                    'des_semester'  => $des_semester,
                    'start_date'    => $start,
                    'end_date'      => $end,
                    'aktif'         => $aktif
                ]
            );
            
            DB::commit();
            return redirect()->route('periode.index')->with('toast_success', 'Simpan Semester berhasil');

        }catch (Exception $ex) {
            DB::rollBack();
            return redirect()->route('periode.index')->with('toast_error', 'Error : ' . $ex->getMessage());
        }
    }

    public function edit($id){
        $data = PeriodeModel::findOrFail($id);
        // $data->start = date("d/m/y H:i A",$data->start_date);
        // $data->end = date("d/m/y H:i A",$data->end_date);
        
        return json_encode($data);
    }

    public function delete($id){
        $data = PeriodeModel::findOrFail($id);

        $data->delete();

        return redirect()->back()->with('toast_success', 'Data telah dihapus')->with('dispen_active', 'active');
    }
}
