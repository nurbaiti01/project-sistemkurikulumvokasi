<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar\Section;

use Livewire\Component;
use App\Models\RealisasiPengajaran;
use App\Models\RealisasiPengajaranApproval;
use WireUi\Traits\WireUiActions;
use Flux\Flux;
class Timeline extends Component
{
    use WireUiActions;
    public ?int $selectedId = null;

    public $masterData = [];
    public $approvalId = null;
    public $catatan = null;

    public function mount(?int $selectedId = null)
    {
        $this->selectedId = $selectedId;
        $this->setMasterData();
    }

    protected function setMasterData()
    {
        $this->masterData = RealisasiPengajaran::with('approvals')->where('id', $this->selectedId)->first();
    }

    public function openDialog(int $id, $isSubmit = true)
    {
        $this->approvalId = $id;
        if ($isSubmit) {
            $this->dialog()->confirm([
                'title' => 'Are you Sure?',
                'description' => 'Save the information?',
                'acceptLabel' => 'Yes, save it',
                'method' => 'submitPerumusan',
                'params' => $id,
            ]);
        } else {
            $this->dialog()->confirm([
                'title' => 'Are you Sure?',
                'description' => 'Save the information?',
                'acceptLabel' => 'Yes, save it',
                'method' => 'saveApproved',
                'params' => $id,
            ]);
        }

    }

    public function openRejectDialog(int $id)
    {
        $this->approvalId = $id;
        Flux::modal('rejectedRealisasi')->show();
    }

    public function submitPerumusan(int $approvalId): void
    {
        $approval = RealisasiPengajaranApproval::findOrFail($approvalId);

        abort_if($approval->role_proses !== 'perumusan', 403);
        abort_if($approval->status !== 'pending', 403);

        $approval->update([
            'status' => 'approved',
            'approved' => true,
            'approved_at' => now(),
        ]);

        $approval->realisasiPengajaran()->update([
            'status' => 'submitted'
        ]);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Sudah Di Submit.',
        ]);
    }

    public function saveApproved(int $approvalId): void
    {
        $approval = RealisasiPengajaranApproval::findOrFail($approvalId);
        abort_if($approval->role_proses !== 'pemeriksaan', 403);
        abort_if($approval->status !== 'pending', 403);

        $approval->update([
            'dosen_id'=> auth()->user()->dosenId(),
            'status' => 'approved',
            'approved' => true,
            'approved_at' => now(),
        ]);

        $approval->realisasiPengajaran()->update([
            'status' => 'approved'
        ]);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Sudah Di Submit.',
        ]);
    }

    public function saveRejected()
    {
        $approval = RealisasiPengajaranApproval::findOrFail($this->approvalId);

        abort_if($approval->role_proses !== 'pemeriksaan', 403);
        abort_if($approval->status !== 'pending', 403);

        $approval->update([
            'dosen_id'=> auth()->user()->dosenId(),
            'status' => 'rejected',
            'approved' => false,
            'catatan' => $this->catatan,
            'approved_at' => now(),
        ]);

        $approval->realisasiPengajaran()->update([
            'status' => 'rejected'
        ]);
        Flux::modal('rejectedRealisasi')->close();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Sudah Di Submit.',
        ]);
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.section.timeline');
    }
}
