<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefStatusPengajuanModel extends Model
{
    use HasFactory;

    protected $table = 'ref_status_pengajuan';

    protected $quarded = ['id'];

    protected $fillable = ['id','status_ajuan','keterangan'];

}
