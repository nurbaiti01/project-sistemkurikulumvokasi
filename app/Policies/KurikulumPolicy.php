<?php

namespace App\Policies;

use App\Models\Kurikulum;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KurikulumPolicy extends BasePolicy
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

    public function submitted(User $user, Kurikulum $kurikulum, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }

    public function approval(User $user, Kurikulum $kurikulum, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }

    public function revisi(User $user, Kurikulum $kurikulum, array $blockRoles = []): bool
    {
        return $this->allowOnlyIfInBlockOrDefault($blockRoles);
    }
}
