<?php

namespace App\Policies;

use App\Models\CapaianPembelajaranLulusan;
use App\Models\User;

class CapaianPembelajaranLulusanPolicy extends BasePolicy
{
    /**
     * Default allowed roles
     */
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

    public function update($user, $profileLulusan, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }

    public function delete($user, $profileLulusan, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }
}
