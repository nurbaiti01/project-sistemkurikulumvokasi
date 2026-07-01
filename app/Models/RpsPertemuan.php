<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsPertemuan extends Model
{
    protected $fillable = [
        'rps_id',
        'pertemuan_ke',
        'materi_ajar',
        'indikator',
        'bentuk_pembelajaran',
        'cpmk_id',
        'pemberian_tugas',
        'alokasi',
        'bobots',
        'rancangan_penilaian',
    ];

    protected $casts = [
        'alokasi' => 'array',
        'bobots' => 'array',
        'rancangan_penilaian' => 'array',
        'pemberian_tugas' => 'boolean',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class);
    }
}
