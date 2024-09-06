<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefJenisDispensasiModel extends Model
{
    use HasFactory;

    protected $table = 'ref_jenis_dispensasi';

    protected $quarded = ['id'];

    protected $fillable = ['id','jenis_dispensasi','presentasi_potongan','keterangan_dispensasi','aktif'];

}
