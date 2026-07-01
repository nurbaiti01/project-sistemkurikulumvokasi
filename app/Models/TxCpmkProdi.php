<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
class TxCpmkProdi extends Pivot
{
    protected $table = 'tx_cpmk_prodis';

    protected $fillable = [
        'cpmk_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke CPMK
     */
    public function capaianPembelajaranMatakuliah()
    {
        return $this->belongsTo(
            CapaianPembelajaranMatakuliah::class,
            'cpmk_id'
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
