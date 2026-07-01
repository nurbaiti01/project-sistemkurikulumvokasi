<?php

namespace App\Livewire\Kurikulum;

use Livewire\Component;
use Livewire\Attributes\Title;
use App\Models\Kurikulum;
use App\Services\KurikulumTreeBuilder;
use WireUi\Traits\WireUiActions;
use Flux\Flux;
#[Title('Matriks Kurikulum')]
class MatriksData extends Component
{
    use WireUiActions;

    public $selectedId = null;
    public Kurikulum $kurikulum;
    public string $matrixMode = '';
    public array $originalTree = [];
    public array $tree = [];
    public string $status_approval = '';
    public string $approval_note = '';
    public string $approval_role = '';
    // public array $expanded = [];

    public function mount($id)
    {
        $this->selectedId = $id;
        $this->kurikulum = Kurikulum::with(['programStudis', 'wadirApproval'])->find($id);
        $this->originalTree = (new KurikulumTreeBuilder($this->kurikulum))->build();
        $this->tree = $this->originalTree;
    }

    public function updatedMatrixMode($value)
    {
        $this->matrixMode = $value;
        $this->dispatch('applyMatrixFilter', mode: $value);
    }


    public function expandedAll()
    {
        $this->dispatch('expandAll');
    }

    public function collapseAll()
    {
        $this->dispatch('collapseAll');
    }

    public function submitKurikulum()
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Are you sure want to save this kurikulum?',
            'acceptLabel' => 'Yes, Submit it!',
            'method' => 'save',
        ]);
    }

    public function approval(int $id, string $role)
    {
        $this->approval_role = $role;
        $this->status_approval = 'approved';
        Flux::modal('approvedDialog')->show();
    }

    public function rejected(int $id, string $role)
    {
        $this->approval_role = $role;
        $this->status_approval = 'rejected';
        Flux::modal('rejectedDialog')->show();
    }

    public function save()
    {
        $this->kurikulum->status = 'submitted';
        $this->kurikulum->save();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Kurikulum Berhasil Disimpan',
            'timeout' => 2500
        ]);
        // $this->dispatch('success-updated')->component('kurikulum.index');
    }

    public function approvedKurikulum()
    {
        $this->saveApproval();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Kurikulum Berhasil Approved',
            'timeout' => 2500
        ]);

        Flux::modal('approvedDialog')->close();
    }

    public function rejectedKurikulum()
    {
        $this->validate(
            [
                'approval_note' => 'required',
            ],
            [
                'approval_note.required' => 'Note Tidak Boleh Kosong',
            ]
        );
        $this->saveApproval();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Kurikulum Berhasil Approved',
            'timeout' => 2500
        ]);

        Flux::modal('rejectedDialog')->close();
    }

    public function saveApproval()
    {
        $this->kurikulum->status = ($this->approval_role == 'bpm' ? 'published' : ($this->approval_role == 'wadir' ? 'approved_wadir' : 'approved_direktur'));
        $this->kurikulum->save();

        $getApproval = $this->kurikulum->approvals()->where('role', strtolower($this->approval_role))->first();
        if ($getApproval) {
            $getApproval->note = $this->approval_note;
            $getApproval->status = $this->status_approval;
            $getApproval->role = strtolower($this->approval_role);
            $getApproval->approved_by = auth()->user()->id;
            $getApproval->updated_at = now();
            $getApproval->approved_at = now();
            $getApproval->save();
        } else {
            $this->kurikulum->approvals()->create([
                'note' => $this->approval_note,
                'role' => strtolower($this->approval_role),
                'status' => $this->status_approval,
                'approved_by' => auth()->user()->id,
                'approved_at' => now(),
            ]);
        }
        // dump($this->kurikulum->approvals()->where('role', $this->approval_role)->first());
    }

    
    public function render()
    {
        return view('livewire.kurikulum.matriks-data');
    }
}
