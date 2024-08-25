<?php 

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Services 
{
    public static function post($endpoint, $data = [])
    {
        $headers = [
            "Authorization" => session()->get("token")
        ];
        return Http::withHeaders($headers)->post(env("BACKEND_URL") . $endpoint, $data);
    }

    public static function get($endpoint, $data = [])
    {
        $headers = [
            "Authorization" => session()->get("token")
        ];
        return Http::withHeaders($headers)->get(env("BACKEND_URL") . $endpoint, $data);
    }

    public static function login($username, $password)
    {
        return Services::post("masuk", [
            "username" => $username,
            "password" => $password
        ]);
    }

    public static function getFromSiakad($endpoint, $data = [])
    {
        return Http::get(env("SIAKAD_URI") . $endpoint, $data);
    }

    public static function getPeriodeAktif()
    {
        return Services::getFromSiakad("/as400/semesterAktif/13");
    }

    public static function getAktifMahasiswa($smt,$nim,$token)
    {
        return Services::getFromSiakad("/as400/mahasiswaAktifPerSemester/$smt/$nim/$token");
    }

    public static function getDataMahasiswa($nim,$token)
    {
        return Services::getFromSiakad("/as400/dataMahasiswa/$nim/$token");
    }
    public static function getBeasiswa($nim,$smt,$token)
    {
        return Services::getFromSiakad("/as400/beasiswaMahasiswaPerSemester/$nim/$smt/$token");
    }
    
    public static function getMahasiswaPerProdi($prodi,$semester,$token)
    {
        return Services::getFromSiakad("/as400/dataMahasiswaPerProdi/$prodi/$semester/$token");
    }
    
    public static function getDataIPK($nim)
    {
        return Services::getFromSiakad("/as400/prestasiMahasiswa/$nim");
    }

    public static function getProdi($kode)
    {
        return Services::getFromSiakad("/as400/programStudi/$kode");
    }

    public static function getDosen($nidn,$token)
    {
        return Services::getFromSiakad("/as400/dataDosen/$nidn/$token");
    }
    public static function getDosenSeUNJ($nidn,$token)
    {
        return Services::getFromSiakad("/as400/dataDosenSeUNJ/$nidn/$token");
    }
    public static function getPenjadwalan($smt,$prodi,$token)
    {
        return Services::getFromSiakad("/as400/penjadwalan/$smt/$prodi/$token");
        // echo "/as400/penjadwalan/$smt/$prodi/$token";
    }
}