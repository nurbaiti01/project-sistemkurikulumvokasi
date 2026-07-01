<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BebanAjarDosen extends Model
{
    protected $table = 'beban_ajar_dosens';

    protected $fillable = [
        'dosen_id',
        'matakuliah_id',
        'taught_prodi_id',
        'home_prodi_id',
        'kelas',
        'tahun_ajaran',
        'semester',
        'peran',
        'sks_beban',
    ];

    protected $casts = [
        'sks_beban' => 'float',
    ];

    // ======================
    // RELATIONS
    // ======================

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    public function taughtProdi()
    {
        return $this->belongsTo(ProgramStudi::class, 'taught_prodi_id');
    }

    public function homeProdi()
    {
        return $this->belongsTo(ProgramStudi::class, 'home_prodi_id');
    }

    // ======================
    // ATTRIBUTES
    // ======================

    public function getIsLintasProdiAttribute(): bool
    {
        return $this->home_prodi_id !== $this->taught_prodi_id;
    }

    // ======================
    // SCOPES (QUERY HELPERS)
    // ======================

    // Filter tahun & semester
    public function scopePeriode(Builder $query, string $tahunAjaran, string $semester)
    {
        return $query
            ->where('tahun_ajaran', $tahunAjaran)
            ->where('semester', $semester);
    }

    // Beban ajar dosen tertentu
    public function scopeByDosen(Builder $query, int $dosenId)
    {
        return $query->where('dosen_id', $dosenId);
    }

    // Team teaching (MK + kelas yg >1 dosen)
    public function scopeTeamTeaching(Builder $query)
    {
        return $query->select('matakuliah_id', 'kelas', 'taught_prodi_id')
            ->groupBy('matakuliah_id', 'kelas', 'taught_prodi_id')
            ->havingRaw('COUNT(*) > 1');
    }

    // Hanya lintas prodi
    public function scopeLintasProdi(Builder $query)
    {
        return $query->whereColumn('home_prodi_id', '!=', 'taught_prodi_id');
    }
}
