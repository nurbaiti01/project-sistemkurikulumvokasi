<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;

class Penilaian extends Component
{
    public $cpmkList = [];
    public $asesmen = [
        'uts' => true,
        'uas' => true,
    ];
    public $kelompokPenilaian = [
        'default' => [
            'aktivitas_partisipatif',
            'project',
        ],
        'kognitif' => [
            'tugas',
            'kuis',
            'uts',
            'uas',
        ],
    ];

    public $penilaian = [
        'aktivitas_partisipatif' => [
            'persentase' => 10,
            'cpmk' => [], // [cpmk_id => nilai]
        ],
        'project' => [
            'persentase' => 0,
            'cpmk' => [],
        ],
        'tugas' => [
            'persentase' => 25,
            'cpmk' => [],
        ],
        'kuis' => [
            'persentase' => 15,
            'cpmk' => [],
        ],
        'uts' => [
            'persentase' => 20,
            'cpmk' => [],
        ],
        'uas' => [
            'persentase' => 30,
            'cpmk' => [],
        ],
    ];

    protected $listeners = ['requestFormData'];

    public function requestFormData(): void
    {
        $this->dispatch(
            'formDataReady',
            section: 'penilaian',
            data: $this->penilaian
        );
    }

    public function mount(array $cpmkList = []): void
    {
        $this->cpmkList = $this->normalizeCpmkList($cpmkList);
        dump($this->cpmkList);
    }

    protected function normalizeCpmkList(array $list): array
    {
        return collect($list)
            ->map(fn($item) => (object) $item)
            ->values()
            ->toArray();
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.penilaian');
    }
}
