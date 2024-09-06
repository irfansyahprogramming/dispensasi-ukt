<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class PengajuanDispensasiUKTModel extends Model
{
    // use HasFactory;

    protected $table='tb_pengajuan_dispensasi';

    protected $quarded = ['id'];

    protected $fillable = ['id','semester','jenis_dispensasi', 'nim', 'nama', 'kode_prodi', 'nama_prodi', 'jenjang_prodi', 'alamat', 'no_hp', 'email', 'kelompok_ukt', 'nominal_ukt', 'pekerjaan', 'jabatan_kerja', 'status_pengajuan','semesterke', 'sks_belum','file_pernyataan','file_keterangan','file_penghasilan','file_phk','file_pailit','file_pratranskrip'];

    public function histories()
    {
        return $this->hasMany(HistoryPengajuan::class,'id_pengajuan','id');
    }
    public function kelompok()
    {
        return $this->hasOne(RefKelompokModel::class,'kelompok_ukt','id');
    }
    public function dispensasi()
    {
        return $this->hasOne(RefJenisDispensasiModel::class,'jenis_dispensasi','id');
    }
}
