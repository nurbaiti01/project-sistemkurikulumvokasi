<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class TxPlProdi extends Pivot
{
    protected $table = 'tx_pl_prodis';

    protected $fillable = [
        'pl_id',
        'prodi_id',
    ];

    /**
     * Jika pivot punya timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke Profile Lulusan
     */
    public function profileLulusan()
    {
        return $this->belongsTo(ProfileLulusan::class, 'pl_id');
    }

    /**
     * Relasi ke Program Studi
     */
    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }
}
