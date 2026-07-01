<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KontrakKuliahApproval extends Model
{
    protected $table = 'kontrak_kuliah_approvals';

    protected $fillable = [
        'kontrak_kuliah_id',
        'dosen_id',
        'role_proses',
        'status',
        'approved',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
    ];

    public function kontrakKuliah()
    {
        return $this->belongsTo(KontrakKuliah::class);
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}
