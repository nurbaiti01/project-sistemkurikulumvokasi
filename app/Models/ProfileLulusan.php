<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class ProfileLulusan extends Model
{
    use HasFactory;
    protected $table = 'profile_lulusans';

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
     * Lebih aman & readable karena code unique
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    /**
     * Scope pencarian (admin / livewire / api)
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
            'tx_pl_prodis',
            'pl_id',
            'prodi_id'
        )->using(TxPlProdi::class)
        ->withTimestamps();
    }
}
