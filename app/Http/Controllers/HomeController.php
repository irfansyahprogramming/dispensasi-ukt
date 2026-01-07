<?php

namespace App\Http\Controllers;

use App\Helpers\Functions;
use App\Models\BukaDispensasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {

        if (!Session::has('isLoggedIn')) {
            return redirect()->to('login');
        }

        $user = session('user_name');
        $mode = session('user_mode');
        $cmode = session('user_cmode');
        
        // @dd($pengajuan);
        $arrData = [
            'title'         => 'Home',
            'active'        => 'home',
            'user'          => $user,
            'mode'          => $mode,
            'cmode'          => $cmode,
            'subtitle'      => 'Dashboard',
            'home_active'   => 'active',
            'periode_active' => '',
            'input_active'       => '',
            'dataukt_active'    => '',
            'dispen_active' => '',
            'penerima_active'   => '',
            'laporan_active' => '',
            'penerbitan_active' => ''
        ];

        return view('home', $arrData);
    }
}
