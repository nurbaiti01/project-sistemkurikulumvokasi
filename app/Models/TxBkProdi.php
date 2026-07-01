<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class TxBkProdi extends Pivot
{
    protected $table = 'tx_bk_prodis';

    protected $fillable = [
        'bk_id',
        'prodi_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Bahan Kajian
     */
    public function bahanKajian()
    {
        return $this->belongsTo(BahanKajian::class, 'bk_id');
    }

    /**
     * Relasi ke Program Studi
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
