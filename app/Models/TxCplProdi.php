<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class TxCplProdi extends Pivot
{
    protected $table = 'tx_cpl_prodis';

    protected $fillable = [
        'cpl_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke CPL
     */
    public function capaianPembelajaranLulusan()
    {
        return $this->belongsTo(CapaianPembelajaranLulusan::class, 'cpl_id');
    }

    /**
     * Relasi ke Program Studi
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
