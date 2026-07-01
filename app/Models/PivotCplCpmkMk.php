<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotCplCpmkMk extends Pivot
{
    protected $table = 'pivot_cpl_cpmk_mks';

    protected $fillable = [
        'kurikulum_id',
        'cpl_id',
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
     * Relasi ke CPL
     */
    public function cpl()
    {
        return $this->belongsTo(CapaianPembelajaranLulusan::class, 'cpl_id');
    }

    /**
     * Relasi ke MK
     */
    public function mk()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }

    /**
     * Relasi ke CPMK
     */
    public function cpmk()
    {
        return $this->belongsTo(CapaianPembelajaranMatakuliah::class, 'cpmk_id');
    }
}
