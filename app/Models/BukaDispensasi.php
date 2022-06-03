<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukaDispensasi extends Model
{
    protected $table = 'ref_periode';

    public $timestamps = false;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    public static function checkOpenPeriode()
    {
        $current_time =  date('Y-m-d H:i:s');
        $data = self::where('start_date', '<=', $current_time)
            ->where('end_date', '>=', $current_time)
            ->first();

        return $data;
    }

}
