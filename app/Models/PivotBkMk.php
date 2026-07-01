<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PivotBkMk extends Pivot
{
    protected $table = 'pivot_bk_mks';

    protected $fillable = [
        'kurikulum_id',
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
     * Relasi ke BK
     */
    public function bk()
    {
        return $this->belongsTo(BahanKajian::class, 'bk_id');
    }

    /**
     * Relasi ke MK
     */
    public function mk()
    {
        return $this->belongsTo(Matakuliah::class, 'mk_id');
    }
}
