<?php

namespace App\Policies;

use App\Models\BebanAjarDosen;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BebanAjarDosenPolicy extends BasePolicy
{
    protected array $allowedRoles = [
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
