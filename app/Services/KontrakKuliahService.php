<?php

namespace App\Services;
use App\Models\KontrakKuliah;
use App\Models\KontrakKuliahApproval;
use Illuminate\Support\Facades\DB;
class KontrakKuliahService
{
    protected $kontrakKuliah;
    protected $kontrakKuliahApproval;
    public function __construct(KontrakKuliah $kontrakKuliah, KontrakKuliahApproval $kontrakKuliahApproval)
    {
        $this->kontrakKuliah = $kontrakKuliah;
        $this->kontrakKuliahApproval = $kontrakKuliahApproval;
    }

    public function createUpdateWithApprovals(array $data, ?int $selectedId = null): KontrakKuliah
    {
        return DB::transaction(function () use ($data, $selectedId) {

            if ($selectedId) {
                $kontrak = $this->kontrakKuliah->findOrFail($selectedId);
                $kontrak->update($data);
            } else {
                $kontrak = $this->kontrakKuliah->create($data);
            }

            $this->createApproval($kontrak->id, $data);

            return $kontrak;
        });
    }

    protected function createApproval($idKontrakKuliah, $data)
    {
        $now = now();

        $exists = $this->kontrakKuliahApproval
            ->where('kontrak_kuliah_id', $idKontrakKuliah)
            ->exists();

        // ======================
        // JIKA BELUM ADA APPROVAL
        // ======================
        if (!$exists) {
            $this->kontrakKuliahApproval->insert([
                [
                    'kontrak_kuliah_id' => $idKontrakKuliah,
                    'dosen_id' => auth()->user()->dosenId(),
                    'role_proses' => 'perumusan',
                    'status' => 'pending',
                    'approved' => false,
                    'approved_at' => $now,
                    'catatan' => $data['catatan'] ?? null,
                    'created_at' => $now,
                ],
                [
                    'kontrak_kuliah_id' => $idKontrakKuliah,
                    'dosen_id' => null,
                    'role_proses' => 'pemeriksaan',
                    'status' => 'pending',
                    'approved' => false,
                    'approved_at' => null,
                    'catatan' => null,
                    'created_at' => $now,
                ],
            ]);

            return;
        }

        // ======================
        // JIKA SUDAH ADA APPROVAL
        // ======================

        // Update role PERUMUSAN
        $this->kontrakKuliahApproval
            ->where('kontrak_kuliah_id', $idKontrakKuliah)
            ->where('role_proses', 'perumusan')
            ->update([
                'catatan' => $data['catatan'] ?? null,
                'approved' => true,
                'status' => 'approved',
                'approved_at' => $now,
                'dosen_id' => auth()->user()->dosenId(),
                'updated_at' => $now,
            ]);

        // Reset role PEMERIKSAAN
        $this->kontrakKuliahApproval
            ->where('kontrak_kuliah_id', $idKontrakKuliah)
            ->where('role_proses', 'pemeriksaan')
            ->update([
                'status' => 'pending',
                'approved' => false,
                'approved_at' => null,
                'updated_at' => $now,
            ]);
    }

}
