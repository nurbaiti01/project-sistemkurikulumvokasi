<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaranDetail extends Model
{
    protected $table = 'realisasi_pengajaran_details';

    protected $fillable = [
        'realisasi_id',
        'pertemuan_ke',
        'tanggal',
        'pokok_bahasan',
        'jam',
        'paraf',
    ];

    protected $casts = [
        'pertemuan_ke' => 'integer',
        'tanggal' => 'date',
        'jam' => 'string',
        'paraf' => 'boolean',
    ];

    /**
     * ====================
     * RELATIONSHIPS
     * ====================
     */

    public function realisasiPengajaran()
    {
        return $this->belongsTo(RealisasiPengajaran::class, 'realisasi_id', 'id');
    }
}
