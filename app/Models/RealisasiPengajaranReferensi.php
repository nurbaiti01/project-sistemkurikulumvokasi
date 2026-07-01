<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaranReferensi extends Model
{
    protected $table = 'realisasi_pengajaran_referensis';

    protected $fillable = [
        'realisasi_id',
        'jenis',
        'judul',
        'penerbit',
    ];

    /**
     * ====================
     * CASTS
     * ====================
     */
    protected $casts = [];

    /**
     * ====================
     * RELATIONSHIPS
     * ====================
     */

    public function realisasiPengajaran()
    {
        return $this->belongsTo(RealisasiPengajaran::class, 'realisasi_id', 'id');
    }

    /**
     * ====================
     * CONSTANTS
     * ====================
     */

    public const JENIS_DIKTAT = 'diktat';
    public const JENIS_BUKU = 'buku';

    public const JENIS = [
        self::JENIS_DIKTAT,
        self::JENIS_BUKU,
    ];
}
