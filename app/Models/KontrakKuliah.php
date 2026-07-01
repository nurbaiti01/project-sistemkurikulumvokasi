<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakKuliah extends Model
{
    protected $table = 'kontrak_kuliahs';

    protected $fillable = [
        'matakuliah_id',
        'prodi_id',
        'dosen_id',
        'tahun_akademik',
        'kelas',
        'total_jam',
        'tujuan_pembelajaran',
        'strategi_perkuliahan',
        'materi_pembelajaran',
        'kriteria_penilaian',
        'tata_tertib',
        'status'
    ];

    /**
     * Relasi ke tabel Matakuliah
     */
    public function matakuliah()
    {
        return $this->belongsTo(Matakuliah::class);
    }

    /**
     * Relasi ke tabel Dosen
     */
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * Relasi ke tabel ProgramStudi
     */
    public function programStudis()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    public function kontrakApprovals()
    {
        return $this->hasMany(KontrakKuliahApproval::class);
    }
}
