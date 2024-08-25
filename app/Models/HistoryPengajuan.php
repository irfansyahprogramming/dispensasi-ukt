<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPengajuan extends Model
{
    use HasFactory;

    protected $table='tr_history_pengajuan';
    
    public $timestamps = false;

    protected $quarded = ['id','id_pengajuan','created_at', 'updated_at'];

    protected $fillable = ['id_pengajuan','v_mode','status_ajuan','status_pengajuan','alasan_verif'];

    // public function Ajuan(){
    //     return $this->belongsTo(Ajuan::class)->withDefault();
    // }
}
