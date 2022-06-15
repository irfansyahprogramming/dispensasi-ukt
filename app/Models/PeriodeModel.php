<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeModel extends Model
{
    use HasFactory;
    protected $table='ref_periode';
    
    public $timestamps = false;

    protected $quarded = ['id','created_at', 'updated_at'];

    protected $fillable = ['semester','des_semester','start_date','end_date','aktif'];
}
