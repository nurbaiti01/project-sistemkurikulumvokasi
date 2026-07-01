<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TxUserRole extends Model
{
    use HasFactory;

    protected $table = 'tx_user_roles';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke UserRole
    public function role()
    {
        return $this->belongsTo(UserRole::class, 'role_id');
    }
}
