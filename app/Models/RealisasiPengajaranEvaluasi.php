<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaranEvaluasi extends Model
{
    protected $table = 'realisasi_pengajaran_evaluasis';

    protected $fillable = [
        'realisasi_id',
        'tugas_persen',
        'kuis_persen',
        'ujian_persen',
    ];

    protected $casts = [
        'tugas_persen' => 'decimal:2',
        'kuis_persen' => 'decimal:2',
        'ujian_persen' => 'decimal:2',
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
     * ACCESSORS
     * ====================
     */

    public function getTotalPersenAttribute(): float
    {
        return (float) (
            $this->tugas_persen +
            $this->kuis_persen +
            $this->ujian_persen
        );
    }

    /**
     * ====================
     * HELPERS
     * ====================
     */

    public function isValid(): bool
    {
        return $this->total_persen === 100.00;
    }
}
