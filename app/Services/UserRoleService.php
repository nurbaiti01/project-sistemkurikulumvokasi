<?php

namespace App\Services;

use App\Models\User;
use App\Models\TxUserRole;
use Illuminate\Support\Facades\DB;

class UserRoleService
{
    /**
     * ======================
     * ROLE CONSTANTS
     * ======================
     * Sesuaikan ID dengan tabel user_roles
     */
    public const ROLE_DIREKTUR = 6;
    public const ROLE_WADIR   = 5;
    public const ROLE_BPM     = 4;
    public const ROLE_KAPRODI = 2;
    public const ROLE_AKADEMIK = 1;
    public const ROLE_DOSEN  = 3;

    /**
     * Role yang hanya boleh dimiliki 1 user secara global
     */
    protected array $exclusiveRoles = [
        self::ROLE_DIREKTUR,
        self::ROLE_WADIR,
        self::ROLE_BPM,
    ];

    /**
     * ======================
     * PUBLIC API
     * ======================
     */

    /**
     * Update user dan role-nya dengan aturan eksklusif & kaprodi per prodi
     */
    public function updateUserWithRoles(
        int $userId,
        array $roles,
        array $userData = [],
        bool $editPassword = false
    ): void {
        DB::transaction(function () use ($userId, $roles, $userData, $editPassword) {

            $user = User::findOrFail($userId);

            // Update data user
            if (!empty($userData)) {
                $user->update([
                    'name' => $userData['name'] ?? $user->name,
                    'email' => $userData['email'] ?? $user->email,
                    'password' => $editPassword && isset($userData['password'])
                        ? bcrypt($userData['password'])
                        : $user->password,
                ]);
            }

            // Apply aturan role
            foreach ($roles as $roleId) {
                $this->applyRoleRules($userId, (int) $roleId);
            }

            // Sync role
            $user->roles()->sync($roles);
        });
    }

    /**
     * ======================
     * CORE RULE ENGINE
     * ======================
     */
    protected function applyRoleRules(int $userId, int $roleId): void
    {
        // Role global eksklusif
        if (in_array($roleId, $this->exclusiveRoles, true)) {
            $this->removeRoleFromPreviousOwner($userId, $roleId);
        }

        // Kaprodi per prodi
        if ($roleId === self::ROLE_KAPRODI) {
            $prodiId = $this->getUserProdiId($userId);

            if ($prodiId) {
                $this->removeKaprodiByProdi($userId, $prodiId);
            }
        }
    }

    /**
     * ======================
     * HELPERS
     * ======================
     */

    /**
     * Hapus role eksklusif dari user lain
     */
    protected function removeRoleFromPreviousOwner(int $currentUserId, int $roleId): void
    {
        TxUserRole::where('role_id', $roleId)
            ->where('user_id', '!=', $currentUserId)
            ->delete();
    }

    /**
     * Ambil prodi user lewat relasi:
     * user -> dosens -> programStudis
     */
    protected function getUserProdiId(int $userId): ?int
    {
        $user = User::with('dosens.programStudis')->find($userId);

        return $user?->dosens
            ->first()
            ?->programStudis
            ->first()
            ?->id;
    }

    /**
     * Hapus kaprodi lama pada prodi yang sama
     */
    protected function removeKaprodiByProdi(int $currentUserId, int $prodiId): void
    {
        $userIds = User::whereHas('dosens.programStudis', function ($q) use ($prodiId) {
            $q->where('program_studis.id', $prodiId);
        })->pluck('id');

        TxUserRole::where('role_id', self::ROLE_KAPRODI)
            ->whereIn('user_id', $userIds)
            ->where('user_id', '!=', $currentUserId)
            ->delete();
    }
}
