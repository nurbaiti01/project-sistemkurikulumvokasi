<?php

namespace App\Livewire\Master\PL;

use Livewire\Component;
use App\Models\ProgramStudi;
use App\Models\ProfileLulusan as PL;
use WireUi\Traits\WireUiActions;
use Illuminate\Support\Facades\Auth;
class Create extends Component
{
    use WireUiActions;

    protected $user;
    public $isKaprodi = false;
    public string $code = '';
    public string $name = '';
    public string $description = '';
    public $prodi_id = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->isKaprodi();// dump('test');

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
            $this->prodi_id = [$programStudi?->id];
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

    public function rules()
    {
        return [
            'code' => 'required|string|unique:profile_lulusans,code',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'prodi_id' => 'required|exists:program_studis,id',
        ];
    }

    public function save()
    {
        $this->validate();

        $create = PL::create([
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
        ]);

        if ($this->prodi_id) {
            $create->programStudis()->sync($this->prodi_id);
        }
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Info Notification!',
            'description' => 'This is a description.',
            'timeout' => 2500
        ]);
        $this->dispatch('success-created')->component('master.pl.index');
        $this->cancel();
    }
    public function render()
    {
        return view('livewire.master.p-l.create');
    }
}
