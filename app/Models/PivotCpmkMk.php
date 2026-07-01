<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotCpmkMk extends Pivot
{
    protected $table = 'pivot_cpmk_mks';

    protected $fillable = [
        'kurikulum_id',
        'mk_id',
        'cpmk_id',
    ];

    /**
     * Relasi ke Kurikulum
     */
    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    /**
     * Relasi ke CPMK
     */
    public function cpmk()
    {
        return $this->belongsTo(CapaianPembelajaranMatakuliah::class, 'cpmk_id');
    }

    /**
     * Relasi ke Matakuliah
     */
    public function mk()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }
}
