<?php

namespace App\Policies;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

abstract class BasePolicy
{
    /**
     * Allowed roles default (fallback)
     * Child policy dapat override untuk menambah / membatasi
     */
    protected array $allowedRoles = [];

    /**
     * Cache container agar tidak query DB berulang
     */
    private array $resolvedAllowedRoles = [];

    /**
     * Role aktif session
     */
    protected function role(): string
    {
        return session('active_role') ?? '';
    }

    /**
     * Ambil allowed roles final:
     * 1️⃣ Jika policy child override `$allowedRoles` → gunakan itu
     * 2️⃣ Jika parent kosong → ambil dari tabel user_roles
     * 3️⃣ Jika masih kosong → default deny
     */
    protected function getAllowedRoles(): array
    {
        // sudah pernah resolve → gunakan cache
        if (!empty($this->resolvedAllowedRoles)) {
            return $this->resolvedAllowedRoles;
        }

        // Jika policy child override → gunakan langsung
        if (!empty($this->allowedRoles)) {
            return $this->resolvedAllowedRoles = $this->allowedRoles;
        }

        // Jika table belum migrate / tidak tersedia → deny untuk keamanan
        if (!Schema::hasTable('user_roles')) {
            return $this->resolvedAllowedRoles = [];
        }

        // Ambil dari database
        try {
            $roles = DB::table('user_roles')->pluck('name')->toArray();
        } catch (\Throwable $e) {
            // Jika terjadi error DB → fallback return empty
            return $this->resolvedAllowedRoles = [];
        }

        // Jika tabel ada tetapi kosong → deny
        if (empty($roles)) {
            return $this->resolvedAllowedRoles = [];
        }

        return $this->resolvedAllowedRoles = $roles;
    }

    /**
     * Check if the current role is allowed in the given block roles.
     * If no block roles are given, it will use the allowed roles from the policy.
     *
     * @param array $blockRoles Optional block roles to check against.
     * @return bool True if the current role is allowed, false otherwise.
     */
    protected function allowOnlyIfInBlockOrDefault(array $blockRoles = []): bool
    {
        $allowed = !empty($blockRoles) ? $blockRoles : $this->getAllowedRoles();
        return in_array($this->role(), $allowed);
    }

    /**
     * Check access dengan optional block roles
     */
    protected function hasAccess(?array $blockRoles = null): bool
    {
        $allowed = $this->getAllowedRoles();
        $current = $this->role();

        // jika current role bukan allowed → tidak boleh
        if (!in_array($current, $allowed)) {
            return false;
        }

        // jika tidak ada blockRoles → cukup lolos allowed saja
        if (empty($blockRoles)) {
            return true;
        }

        // jika ada blockRoles → role diblok?
        return !in_array($current, $blockRoles);
    }


    /* =====================
       Default Policy Methods
       ===================== */

    public function viewAny($user, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function view($user, $model, array $blockRoles = []): bool
    {
        return false;
    }

    public function filter($user, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function create($user, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function update($user, $model, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function delete($user, $model, array $blockRoles = []): bool
    {
        return $this->hasAccess($blockRoles);
    }

    public function restore($user, $model, array $blockRoles = []): bool
    {
        return false;
    }

    public function forceDelete($user, $model, array $blockRoles = []): bool
    {
        return false;
    }
}
