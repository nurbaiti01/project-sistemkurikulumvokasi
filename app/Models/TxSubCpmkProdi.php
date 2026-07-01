<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TxSubCpmkProdi extends Pivot
{
    protected $table = 'tx_sub_cpmk_prodis';

    protected $fillable = [
        'sub_cpmk_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Sub-CPMK
     */
    public function subCapaianPembelajaranMatakuliah()
    {
        return $this->belongsTo(
            SubCapaianPembelajaranMatakuliah::class,
            'sub_cpmk_id'
        );
    }

    /**
     * Relasi ke Program Studi
     */
    public function programStudi()
    {
        return $this->belongsTo(
            ProgramStudi::class,
            'prodi_id'
        );
    }
}
