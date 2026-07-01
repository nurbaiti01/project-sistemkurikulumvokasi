<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotCplBk extends Pivot
{
    protected $table = 'pivot_cpl_bks';

    protected $fillable = [
        'kurikulum_id',
        'cpl_id',
        'bk_id',
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
    public function bk()
    {
        return $this->belongsTo(BahanKajian::class, 'bk_id');
    }
}
