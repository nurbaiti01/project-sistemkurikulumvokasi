<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BahanKajian extends Model
{
    protected $table = 'bahan_kajians';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'code',
        'name',
        'description',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
    ];

    /**
     * Route model binding menggunakan code
     * Cocok untuk API & URL readable
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Scope pencarian (Admin / Livewire / API)
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('code', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%");
    }

    public function programStudis()
    {
        return $this->belongsToMany(
            ProgramStudi::class,
            'tx_bk_prodis',
            'bk_id',
            'prodi_id'
        )->using(TxBkProdi::class)
            ->withTimestamps();
    }
}
