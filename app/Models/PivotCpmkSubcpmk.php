<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Model;
class PivotCpmkSubcpmk extends Pivot
{
    protected $table = 'pivot_cpmk_sub_cpmks';

    protected $fillable = [
        'kurikulum_id',
        'cpmk_id',
        'subcpmk_id',
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
     * Relasi ke SubCPMK
     */
    public function subcpmk()
    {
        return $this->belongsTo(SubCapaianPembelajaranMatakuliah::class, 'subcpmk_id');
    }
}
