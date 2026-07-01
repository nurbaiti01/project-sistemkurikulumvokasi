<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiPengajaranApproval extends Model
{
    protected $table = 'realisasi_pengajaran_approvals';

    protected $fillable = [
        'realisasi_id',
        'dosen_id',
        'role_proses',
        'status',
        'approved',
        'catatan',
        'approved_at',
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'date',
    ];

    /**
     * ====================
     * RELATIONSHIPS
     * ====================
     */

    public function realisasiPengajaran()
    {
        return $this->belongsTo(RealisasiPengajaran::class, 'realisasi_id', 'id');
    }

    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }

    /**
     * ====================
     * CONSTANTS
     * ====================
     */

    // Role Proses
    public const ROLE_PERUMUSAN = 'perumusan';
    public const ROLE_PEMERIKSAAN = 'pemeriksaan';

    public const ROLES = [
        self::ROLE_PERUMUSAN,
        self::ROLE_PEMERIKSAAN,
    ];

    // Status
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    /**
     * ====================
     * SCOPES
     * ====================
     */

    public function scopeRole($query, string $role)
    {
        return $query->where('role_proses', $role);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * ====================
     * HELPERS / BUSINESS LOGIC
     * ====================
     */

    public function approve(?int $dosenId = null): void
    {
        $this->update([
            'dosen_id' => $dosenId ?? auth()->user()?->dosenId(),
            'status' => self::STATUS_APPROVED,
            'approved' => true,
            'approved_at' => now(),
        ]);
    }

    public function reject(?string $catatan = null): void
    {
        $this->update([
            'status' => self::STATUS_REJECTED,
            'approved' => false,
            'catatan' => $catatan,
            'approved_at' => now(),
        ]);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
}
