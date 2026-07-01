<?php

namespace App\Livewire\Master\ProgramStudi;

use App\Livewire\Base\BaseForm;
use App\Models\ProgramStudi;
use App\Models\ProgramStudi as PRODI;

class CreateUpdate extends BaseForm
{
    public $listJenjang = [
        PRODI::JENJANG_D2 => 'Diploma 2',
        PRODI::JENJANG_D3 => 'Diploma 3',
        PRODI::JENJANG_D4 => 'Diploma 4',
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->openEdit($id);
        }


    }
    protected function model(): string
    {
        return PRODI::class;
    }

    public function rules(): array
    {
        return [
            'form.code' => 'required|string|unique:program_studis,code,' . $this->selectedId,
            'form.name' => 'required|string',
            'form.jenjang' => 'required|string',
            'form.singkatan' => 'required|string',
        ];
    }

    public function updating($key, $value)
    {
        if ($key == 'form.name') {

            $this->form['singkatan'] = collect(explode(' ', $value))
                ->filter()
                ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
                ->implode('');
        }
    }

    public function render()
    {
        return view('livewire.master.program-studi.create-update');
    }
}
