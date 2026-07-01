<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
class TxMkProdi extends Pivot
{
    protected $table = 'tx_mk_prodis';

    protected $fillable = [
        'mk_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Mata Kuliah
     */
    public function mataKuliah()
    {
        return $this->belongsTo(
            MataKuliah::class,
            'mk_id'
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
