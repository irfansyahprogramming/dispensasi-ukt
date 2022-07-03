<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataUKT extends Model
{
    protected $table='data_ukt';

    protected $quarded = ['id'];

    protected $fillable = ['kode_prodi','angkatan', 'ukt_1', 'ukt_2', 'ukt_3', 'ukt_4', 'ukt_5', 'ukt_6', 'ukt_6', 'ukt_7', 'ukt_8', 'ukt_beasiswa', 'ukt_kerjasama'];

}
