<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KurikulumApproval extends Model
{
    protected $fillable = [
        'kurikulum_id',
        'role',
        'approved_by',
        'status',
        'note',
        'approved_at',
    ];

    public function kurikulum()
    {
        return $this->belongsTo(Kurikulum::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
