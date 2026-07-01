<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsPenilaian extends Model
{
    protected $fillable = [
        'rps_id',
        'jenis_penilaian',
        'cpmk_id',
        'persentase_penilaian',
        'bobot_cpmk',
        'kelompok',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class);
    }
}
