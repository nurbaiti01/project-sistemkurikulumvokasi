<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matakuliah extends Model
{
    protected $table = 'matakuliahs';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'code',
        'name',
        'sks',
        'semester',
        'jenis',
        'description',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'sks' => 'integer',
        'semester' => 'integer',
        'jenis' => 'string',
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
     * Enum helper (clean & readable)
     */
    public const JENIS_TEORI = 'T';
    public const JENIS_PRAKTIK = 'P';

    /**
     * Accessor label jenis (T / P → Teori / Praktik)
     */
    protected function jenisLabel(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => match ($this->jenis) {
                self::JENIS_TEORI => 'Teori',
                self::JENIS_PRAKTIK => 'Praktik',
                default => '-',
            }
        );
    }

    /**
     * Scope filter
     */
    public function scopeSemester($query, int $semester)
    {
        return $query->where('semester', $semester);
    }

    public function scopeJenis($query, string $jenis)
    {
        return $query->where('jenis', $jenis);
    }

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
            'tx_mk_prodis',
            'mk_id',
            'prodi_id'
        )->using(TxMkProdi::class)
            ->withTimestamps();
    }

    public function MkCpmk()
    {
        return $this->hasMany(PivotCpmkMk::class, 'mk_id', 'id')->with('cpmk');
    }

    public function MkCpl()
    {
        return $this->hasMany(PivotCplMk::class, 'mk_id', 'id')->with('cpl');
    }


}
