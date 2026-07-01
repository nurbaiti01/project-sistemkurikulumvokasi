<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosens';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'nrp',
        'nidn',
        'name',
        'email',
        'gender',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'nrp' => 'string',
        'nidn' => 'string',
        'name' => 'string',
        'email' => 'string',
        'gender' => 'string',
    ];

    /**
     * Route model binding menggunakan NRP
     * Cocok karena unique & lebih natural
     */
    public function getRouteKeyName(): string
    {
        return 'nrp';
    }

    /**
     * Enum-like constants untuk gender
     */
    public const GENDER_LAKI_LAKI = 'Laki-laki';
    public const GENDER_PEREMPUAN = 'Perempuan';

    /**
     * Accessor label gender (optional, future-proof)
     */
    protected function genderLabel(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn() => $this->gender
        );
    }

    /**
     * Scope pencarian (Admin / Livewire / API)
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('nrp', 'like', "%{$keyword}%")
            ->orWhere('nidn', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%");
    }

    public function programStudis()
    {
        return $this->belongsToMany(
            ProgramStudi::class,
            'tx_dosen_prodis',
            'dosen_id',
            'prodi_id'
        )->using(TxDosenProdi::class)
            ->withTimestamps();
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'tx_user_dosens',
            'dosen_id',
            'user_id'
        )->using(TxUserDosen::class)
            ->withTimestamps();
    }
}
