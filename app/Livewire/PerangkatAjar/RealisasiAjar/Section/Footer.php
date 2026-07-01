<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar\Section;

use Livewire\Component;
use App\Models\RealisasiPengajaranEvaluasi;
use App\Models\RealisasiPengajaranMetode;
use App\Models\RealisasiPengajaranReferensi;
class Footer extends Component
{
    public $form = [
        'metode' => [
            'kuliah' => [
                'jam' => ''
            ],
            'tutorial' => [
                'jam' => ''
            ],
            'laboratorium' => [
                'jam' => ''
            ]
        ],
        'evaluasi' => [
            'tugas_persen' => '',
            'kuis_persen' => '',
            'ujian_persen' => '',
        ]
    ];

    public $lisJenisReferensi = ['buku', 'diktat'];
    public $referensi = [];

    public ?int $selectedId = null;
    public $isEdit = false;
    public $isView = false;

    protected $listeners = ['requestFormData'];

    public function requestFormData()
    {
        $this->validate([
            'form.metode.kuliah.jam' => ['required', 'integer', 'min:0'],
            'form.metode.tutorial.jam' => ['required', 'integer', 'min:0'],
            'form.metode.laboratorium.jam' => ['required', 'integer', 'min:0'],
            'form.evaluasi.tugas_persen' => ['required', 'integer', 'min:0'],
            'form.evaluasi.kuis_persen' => ['required', 'integer', 'min:0'],
            'form.evaluasi.ujian_persen' => ['required', 'integer', 'min:0'],
            'referensi' => ['required', 'array', 'min:1'],
            'referensi.*.jenis' => ['required', 'string'],
            'referensi.*.judul' => ['required', 'string'],
            'referensi.*.penerbit' => ['required', 'string'],
        ]);
        $this->form['referensi'] = $this->referensi;

        $this->dispatch(
            'formDataReady',
            section: 'footer',
            data: $this->form
        );
    }

    // public function editData($id)
    // {
    //     $this->selectedId = $id;
    //     $this->isEdit = true;
    //     $this->openEdit();
    // }

    public function openEdit()
    {
        $this->initRealisasiState();

        // ======================
        // METODE
        // ======================
        $metodeData = RealisasiPengajaranMetode::where('realisasi_id', $this->selectedId)->get();

        foreach ($metodeData as $metode) {
            $this->form['metode'][$metode->jenis]['jam'] = (int) $metode->jam;
        }

        // ======================
        // EVALUASI
        // ======================
        $evaluasiData = RealisasiPengajaranEvaluasi::where('realisasi_id', $this->selectedId)->first();

        if ($evaluasiData) {
            $this->form['evaluasi'] = [
                'tugas_persen' => (int) $evaluasiData->tugas_persen,
                'kuis_persen' => (int) $evaluasiData->kuis_persen,
                'ujian_persen' => (int) $evaluasiData->ujian_persen,
            ];
        }

        // ======================
        // REFERENSI
        // ======================
        $referensiData = RealisasiPengajaranReferensi::where('realisasi_id', $this->selectedId)->get();

        if ($referensiData->isNotEmpty()) {
            $this->referensi = $referensiData->map(fn($ref) => [
                'uid' => uniqid(),
                'jenis' => $ref->jenis,
                'judul' => $ref->judul,
                'penerbit' => $ref->penerbit,
            ])->toArray();
        }
    }


    protected function initRealisasiState(): void
    {
        $this->form['metode'] = [
            'kuliah' => ['jam' => 0],
            'tutorial' => ['jam' => 0],
            'laboratorium' => ['jam' => 0],
        ];

        $this->form['evaluasi'] = [
            'tugas_persen' => 0,
            'kuis_persen' => 0,
            'ujian_persen' => 0,
        ];

        // Minimal 1 row referensi
        $this->referensi = [
            [
                'uid' => uniqid(),
                'jenis' => '',
                'judul' => '',
                'penerbit' => '',
            ]
        ];
    }

    public function mount(int $selectedId = null, bool $isEdit = false, bool $isView = false)
    {
        if ($selectedId && ($isEdit || $isView)) {
            $this->selectedId = $selectedId;
            $this->isEdit = $isEdit;
            $this->isView = $isView;
            $this->openEdit();
        } else {
            $this->addReferensi();

        }
    }
    public function addReferensi()
    {
        $this->referensi[] = [
            'uid' => uniqid(),
            'jenis' => '',
            'judul' => '',
            'penerbit' => '',
        ];
    }

    public function removeReferensi($index)
    {
        if (count($this->referensi) <= 1) {
            return;
        }

        unset($this->referensi[$index]);
        $this->referensi = array_values($this->referensi);
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.section.footer');
    }
}
