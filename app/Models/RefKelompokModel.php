<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RefKelompokModel extends Model
{
    use HasFactory;

    protected $table = 'ref_kelompok_ukt';

    protected $quarded = ['id'];

    protected $fillable = ['id','kelompok','keterangan'];

    
}