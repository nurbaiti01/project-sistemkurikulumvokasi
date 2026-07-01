<?php

namespace App\Livewire\Master\PL;

use Livewire\Component;
use App\Models\ProgramStudi;
use App\Models\ProfileLulusan as PL;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
class Update extends Component
{
    use WireUiActions;
    protected $user;
    public $isKaprodi = false;
    public string $code = '';
    public string $name = '';
    public string $description = '';
    public $prodi_id = [];

    public int $selectedId;
    public function mount($selectedId)
    {
        $this->selectedId = $selectedId;
        $pl = PL::find($selectedId);
        $this->code = $pl->code;
        $this->name = $pl->name;
        $this->description = $pl->description;
        $this->prodi_id = $pl->programStudis->pluck('id')->toArray();
        $this->isKaprodi();
    }
    protected function isKaprodi()
    {
        $isKaprodi = session('active_role') == 'Kaprodi';

        if ($isKaprodi) {
            $programStudi = $this->user
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();
            $this->isKaprodi = true;
        }
    }
    public function getProdiProperty()
    {
        return ProgramStudi::all();
    }
    public function cancel()
    {
        $this->dispatch('cancel');
    }

    public function save()
    {
        $update = PL::find($this->selectedId);
        $update->update([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ]);


        if ($this->prodi_id) {
            $update->programStudis()->sync($this->prodi_id);
        }
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Profile Lulusan Berhasil Diupdate',
            'timeout' => 2500
        ]);
        $this->dispatch('success-updated')->component('master.pl.index');
        $this->cancel();
    }

    public function render()
    {
        return view('livewire.master.p-l.update');
    }
}
