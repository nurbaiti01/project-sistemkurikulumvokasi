<?php

namespace App\Livewire\Master\Cpl;

use Livewire\Component;
use App\Livewire\Base\BaseForm;
use App\Models\CapaianPembelajaranLulusan as CPL;
use App\Models\ProgramStudi;

class CreateUpdate extends BaseForm
{
    protected array $relations = ['programStudis'];

    public function mount($id = null)
    {
        if ($id) {
            $this->openEdit($id);
        }


    }
    protected function model(): string
    {
        return CPL::class;
    }

    public function rules(): array
    {
        return [
            'form.code' => 'required|string|unique:capaian_pembelajaran_lulusans,code,' . $this->selectedId,
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
        return view('livewire.master.cpl.create-update');
    }
}
