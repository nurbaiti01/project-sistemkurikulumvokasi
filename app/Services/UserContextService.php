<?php

namespace App\Services;

class UserContextService
{
    public function getActiveDosenContext(): array
    {
        $user = auth()->user();

        if (session('active_role') !== 'Dosen') {
            return [];
        }

        $dosen = $user->dosens()->with('programStudis')->first();

        return [
            'dosen_id' => $dosen?->id,
            'prodi_id' => $dosen?->programStudis()->first()?->id,
            'dosen_name' => $user->name,
        ];
    }
}