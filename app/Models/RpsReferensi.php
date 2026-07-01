<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsReferensi extends Model
{
    protected $fillable = [
        'rps_id',
        'jenis',
        'deskripsi',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class);
    }
}
