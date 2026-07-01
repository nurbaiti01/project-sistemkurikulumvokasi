<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;
use Livewire\Attributes\On;
class PertemuanGrid extends Component
{
    const MENIT_PER_JAM_AKADEMIK = 170;
    public array $jenisAlokasi = [
        'PB' => 'Pembelajaran Tatap Muka',
        'PT' => 'Tugas Terstruktur',
        'BM' => 'Belajar Mandiri',
        'ASESMEN' => 'Asesmen',
    ];

    public $cpmkList = [];
    public $pertemuans = [];

    protected $listeners = ['requestFormData'];

    public function requestFormData(): void
    {
        $this->dispatch(
            'formDataReady',
            section: 'pertemuan_grid',
            data: $this->pertemuans
        );
    }

    public function mount(array $cpmkList = []): void
    {
        $this->cpmkList = $this->normalizeCpmkList($cpmkList);
        $this->addPertemuan();
    }


    protected function normalizeCpmkList(array $list): array
    {
        return collect($list)
            ->map(fn($item) => (object) $item)
            ->values()
            ->toArray();
    }

    public function addPertemuan()
    {
        $this->pertemuans[] = [
            'show' => true,
            'pertemuan_ke' => count($this->pertemuans) + 1,
            'materi_ajar' => '',
            'indikator' => '',
            'bentuk_pembelajaran' => '',
            'pemberian_tugas' => false,
            'cpmk_id' => null,
            'selected_bobot_index' => null,
            'alokasi' => [
                [
                    'tipe' => 'PB',
                    'jumlah' => 3,
                    'menit' => 50,
                ],
                [
                    'tipe' => 'BM',
                    'jumlah' => 3,
                    'menit' => 120,
                ],
            ],
            'bobots' => [
                ['jenis' => '', 'bobot' => 0],
            ],
            'rancangan_penilaian' => [
                'jenis' => '',
                'bentuk' => '',
                'bobot' => 0,
                'topik' => '',
            ]
        ];
        $this->updatedPertemuans();
    }

    public function removePertemuan($index)
    {
        if (count($this->pertemuans) <= 1) {
            return;
        }

        unset($this->pertemuans[$index]);
        $this->pertemuans = array_values($this->pertemuans);
    }

    public function totalJamPertemuan($pIndex): int
    {
        return collect($this->pertemuans[$pIndex]['alokasi'])
            ->sum(fn($row) => (int) $row['jam']);
    }

    public function totalMenitPertemuan($pIndex): int
    {
        return collect($this->pertemuans[$pIndex]['alokasi'])
            ->sum(fn($a) => (int) $a['jumlah'] * (int) $a['menit']);
    }

    public function totalMenitSemester(): int
    {
        $result = collect($this->pertemuans)
            ->keys()
            ->sum(fn($i) => $this->totalMenitPertemuan($i));
        return $result;
    }

    public function totalJamSemester(): int
    {
        $result = floor($this->totalMenitSemester() / 60);
        return $result;
    }
    public function totalMenitAsesmen(): int
    {
        $jumlah = collect($this->asesmen)->filter()->count();
        return $jumlah * 2 * self::MENIT_PER_JAM_AKADEMIK;
    }

    public function addBobot($pIndex)
    {
        $this->pertemuans[$pIndex]['bobots'][] = [
            'jenis' => '',
            'bobot' => 0,
        ];
    }
    public function totalBobotPertemuan(int $pIndex): int
    {
        return collect($this->pertemuans[$pIndex]['bobots'] ?? [])
            ->sum(fn($b) => (int) ($b['bobot'] ?? 0));
    }
    public function totalBobotPerCpmk(): array
    {
        return collect($this->pertemuans)
            ->filter(fn($p) => !empty($p['cpmk_id']))
            ->groupBy('cpmk_id')
            ->map(function ($pertemuanGroup) {
                return $pertemuanGroup->sum(function ($p) {
                    return collect($p['bobots'] ?? [])
                        ->sum(fn($b) => (int) ($b['bobot'] ?? 0));
                });
            })
            ->toArray();
    }
    public function getCpmkCode($cpmkId): string
    {
        return $this->matriksCplCpmk['cpmk'][$cpmkId]->code ?? 'CPMK-' . $cpmkId;
    }

    public function removeBobot($pIndex, $bIndex)
    {
        if (count($this->pertemuans[$pIndex]['bobots']) <= 1) {
            return;
        }

        unset($this->pertemuans[$pIndex]['bobots'][$bIndex]);
        $this->pertemuans[$pIndex]['bobots'] = array_values(
            $this->pertemuans[$pIndex]['bobots']
        );
    }

    public function updatedPertemuans()
    {
        // dump($pIndex, $pValue);
        $this->dispatch('totalJamSemesterUpdated', $this->totalJamSemester());
        $this->dispatch('totalMenitSemesterUpdated', $this->totalMenitSemester());
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.pertemuan-grid');
    }
}
