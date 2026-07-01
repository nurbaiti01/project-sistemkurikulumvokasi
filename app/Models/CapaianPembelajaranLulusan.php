<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaranLulusan extends Model
{
    protected $table = 'capaian_pembelajaran_lulusans';

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
     * Route model binding by code (optional but recommended)
     */
    public function getRouteKeyName(): string
    {
        return 'code';
    }

    public function programStudis()
    {
        return $this->belongsToMany(
            ProgramStudi::class,
            'tx_cpl_prodis',
            'cpl_id',
            'prodi_id'
        )->using(TxCplProdi::class)
            ->withTimestamps();
    }


}
