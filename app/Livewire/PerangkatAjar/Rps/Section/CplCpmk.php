<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;
use App\Models\Kurikulum;
use App\Models\PivotCplCpmkMk;
use App\Models\Matakuliah;
use Livewire\Attributes\On;
class CplCpmk extends Component
{
    public ?int $matakuliahId = null;
    public ?int $programStudiId = null;
    public ?int $kurikulumId = null;
    public $cplList = [];
    public $cpmkList = [];
    public $matrik = [];
    public $form = [
        'cpmks' => [],
    ];
    protected $listeners = ['requestFormData'];

    public function requestFormData(): void
    {
        $this->dispatch(
            'formDataReady',
            section: 'cpl_cpmk',
            data: $this->form
        );
    }

    public function mount(
        ?int $matakuliahId = null,
        ?int $programStudiId = null,
        ?int $kurikulumId = null
    ): void {
        $this->matakuliahId = $matakuliahId;
        $this->programStudiId = $programStudiId;
        $this->kurikulumId = $kurikulumId;

        $this->loadCplCpmk();
    }
    public function updatedMatakuliahId()
    {
        $this->loadCplCpmk();
    }

    public function updatedProgramStudiId()
    {
        $this->loadCplCpmk();
    }

    public function updatedKurikulumId()
    {
        $this->loadCplCpmk();
    }
    public function loadCplCpmk()
    {
        // Reset state dulu (WAJIB)
        $this->reset(['cplList', 'cpmkList', 'matrik']);

        if (
            !$this->matakuliahId ||
            !$this->programStudiId ||
            !$this->kurikulumId
        ) {
            return;
        }



        $matakuliah = Matakuliah::with([
            'MkCpmk' => fn($q) =>
                $q->where('kurikulum_id', $this->kurikulumId)
                    ->with('cpmk'),

            'MkCpl' => fn($q) =>
                $q->where('kurikulum_id', $this->kurikulumId)
                    ->with('cpl'),
            'programStudis' => fn($q) => $q->where('program_studis.id', $this->programStudiId)
        ])
            ->find($this->matakuliahId);
        if ($matakuliah) {
            $cplList = $matakuliah->MkCpl->pluck('cpl', 'cpl_id');
            $cpmkList = $matakuliah->MkCpmk->pluck('cpmk', 'cpmk_id');
            $matakuliah->cplMap = $matakuliah->MkCpl->keyBy('cpl_id');
            $matakuliah->cpmkMap = $matakuliah->MkCpmk->keyBy('cpmk_id');

            $this->cplList = $cplList;
            $this->cpmkList = $cpmkList;
        }
        $pivot = PivotCplCpmkMk::query()
            ->where('kurikulum_id', $this->kurikulumId)
            ->where('mk_id', $this->matakuliahId)
            ->get()
            ->groupBy('cpmk_id')
            ->map(
                fn($rows) =>
                $rows->pluck('cpl_id')->flip()->map(fn() => true)
            );

        $matrix = [];

        foreach ($cplList as $cplId => $cpl) {
            foreach ($cpmkList as $cpmkId => $cpmk) {
                $matrix[$cpmkId][$cplId] = isset($pivot[$cpmkId][$cplId]);
            }
        }
        $this->matrik = $matrix;
        $this->form['cpmks'] = $matakuliah->MkCpmk
            ->mapWithKeys(fn($item) => [
                $item->cpmk_id => [
                    'cpmk_id' => $item->cpmk_id,
                    'bobot' => 0,
                ]
            ])
            ->toArray();
        // $params = $matakuliah->MkCpmk
        //     ->map(fn($item) => $item->cpmk)
        //     ->filter()
        //     ->values();
        $params = [
            'cpmk' => $matakuliah->MkCpmk
                ->map(fn($item) => $item->cpmk)
                ->filter()
                ->values(),
            'cpl' => $matakuliah->MkCpl
                ->map(fn($item) => $item->cpl)
                ->filter()
                ->values(),
            'matrik' => $this->matrik
        ];
        $this->dispatch('cpmkCplMatrikUpdated', $cpmkList, $cplList, $this->matrik);

    }

    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.cpl-cpmk');
    }
}
