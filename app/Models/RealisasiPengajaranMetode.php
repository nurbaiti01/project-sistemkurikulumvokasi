<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaranMetode extends Model
{
    protected $table = 'realisasi_pengajaran_metodes';

    protected $fillable = [
        'realisasi_id',
        'jenis',
        'jam',
    ];

    protected $casts = [
        'jam' => 'integer',
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

    /**
     * ====================
     * CONSTANTS
     * ====================
     */

    public const JENIS_KULIAH = 'kuliah';
    public const JENIS_TUTORIAL = 'tutorial';
    public const JENIS_LABORATORIUM = 'laboratorium';

    public const JENIS = [
        self::JENIS_KULIAH,
        self::JENIS_TUTORIAL,
        self::JENIS_LABORATORIUM,
    ];
}
