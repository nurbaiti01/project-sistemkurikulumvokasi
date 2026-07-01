<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RpsApproval extends Model
{
    protected $fillable = [
        'rps_id',
        'dosen_id',
        'status',
        'approved',
        'role_proses',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'date',
    ];

    public function rps()
    {
        return $this->belongsTo(Rps::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    // Helper untuk menampilkan nama proses dari role
    public function proses(): string
    {
        return match ($this->role_proses) {
            'Dosen' => 'Perumusan',
            'Kaprodi' => 'Pemeriksaan',
            'WADIR 1' => 'Persetujuan',
            'Direktur' => 'Penetapan',
            'BPM' => 'Pengendalian',
            default => 'Unknown',
        };
    }
}
