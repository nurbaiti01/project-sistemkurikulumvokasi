<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    protected $table = 'program_studis';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'code',
        'name',
        'jenjang',
        'singkatan',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'name' => 'string',
        'jenjang' => 'string',
        'singkatan' => 'string',
    ];

    /**
     * Route model binding menggunakan code
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Enum-like constants untuk jenjang
     */
    public const JENJANG_D2 = 'D2';
    public const JENJANG_D3 = 'D3';
    public const JENJANG_D4 = 'D4';

    /**
     * Accessor label jenjang
     */
    protected function jenjangLabel(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => match ($this->jenjang) {
                self::JENJANG_D2 => 'Diploma 2',
                self::JENJANG_D3 => 'Diploma 3',
                self::JENJANG_D4 => 'Diploma 4',
                default => '-',
            }
        );
    }

    /**
     * Scope pencarian
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('code', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->orWhere('singkatan', 'like', "%{$keyword}%");
    }

    public function profileLulusans()
    {
        return $this->belongsToMany(
            ProfileLulusan::class,
            'tx_pl_prodis',
            'prodi_id',
            'pl_id'
        )->using(TxPlProdi::class)
            ->withTimestamps();
    }

    public function capaianPembelajaranLulusans()
    {
        return $this->belongsToMany(
            CapaianPembelajaranLulusan::class,
            'tx_cpl_prodis',
            'prodi_id',
            'cpl_id'
        )->using(TxCplProdi::class)
            ->withTimestamps();
    }

    public function bahanKajians()
    {
        return $this->belongsToMany(
            BahanKajian::class,
            'tx_bk_prodis',
            'prodi_id',
            'bk_id'
        )->using(TxBkProdi::class)
            ->withTimestamps();
    }

    public function capaianPembelajaranMatakuliahs()
    {
        return $this->belongsToMany(
            CapaianPembelajaranMatakuliah::class,
            'tx_cpmk_prodis',
            'prodi_id',
            'cpmk_id'
        )->using(TxCpmkProdi::class)
            ->withTimestamps();
    }

    public function subCapaianPembelajaranMatakuliahs()
    {
        return $this->belongsToMany(
            SubCapaianPembelajaranMatakuliah::class,
            'tx_sub_cpmk_prodis',
            'prodi_id',
            'sub_cpmk_id'
        )->using(TxSubCpmkProdi::class)
            ->withTimestamps();
    }

    public function mataKuliahs()
    {
        return $this->belongsToMany(
            MataKuliah::class,
            'tx_mk_prodis',
            'prodi_id',
            'mk_id'
        )->using(TxMkProdi::class)
            ->withTimestamps();
    }

    public function dosens()
    {
        return $this->belongsToMany(
            Dosen::class,
            'tx_dosen_prodis',
            'prodi_id',
            'dosen_id'
        )->using(TxDosenProdi::class)
            ->withTimestamps();
    }
}
