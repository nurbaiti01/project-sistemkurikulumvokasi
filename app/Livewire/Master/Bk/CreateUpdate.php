<?php

namespace App\Livewire\Master\Bk;

use App\Livewire\Base\BaseForm;
use App\Models\ProgramStudi;
use App\Models\BahanKajian as BK;
use Illuminate\Support\Facades\Auth;
class CreateUpdate extends BaseForm
{

    
    protected array $relations = ['programStudis'];

    public function mount($id = null)
    {
        $this->isKaprodi();
        if ($id) {
            $this->openEdit($id);
        }


    }
    protected function model(): string
    {
        return BK::class;
    }

    public function rules(): array
    {
        return [
            'form.code' => 'required|string|unique:bahan_kajians,code,' . $this->selectedId,
            'form.name' => 'required|string',
            'form.description' => 'required|string',
            'form.programStudis' => 'required|min:1',
            'form.programStudis.*' => 'exists:program_studis,id',
        ];
    }

    public function getProdiProperty()
    {
        return ProgramStudi::all();
    }
    public function render()
    {
        return view('livewire.master.bk.create-update');
    }
}
