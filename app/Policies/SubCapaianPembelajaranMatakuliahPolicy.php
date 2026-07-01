<?php

namespace App\Policies;

use App\Models\SubCapaianPembelajaranMatakuliah;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubCapaianPembelajaranMatakuliahPolicy extends BasePolicy
{
    protected array $allowedRoles = [
        'Superadmin',
        'Akademik',
        'Direktur',
        'WADIR 1',
        'BPM',
        'Kaprodi',
        'Dosen'
    ];

    public function filter($user, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function create($user, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }

    public function update($user, $data, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }

    public function delete($user, $data, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }
}
