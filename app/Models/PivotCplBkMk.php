<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotCplBkMk extends Pivot
{
    protected $table = 'pivot_cpl_bk_mks';

    protected $fillable = [
        'kurikulum_id',
        'cpl_id',
        'bk_id',
        'mk_id',
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
     * Relasi ke BK
     */
    public function bahanKajian()
    {
        return $this->belongsTo(BahanKajian::class, 'bk_id');
    }

    /**
     * Relasi ke MK
     */
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }
}
