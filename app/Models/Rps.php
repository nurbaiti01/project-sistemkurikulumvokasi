<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rps extends Model
{
    protected $fillable = [
        'matakuliah_id',
        'program_studi_id',
        'class',
        'dosen_id',
        'academic_year',
        'revision',
        'cpmk_bobot',
        'learning_method',
        'learning_experience',
        'status',
    ];

    protected $casts = [
        'cpmk_bobot' => 'array',       
    ];

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function programStudi()
    {
        return $this->belongsTo(ProgramStudi::class);
    }

    public function pertemuans()
    {
        return $this->hasMany(RpsPertemuan::class);
    }

    public function referensis()
    {
        return $this->hasMany(RpsReferensi::class);
    }

    public function penilaians()
    {
        return $this->hasMany(RpsPenilaian::class);
    }

    public function rpsApprovals()
    {
        return $this->hasMany(RpsApproval::class);
    }
}
