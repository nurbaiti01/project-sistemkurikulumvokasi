<?php

namespace App\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\UserRole;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Livewire\Attributes\On;
class Inlistusers extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $perPage = 10;
    public $search = '';
    public $selectedId;
    public $filter = [
        'status' => 'all', // all | trashed | withTrashed
    ];
    public $showModalCreate = false;
    public $showModalUpdate = false;
    public $showModalDelete = false;
    public $listRoles = [];

    public function mount()
    {
        $this->listRoles = UserRole::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilter()
    {
        $this->resetPage();
    }

    public function openModalCreate()
    {
        // $this->showModalCreate = true;
        $this->modal('create-modal')->show();
        // dd($this->showModalCreate);
    }

    public function openModalEdit($id)
    {
        $this->selectedId = $id;
        $this->showModalUpdate = true;
        $this->modal('update-modal')->show();
    }

    public function openModalDelete($id)
    {
        $this->selectedId = $id;
        $this->showModalDelete = true;
        $this->modal('delete-modal')->show();

    }

    #[On('created'), On('updated')]
    public function closeModal()
    {
        $this->selectedId = null;
        $this->showModalCreate = false;
        $this->showModalUpdate = false;
        $this->showModalDelete = false;
        $this->modal('create-modal')->close();
        $this->modal('update')->close();
        $this->modal('delete')->close();
    }

    public function delete()
    {
        User::whereKey($this->selectedId)->delete();
        $this->closeModal();
    }

    public function restore($id)
    {
        User::withTrashed()
            ->whereKey($id)
            ->restore();

        $this->dispatch('restored');
    }
    public function render()
    {
        $user = auth()->user();

        $data = User::query()
            ->with('roles')

            // ❌ jangan tampilkan user login
            ->whereKeyNot($user->id)

            // 🔥 FILTER STATUS USER
            ->when($this->filter['status'] === 'trashed', function ($q) {
                $q->onlyTrashed();
            })
            ->when($this->filter['status'] === 'withTrashed', function ($q) {
                $q->withTrashed();
            })

            // 🔍 SEARCH
            ->when(
                $this->search,
                fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
            )

            ->paginate($this->perPage);

        return view('pages.users.list', compact('data'));
    }

}
