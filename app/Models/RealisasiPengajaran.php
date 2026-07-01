<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaran extends Model
{
    protected $table = 'realisasi_pengajarans';

    protected $fillable = [
        'program_studi_id',
        'matakuliah_id',
        'dosen_id',
        'semester',
        'tahun_akademik',
        'jumlah_sks',
        'tujuan_instruksional_umum',
        'kelas',
        'status'
    ];

    protected $casts = [
        'jumlah_sks' => 'integer',
    ];

    /**
     * ====================
     * RELATIONSHIPS
     * ====================
     */

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function details()
    {
        return $this->hasMany(RealisasiPengajaranDetail::class,'realisasi_id');
    }

    public function metodes()
    {
        return $this->hasMany(RealisasiPengajaranMetode::class,'realisasi_id');
    }

    public function evaluasis()
    {
        return $this->hasOne(RealisasiPengajaranEvaluasi::class,'realisasi_id');
    }

    public function referensis()
    {
        return $this->hasMany(RealisasiPengajaranReferensi::class,'realisasi_id');
    }

    public function approvals()
    {
        return $this->hasMany(RealisasiPengajaranApproval::class,'realisasi_id');
    }


}
