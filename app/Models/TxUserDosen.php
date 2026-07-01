<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class TxUserDosen extends Pivot
{
    protected $table = 'tx_user_dosens';

    protected $fillable = [
        'user_id',
        'dosen_id',
    ];

    /**
     * Pivot ini menggunakan timestamps
     */
    public $timestamps = true;

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'dosen_id');
    }
}
