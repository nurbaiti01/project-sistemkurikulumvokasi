<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class TxDosenProdi extends Pivot
{
    protected $table = 'tx_dosen_prodis';

    protected $fillable = [
        'dosen_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(
            Dosen::class,
            'dosen_id'
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
