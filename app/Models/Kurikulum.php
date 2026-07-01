<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProgramStudi;
use App\Models\User;
use App\Models\PivotPlCpl;
use App\Models\PivotCplBk;
use App\Models\PivotBkMk;
use App\Models\PivotCpmkSubcpmk;
use App\Models\PivotCplMk;
use App\Models\PivotCpmkMk;
use App\Models\PivotCplBkMk;
use App\Models\PivotCplCpmkMk;

class Kurikulum extends Model
{
    protected $table = 'kurikulums';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'prodi_id',
        'name',
        'year',
        'version',
        'parent_id',
        'type',
        'status',
        'created_by',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'year' => 'string',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /** Program Studi pemilik kurikulum */
    public function programStudis()
    {
        return $this->belongsTo(ProgramStudi::class, 'prodi_id');
    }

    /** User pembuat (Kaprodi / Admin Akademik) */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Kurikulum induk (versi sebelumnya) */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /** Daftar revisi dari kurikulum ini */
    public function revisions(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(KurikulumApproval::class);
    }

    public function bpmApproval()
    {
        return $this->hasOne(KurikulumApproval::class)
            ->where('role', 'bpm');
    }

    public function wadirApproval()
    {
        return $this->hasOne(KurikulumApproval::class)
            ->where('role', 'wadir');
    }

    public function direkturApproval()
    {
        return $this->hasOne(KurikulumApproval::class)
            ->where('role', 'direktur');
    }
    /*
    |--------------------------------------------------------------------------
    | PIVOT RELATIONS
    |--------------------------------------------------------------------------
    */

    public function pivotPlCpl(): HasMany
    {
        return $this->hasMany(PivotPlCpl::class, 'kurikulum_id');
    }

    public function pivotCplBk(): HasMany
    {
        return $this->hasMany(PivotCplBk::class, 'kurikulum_id');
    }

    public function pivotBkMk(): HasMany
    {
        return $this->hasMany(PivotBkMk::class, 'kurikulum_id');
    }

    public function pivotCpmkSubcpmk(): HasMany
    {
        return $this->hasMany(PivotCpmkSubcpmk::class, 'kurikulum_id');
    }

    public function pivotCplMk(): HasMany
    {
        return $this->hasMany(PivotCplMk::class, 'kurikulum_id');
    }

    public function pivotCpmkMk(): HasMany
    {
        return $this->hasMany(PivotCpmkMk::class, 'kurikulum_id');
    }

    public function pivotCplBkMk(): HasMany
    {
        return $this->hasMany(PivotCplBkMk::class, 'kurikulum_id');
    }

    public function pivotCplCpmkMk(): HasMany
    {
        return $this->hasMany(PivotCplCpmkMk::class, 'kurikulum_id');
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByProdi($query, int $prodiId)
    {
        return $query->where('prodi_id', $prodiId);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isRoot(): bool
    {
        return $this->parent_id === null;
    }

    public function isRevision(): bool
    {
        return $this->parent_id !== null;
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Buat versi revisi baru
     */
    public function createRevision(
        string $type = 'minor_revision',
        ?int $userId = null
    ): self {
        $clone = self::create([
            'prodi_id' => $this->prodi_id,
            'name' => $this->name,
            'year' => $this->year,
            'version' => $this->version,
            'parent_id' => $this->id,
            'type' => $type,
            'status' => 'draft',
            'created_by' => $userId ?? auth()->id(),
        ]);

        // Clone semua pivot
        $this->pivotPlCpl->each(fn($p) => $clone->pivotPlCpl()->create($p->toArray()));
        $this->pivotCplBk->each(fn($p) => $clone->pivotCplBk()->create($p->toArray()));
        $this->pivotBkMk->each(fn($p) => $clone->pivotBkMk()->create($p->toArray()));
        $this->pivotCpmkSubcpmk->each(fn($p) => $clone->pivotCpmkSubcpmk()->create($p->toArray()));
        $this->pivotCplMk->each(fn($p) => $clone->pivotCplMk()->create($p->toArray()));
        $this->pivotCpmkMk->each(fn($p) => $clone->pivotCpmkMk()->create($p->toArray()));
        $this->pivotCplBkMk->each(fn($p) => $clone->pivotCplBkMk()->create($p->toArray()));
        $this->pivotCplCpmkMk->each(fn($p) => $clone->pivotCplCpmkMk()->create($p->toArray()));

        return $clone;
    }
}
