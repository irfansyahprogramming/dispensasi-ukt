<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    protected $redirectsTo = '/login';

    public function index()
    {

        if (session('isLoggedIn') == true) {
            return redirect()->to('home');
        }
        return view('login');
    }

    public function attemptLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required']
        ]);

        $url = env('SIAKAD_URI') . "/as400/signin";
        $post_data = http_build_query(
            array(
                'username'  => $request->username,
                'password'  => $request->password
            )
        );
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $post_data
            )
        );
        
        $context  = stream_context_create($opts);
        // dd ($context);
		$response =file_get_contents($url,false, $context);
        // dd ($response);

        // $response = Http::asForm()->post($url, $credentials);
        $result = json_decode($response);
        if ($result->status == false) {
            $this->logout($request);
            $request->flash();
            return redirect()->to('login')->with('login_msg', 'Username atau Password salah');
        }

        $set_session = $this->setUserSession($result);
        
        if ($set_session) {
            return redirect()->to('home');
        } else {
            return redirect()->to('login')->with('login_msg', 'Gagal melakukan koneksi ke SIAKAD'.$url);
        }
    }
    protected function setUserSession($user)
    {
        // dd ($user);
        try {
            $akun = DB::table('ref_mode')->where('id', $user->mode)->first();
            $mode = $akun->mode;
            session([
                'user_name' => $user->nama,
                'user_username' => $user->username,
                'user_cmode' => $user->mode,
                'user_mode' => $mode,
                'user_sex' => $user->kelamin,
                'user_unit' => $user->unit,
                'user_token' => $user->Authorization,
                'isLoggedIn' => true,
            ]);

            return true;
        } catch (Exception $ex) {
            Log::info('User failed to login : ', ['username' => $user->username]);
            Log::debug($ex);
            return false;
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect($this->redirectsTo);
    }
}
