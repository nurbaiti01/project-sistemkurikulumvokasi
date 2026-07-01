<?php

namespace App\Livewire\Kurikulum;

use App\Livewire\Base\BaseForm;
use App\Models\ProgramStudi;
use App\Models\Kurikulum;
use App\Models\ProfileLulusan;
use App\Models\CapaianPembelajaranLulusan;
use App\Models\BahanKajian;
use App\Models\CapaianPembelajaranMatakuliah;
use App\Models\SubCapaianPembelajaranMatakuliah;
use App\Models\Matakuliah;
use Illuminate\Support\Collection;

class CreateUpdate extends BaseForm
{
    protected array $relations = ['programStudis'];

    public array $tabName = [
        0 => 'Metadata Kurikulum',
        1 => 'CPL - PL',
        2 => 'BK - CPL',
        3 => 'BK - MK',
        4 => 'CPMK - SubCPMK',
        5 => 'MK - CPL',
        6 => 'CPMK - MK',
        7 => 'CPL - BK - MK',
        8 => 'CPL - CPMK - MK',
    ];

    public int $tabActive = 0;
    public int $maxTab = 8;
    public bool $showModalCplBkMK = false;
    public bool $showModalCplCpmkMK = false;
    public array $tempSelectCplBkMK = [
        'cpl' => [
            'id' => null,
            'code' => null
        ],
        'bk' => [
            'id' => null,
            'code' => null
        ]
    ];

    public array $tempSelectCplCpmkMK = [
        'cpl' => [
            'id' => null,
            'code' => null
        ],
        'cpmk' => [
            'id' => null,
            'code' => null
        ]
    ];
    public bool $showModalBkMK = false;

    // Temp data yang sedang diedit
    public array $tempSelectBkMK = [
        'bk' => [
            'id' => null,
            'code' => null,
            'name' => null,
        ],
    ];

    public array $tempSelectCpmkMK = [
        'cpmk' => [
            'id' => null,
            'code' => null,
        ],
    ];

    public array $setTempSelectCpmkMK = [];

    public bool $showModalCpmkMK = false;

    // Penyimpanan sementara (preview di tabel)
    public array $setTempSelectBkMK = [];
    public $setTempSelectCplBkMK = [];
    public $setTempSelectCplCpmkMK = [];
    public array $form = [
        'programStudis' => [],
        'cpl_pl' => [],
        'bk_cpl' => [],
        'bk_mk' => [],
        'cpmk_subcpmk' => [],
        'mk_cpl' => [],
        'cpmk_mk' => [],
        'cpl_bk_mk' => [],
        'cpl_cpmk_mk' => [],
    ];
    public array $cpl_pl = [];
    public int $step = 1;

    public $programStudis = null;

    public function setTabActive($key)
    {
        $this->tabActive = $key;
    }

    protected function rulesByStep(): array
    {
        return match ($this->tabActive) {
            0 => [
                'form.programStudis' => 'required',
                'form.name' => 'required|string',
                'form.year' => 'required',
                'form.version' => 'required|numeric',
                'form.type' => 'required',
            ],

            // TAB 1 – CPL ↔ PL
            1 => [
                'form.cpl_pl' => 'required|array|min:1',
            ],

            // TAB 2 – BK ↔ CPL
            2 => [
                'form.bk_cpl' => 'required|array|min:1',
            ],

            // TAB 3 – BK ↔ MK
            3 => [
                'form.bk_mk' => 'required|array|min:1',
            ],

            // TAB 4 – CPMK ↔ SubCPMK
            4 => [
                'form.cpmk_subcpmk' => 'required|array|min:1',
            ],

            // TAB 5 – MK ↔ CPL
            5 => [
                'form.mk_cpl' => 'required|array|min:1',
            ],

            // TAB 6 – CPMK ↔ MK
            6 => [
                'form.cpmk_mk' => 'required|array|min:1',
            ],

            // TAB 7 – CPL ↔ BK ↔ MK
            7 => [
                'form.cpl_bk_mk' => 'required|array|min:1',
            ],

            // TAB 8 – CPL ↔ CPMK ↔ MK
            8 => [
                'form.cpl_cpmk_mk' => 'required|array|min:1',
            ],

            default => [],
        };
    }
    public function nextStep()
    {
        $this->validate($this->rulesByStep());

        if ($this->tabActive < $this->maxTab) {
            $this->tabActive++;
        }
    }

    public function prevStep()
    {
        if ($this->tabActive > 0) {
            $this->tabActive--;
        }
    }
    public function updatedFormProgramStudis($value)
    {
        // $this->form['programStudis'] = array_filter($this->form['programStudis']);
        $this->form['programStudis'] = (array) $value;
        $this->getProfileLulusansProperty();
        $this->getMatakuliahsProperty();
        $cpls = $this->getCapaianPembelajaranLulusansProperty();
        $bk = $this->getBahanKajiansProperty();
        $mk = $this->getMatakuliahsProperty();
        $cpmk = $this->getCapaianPembelajaranMatakuliahsProperty();
        $subcpmk = $this->getSubCapaianPembelajaranMatakuliahsProperty();

        // reset dan init mapping cpl => []
        $this->form['cpl_pl'] = [];
        $this->form['bk_cpl'] = [];
        $this->form['bk_mk'] = [];
        $this->form['cpmk_subcpmk'] = [];
        $this->form['mk_cpl'] = [];
        $this->form['cpmk_mk'] = [];
        $this->form['cpl_bk_mk'] = [];
        $this->form['cpl_cpmk_mk'] = [];

        foreach ($cpls as $cpl) {
            $this->form['cpl_pl'][$cpl->id] = [];
            foreach ($cpmk as $item) {
                $this->form['cpl_cpmk_mk'][$item->id][$cpl->id] = [];
            }

        }

        foreach ($bk as $b) {
            foreach ($cpls as $cpl) {
                $this->form['cpl_bk_mk'][$b->id][$cpl->id] = [];

            }
        }
        foreach ($bk as $b) {
            $this->form['bk_cpl'][$b->id] = [];
            $this->form['bk_mk'][$b->id] = [];
        }

        foreach ($mk as $m) {
            $this->form['mk_cpl'][$m->id] = [];

        }
        foreach ($cpmk as $cpmks) {
            $this->form['cpmk_subcpmk'][$cpmks->id] = [];
            $this->form['cpmk_mk'][$cpmks->id] = [];
        }
    }

    public function getProfileLulusansProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        return ProfileLulusan::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function getCapaianPembelajaranLulusansProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        return CapaianPembelajaranLulusan::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function getBahanKajiansProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }
        return BahanKajian::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function getCapaianPembelajaranMatakuliahsProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }
        return CapaianPembelajaranMatakuliah::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function getSubCapaianPembelajaranMatakuliahsProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }
        return SubCapaianPembelajaranMatakuliah::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function getMatakuliahsProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }
        return Matakuliah::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas('programStudis', function ($query) use ($programStudis) {
                    $query->whereIn('program_studis.id', $programStudis);
                }),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get()
            ->map(function ($mk) {
                $mk->description = $mk->programStudis
                    ->pluck('nama')
                    ->join(', ');
                return $mk;
            });
    }


    public function getMaktulSelectProperty()
    {
        $programStudis = $this->form['programStudis'] ?? [];

        // paksa jadi array
        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }

        if (!is_array($programStudis)) {
            $programStudis = [$programStudis];
        }
        return Matakuliah::with('programStudis')
            ->when(
                count($programStudis) > 0,
                fn($q) => $q->whereHas(
                    'programStudis',
                    fn($query) =>
                    $query->whereIn('program_studis.id', $programStudis)
                ),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get()
            ->map(function ($mk) {
                return [
                    'id' => $mk->id,
                    'code' => $mk->code,
                    'semester' => $mk->semester,
                    'name' => $mk->name,
                    'sks' => $mk->sks,
                    'description' => $mk->programStudis
                        ->pluck('nama')
                        ->join(', '),
                ];
            })
            ->groupBy(fn($mk) => 'Semester ' . $mk['semester'])
            ->map(fn($items, $semester) => [
                'name' => $semester,
                'options' => $items->values(),
            ])
            ->values()
            ->toArray();
    }

    public function openAddCplBkMk($cplId = null, $bkId = null)
    {
        $this->showModalCplBkMK = true;

        $cpl = $this->getCapaianPembelajaranLulusansProperty()
            ->firstWhere('id', $cplId);

        $bk = $this->getBahanKajiansProperty()
            ->firstWhere('id', $bkId);

        $this->tempSelectCplBkMK = [
            'cpl' => [
                'id' => $cpl?->id,
                'code' => $cpl?->code,
            ],
            'bk' => [
                'id' => $bk?->id,
                'code' => $bk?->code,
            ],
        ];

        /**
         * 🔥 INJECT DATA YANG SUDAH DIPILIH
         */
        if (
            isset($this->setTempSelectCplBkMK[$bkId][$cplId]['id'])
        ) {
            $this->form['cpl_bk_mk'][$bkId][$cplId]
                = $this->setTempSelectCplBkMK[$bkId][$cplId]['id'];
        } else {
            // reset jika belum ada data
            $this->form['cpl_bk_mk'][$bkId][$cplId] = [];
        }
    }

    public function openAddCplCpmkMk($cpmkId = null, $cplId = null)
    {
        $this->showModalCplCpmkMK = true;

        $cpl = $this->getCapaianPembelajaranLulusansProperty()
            ->firstWhere('id', $cplId);

        $cpmk = $this->getCapaianPembelajaranMatakuliahsProperty()
            ->firstWhere('id', $cpmkId);

        $this->tempSelectCplCpmkMK = [
            'cpl' => [
                'id' => $cpl?->id,
                'code' => $cpl?->code,
            ],
            'cpmk' => [
                'id' => $cpmk?->id,
                'code' => $cpmk?->code,
            ],
        ];

        // 🔥 inject data lama
        if (isset($this->setTempSelectCplCpmkMK[$cpmkId][$cplId]['id'])) {
            $this->form['cpl_cpmk_mk'][$cpmkId][$cplId]
                = $this->setTempSelectCplCpmkMK[$cpmkId][$cplId]['id'];
        } else {
            $this->form['cpl_cpmk_mk'][$cpmkId][$cplId] = [];
        }

    }


    public function openAddCpmkMK($cpmkId = null)
    {
        $this->showModalCpmkMK = true;

        $cpmk = $this->getCapaianPembelajaranMatakuliahsProperty()
            ->firstWhere('id', $cpmkId);

        $this->tempSelectCpmkMK = [
            'cpmk' => [
                'id' => $cpmk?->id,
                'code' => $cpmk?->code,
            ],
        ];

        /**
         * 🔥 inject data lama
         */
        if (isset($this->setTempSelectCpmkMK[$cpmkId]['id'])) {
            $this->form['cpmk_mk'][$cpmkId]
                = $this->setTempSelectCpmkMK[$cpmkId]['id'];
        } else {
            $this->form['cpmk_mk'][$cpmkId] = [];
        }
    }

    public function openAddBkMk($bkId)
    {
        $this->showModalBkMK = true;

        $bk = $this->getBahanKajiansProperty()
            ->firstWhere('id', $bkId);

        $this->tempSelectBkMK = [
            'bk' => [
                'id' => $bk?->id,
                'code' => $bk?->code,
                'name' => $bk?->name,
            ],
        ];

        /**
         * 🔥 Inject data lama jika ada
         */
        if (isset($this->setTempSelectBkMK[$bkId]['id'])) {
            $this->form['bk_mk'][$bkId]
                = $this->setTempSelectBkMK[$bkId]['id'];
        } else {
            $this->form['bk_mk'][$bkId] = [];
        }
    }
    public function setCpmkMK()
    {
        $cpmkId = $this->tempSelectCpmkMK['cpmk']['id'];

        $selectedMkIds = $this->form['cpmk_mk'][$cpmkId] ?? [];

        $selectedMks = $this->getMatakuliahsProperty()
            ->whereIn('id', $selectedMkIds);

        $this->setTempSelectCpmkMK[$cpmkId] = [
            'id' => $selectedMkIds,
            'code' => $selectedMks->pluck('code')->values()->toArray(),
        ];

        $this->showModalCpmkMK = false;
    }
    public function setBkMk()
    {
        $bkId = $this->tempSelectBkMK['bk']['id'];

        $selectedMkIds = $this->form['bk_mk'][$bkId] ?? [];

        $selectedMks = $this->getMatakuliahsProperty()
            ->whereIn('id', $selectedMkIds);

        $this->setTempSelectBkMK[$bkId] = [
            'id' => $selectedMkIds,
            'code' => $selectedMks->pluck('code')->values()->toArray(),
        ];

        // Close modal
        $this->showModalBkMK = false;
    }
    public function setCplBkMK()
    {

        $bkId = $this->tempSelectCplBkMK['bk']['id'];
        $cplId = $this->tempSelectCplBkMK['cpl']['id'];

        $selectedMkIds = $this->form['cpl_bk_mk'][$bkId][$cplId] ?? [];

        $selectedMks = $this->getMatakuliahsProperty()
            ->whereIn('id', $selectedMkIds);

        $this->setTempSelectCplBkMK[$bkId][$cplId] = [
            'id' => $selectedMkIds,
            'code' => $selectedMks->pluck('code')->values()->toArray(),
        ];

        // ✅ close modal
        $this->showModalCplBkMK = false;
    }

    public function setCplCpmkMK()
    {
        $cpmkId = $this->tempSelectCplCpmkMK['cpmk']['id'];
        $cplId = $this->tempSelectCplCpmkMK['cpl']['id'];

        $selectedMkIds = $this->form['cpl_cpmk_mk'][$cpmkId][$cplId] ?? [];

        $selectedMks = $this->getMatakuliahsProperty()
            ->whereIn('id', $selectedMkIds);

        $this->setTempSelectCplCpmkMK[$cpmkId][$cplId] = [
            'id' => $selectedMkIds,
            'code' => $selectedMks->pluck('code')->values()->toArray(),
        ];

        $this->showModalCplCpmkMK = false;
    }

    public function mount($id = null)
    {
        // $this->form['programStudis'] = [1];
        $this->updatedFormProgramStudis(1);
        if ($id) {
            $this->openEdit($id);
        }

        // $this->cpl_pl = [];
        // dd($this->form['cpl_pl']);
    }
    protected function model(): string
    {
        return Kurikulum::class;
    }

    public function rules(): array
    {
        return [
            'form.name' => 'required|string',
            'form.year' => 'required|string',
            'form.version' => 'required|string',
            'form.type' => 'required|string',
            'form.programStudis' => 'required|min:1',
            'form.programStudis.*' => 'exists:program_studis,id',
            // 'form.cpl_pl' => 'required|array|min:1',
            // 'form.cpl_pl.*' => 'required',
        ];
    }

    public function previewData()
    {
        $this->form['created_by'] = auth()->user()->id;
        $this->form['status'] = 'draft';
        dump($this->form);
    }

    protected function beforeSave(string $action): void
    {
        if ($action == 'created') {


            dump($this->form);
            // return;

        }
    }

    public function getProdiProperty()
    {
        return ProgramStudi::all();
    }
    public function render()
    {
        return view('livewire.kurikulum.create-update');
    }
}
