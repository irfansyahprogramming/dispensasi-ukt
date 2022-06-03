<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    public function index(){
        
        if(!Session::has('isLoggedIn')){
            return redirect()->to('login');
        }

        $user = session ('user_name');
        $mode = session ('user_mode');
        
        $arrData = [
            'title'         => 'Home',
            'active'        => 'home',
            'user'          => $user,
            'mode'          => $mode,
            'subtitle'      => 'Dashboard',
            'home_active'   => 'active',
            'dispen_active' => '',
            'penerima_active'   => '',
            'laporan_active' => ''
            // 'posts' => Post::All()
            //'posts' => Post::latest()->get()
        ];
        
        return view('home',$arrData);
    }

}
