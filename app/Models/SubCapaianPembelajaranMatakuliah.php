<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCapaianPembelajaranMatakuliah extends Model
{
    protected $table = 'sub_capaian_pembelajaran_matakuliahs';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'code',
        'description',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'description' => 'string',
    ];

    /**
     * Route model binding menggunakan code
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
            ->orWhere('description', 'like', "%{$keyword}%");
    }

    public function programStudis()
    {
        return $this->belongsToMany(
            ProgramStudi::class,
            'tx_sub_cpmk_prodis',
            'sub_cpmk_id',
            'prodi_id'
        )->using(TxSubCpmkProdi::class)
            ->withTimestamps();
    }
}
