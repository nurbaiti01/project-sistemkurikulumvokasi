<?php

namespace App\Livewire\Master\SubCpmk;

use App\Livewire\Base\BaseForm;
use App\Models\ProgramStudi;
use App\Models\SubCapaianPembelajaranMatakuliah as SUBCPMK;

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
        return SUBCPMK::class;
    }

    public function rules(): array
    {
        return [
            'form.code' => 'required|string|unique:capaian_pembelajaran_matakuliahs,code,' . $this->selectedId,
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
        return view('livewire.master.sub-cpmk.create-update');
    }
}
