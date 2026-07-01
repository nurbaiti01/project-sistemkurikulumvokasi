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
use App\Models\PivotPlCpl;
use App\Models\PivotCplBk;
use App\Models\PivotBkMk;
use App\Models\PivotCpmkSubcpmk;
use App\Models\PivotCplMk;
use App\Models\PivotCpmkMk;
use App\Models\PivotCplBkMk;
use App\Models\PivotCplCpmkMk;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
class CreateUpdate extends BaseForm
{
    protected array $relations = ['programStudis'];

    public array $tabName = [
        0 => 'Metadata Kurikulum',
        1 => 'CPL - PL',
        2 => 'CPL - BK',
        3 => 'BK - MK',
        4 => 'CPMK - SubCPMK',
        5 => 'CPL - MK',
        6 => 'MK - CPMK',
        7 => 'CPL - BK - MK',
        8 => 'CPL - CPMK - MK',
        9 => 'Distribusi MK'
    ];
    public int $tabActive = 0;
    public int $maxTab = 9;
    public bool $showModalCplBkMK = false;
    public bool $showModalCplCpmkMK = false;
    public bool $showModalBkMK = false;
    public bool $showModalCpmkMK = false;

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

    public ?int $selectedId = null;
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
        'distribusi_mk' => [],
    ];
    public $programStudis = null;

    public $listCpl = [];
    public $listPl = [];
    public $listBk = [];
    public $listMk = [];
    public $listMkOption = [];
    public $listCpmk = [];
    public $listSubCpmk = [];

    public $listSemeter = [];


    public function mount($id = null)
    {
        $this->selectedId = $id;
        $this->setFormProdi();
        if ($id != null) {
            $this->openEdits($this->selectedId, $this->tabActive);
            $this->loadDataMaster();
        }
    }

    public function settabActive($tabActive)
    {
        $this->tabActive = $tabActive;
        $this->loadDataMaster();
        $this->openEdits($this->selectedId, $this->tabActive);
    }
    protected function setFormProdi()
    {
        // Hanya untuk Kaprodi
        if (session('active_role') !== 'Kaprodi') {
            return;
        }

        $programStudi = auth()->user()
                ?->dosens()
                ?->with('programStudis')
                ?->first()
                ?->programStudis()
                ?->first();

        // Mapping jenjang ke jumlah semester
        $semesterMap = [
            'D2' => 4,
            'D3' => 6,
            'D4' => 8,
        ];

        $jenjang = $programStudi?->jenjang;

        // Default fallback kalau jenjang tidak dikenal
        $this->listSemeter = $semesterMap[$jenjang] ?? 8;

        $this->form['programStudis'] = [$programStudi?->id];
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

    public function confirmNextStep()
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Save the information?',
            'acceptLabel' => 'Yes, save it',
            'method' => 'nextStep',
        ]);
    }
    public function nextStep()
    {
        $this->validate($this->rulesByStep());
        $this->saveByNextTab(null, $this->tabActive);

        if ($this->tabActive < $this->maxTab) {
            $this->tabActive++;
        }
        $this->loadDataMaster();
        // dump('Before OpenEdits', $this->form['cpl_pl']);
        $this->openEdits($this->selectedId, $this->tabActive);
        // dump('After OpenEdits', $this->form['cpl_pl']);
    }

    public function loadDataMaster()
    {
        $this->listPl = [];
        $this->listCpl = [];
        $this->listBk = [];
        $this->listMk = [];
        $this->listCpmk = [];
        $this->listSubCpmk = [];
        $this->listMkOption = [];
        switch ($this->tabActive) {
            case 1:
                $this->form['cpl_pl'] = [];
                $this->listPl = $this->getProfileLulusansProperty();
                $this->listCpl = $this->getCapaianPembelajaranLulusansProperty();
                foreach ($this->listCpl as $cpl) {
                    $this->form['cpl_pl'][$cpl->id] = [];
                }
                break;
            case 2:
                $this->form['bk_cpl'] = [];
                $this->listCpl = $this->getCapaianPembelajaranLulusansProperty();
                $this->listBk = $this->getBahanKajiansProperty();
                foreach ($this->listBk as $b) {
                    $this->form['bk_cpl'][$b->id] = [];
                }
                break;
            case 3:
                $this->form['bk_mk'] = [];
                $this->listBk = $this->getBahanKajiansProperty();
                $this->listMk = $this->getMatakuliahsProperty();
                // foreach ($this->listBk as $b) {
                //     $this->form['bk_mk'][$b->id] = [];
                // }
                foreach ($this->listBk as $bk) {
                    $this->form['bk_mk'][$bk->id] = [];
                }
                break;
            case 4:
                $this->form['cpmk_subcpmk'] = [];
                $this->listCpmk = $this->getCapaianPembelajaranMatakuliahsProperty();
                $this->listSubCpmk = $this->getSubCapaianPembelajaranMatakuliahsProperty();
                foreach ($this->listCpmk as $cpmk) {
                    $this->form['cpmk_subcpmk'][$cpmk->id] = [];
                }
                break;
            case 5:
                $this->form['mk_cpl'] = [];
                $this->listCpl = $this->getCapaianPembelajaranLulusansProperty();
                $this->listMk = $this->getMatakuliahsProperty();

                foreach ($this->listMk as $mk) {
                    $this->form['mk_cpl'][$mk->id] = [];
                }
                break;
            case 6:
                $this->form['cpmk_mk'] = [];
                $this->listCpmk = $this->getCapaianPembelajaranMatakuliahsProperty();
                $this->listMk = $this->getMatakuliahsProperty();

                foreach ($this->listMk as $mk) {
                    $this->form['cpmk_mk'][$mk->id] = [];
                }
                break;
            case 7:
                $this->form['cpl_bk_mk'] = [];
                $this->listCpl = $this->getCapaianPembelajaranLulusansProperty();
                $this->listBk = $this->getBahanKajiansProperty();
                $this->listMk = $this->getMatakuliahsProperty();
                break;
            case 8:
                $this->form['cpl_cpmk_mk'] = [];
                $matakuliahs = Matakuliah::with([
                    'MkCpmk' => function ($q) {
                        $q->where('kurikulum_id', $this->selectedId)
                            ->with('cpmk');
                    },
                    'MkCpl' => function ($q) {
                        $q->where('kurikulum_id', $this->selectedId)
                            ->with('cpl');
                    }
                ])
                    ->get();

                $matakuliahs->each(function ($mk) {
                    $mk->cplMap = $mk->MkCpl->keyBy('cpl_id');
                });

                $matakuliahs->each(function ($mk) {
                    $mk->cpmkMap = $mk->MkCpmk->keyBy('cpmk_id');
                });

                foreach ($matakuliahs as $mk) {
                    $this->form['cpl_cpmk_mk'][$mk->id] = [];
                    foreach ($mk->cpmkMap as $cpmk) {
                        $this->form['cpl_cpmk_mk'][$mk->id][$cpmk->cpmk_id] = [];
                    }
                }

                $this->listMk = $matakuliahs;
                foreach ($matakuliahs as $mk) {
                    $cpls = $mk->MkCpl->pluck('cpl')->unique('id');
                    // dump($mk->MkCpmk);
                }
                break;
            case 9:
                $this->form['distribusi_mk'] = [];
                $this->listMk = $this->getMatakuliahsProperty();
                foreach ($this->listMk as $mk) {
                    $this->form['distribusi_mk'][$mk->id] = [
                        'sks' => null,
                        'semester' => null,
                    ];
                }
                break;
            default:
                break;
        }
    }

    public function prevStep()
    {
        if ($this->tabActive > 0) {
            $this->tabActive--;
        }
        $this->loadDataMaster();
        $this->openEdits($this->selectedId, $this->tabActive);

    }

    protected function loadByPrevStep()
    {

    }
    protected function selectedProdiIds(): array
    {
        $prodi = $this->form['programStudis'] ?? [];

        if (empty($prodi)) {
            return [];
        }

        return is_array($prodi) ? $prodi : [$prodi];
    }

    protected function loadByProdi(string $model, array $with = ['programStudis'])
    {
        $prodiIds = $this->selectedProdiIds();

        return $model::with($with)
            ->when(
                count($prodiIds) > 0,
                fn($q) => $q->whereHas(
                    'programStudis',
                    fn($query) =>
                    $query->whereIn('program_studis.id', $prodiIds)
                ),
                fn($q) => $q->whereRaw('1 = 0')
            )
            ->get();
    }

    public function updatedFormProgramStudis($value)
    {
        $this->form['programStudis'] = (array) $value;
        
    }

    public function getProfileLulusansProperty()
    {
        return $this->loadByProdi(ProfileLulusan::class);
    }

    public function getCapaianPembelajaranLulusansProperty()
    {
        return $this->loadByProdi(CapaianPembelajaranLulusan::class);
    }

    public function getBahanKajiansProperty()
    {
        return $this->loadByProdi(BahanKajian::class);
    }

    public function getCapaianPembelajaranMatakuliahsProperty()
    {
        return $this->loadByProdi(CapaianPembelajaranMatakuliah::class);
    }

    public function getSubCapaianPembelajaranMatakuliahsProperty()
    {
        return $this->loadByProdi(SubCapaianPembelajaranMatakuliah::class);
    }

    public function getMatakuliahsProperty()
    {
        return $this->loadByProdi(Matakuliah::class)
            ->map(function ($mk) {
                $mk->description = $mk->programStudis
                    ->pluck('nama')
                    ->join(', ');
                return $mk;
            });
    }


    public function getMaktulSelectProperty()
    {
        return $this->loadByProdi(Matakuliah::class)
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

    public function openAddRelation(string $type, ?int $primaryId = null, ?int $secondaryId = null)
    {
        // Tentukan nama property modal & tempSelect & form mapping berdasarkan type
        $modalProperty = match ($type) {
            'cpl_bk_mk' => 'showModalCplBkMK',
            'cpl_cpmk_mk' => 'showModalCplCpmkMK',
            'cpmk_mk' => 'showModalCpmkMK',
            'bk_mk' => 'showModalBkMK',
            default => null
        };

        $tempProperty = match ($type) {
            'cpl_bk_mk' => 'tempSelectCplBkMK',
            'cpl_cpmk_mk' => 'tempSelectCplCpmkMK',
            'cpmk_mk' => 'tempSelectCpmkMK',
            'bk_mk' => 'tempSelectBkMK',
            default => null
        };

        if (!$modalProperty || !$tempProperty) {
            return;
        }

        // Aktifkan modal
        $this->$modalProperty = true;

        // Ambil data dari model sesuai tipe
        $primary = null;
        $secondary = null;

        switch ($type) {
            case 'cpl_bk_mk':
                $primary = $this->getCapaianPembelajaranLulusansProperty()->firstWhere('id', $primaryId);
                $secondary = $this->getBahanKajiansProperty()->firstWhere('id', $secondaryId);
                $this->$tempProperty = [
                    'cpl' => ['id' => $primary?->id, 'code' => $primary?->code],
                    'bk' => ['id' => $secondary?->id, 'code' => $secondary?->code],
                ];
                break;

            case 'cpl_cpmk_mk':
                $primary = $this->getCapaianPembelajaranLulusansProperty()->firstWhere('id', $secondaryId);
                $secondary = $this->getCapaianPembelajaranMatakuliahsProperty()->firstWhere('id', $primaryId);
                $this->$tempProperty = [
                    'cpl' => ['id' => $secondary?->id, 'code' => $secondary?->code],
                    'cpmk' => ['id' => $primary?->id, 'code' => $primary?->code],
                ];
                break;

            case 'cpmk_mk':
                $primary = $this->getCapaianPembelajaranMatakuliahsProperty()->firstWhere('id', $primaryId);
                $this->$tempProperty = [
                    'cpmk' => ['id' => $primary?->id, 'code' => $primary?->code],
                ];
                break;

            case 'bk_mk':
                $primary = $this->getBahanKajiansProperty()->firstWhere('id', $primaryId);
                $this->$tempProperty = [
                    'bk' => ['id' => $primary?->id, 'code' => $primary?->code, 'name' => $primary?->name],
                ];
                break;
        }

        // Inject data lama dari setTempSelect
        switch ($type) {
            case 'cpl_bk_mk':
                $this->form['cpl_bk_mk'][$secondaryId][$primaryId] =
                    $this->setTempSelectCplBkMK[$secondaryId][$primaryId]['id'] ?? [];
                break;
            case 'cpl_cpmk_mk':
                $this->form['cpl_cpmk_mk'][$secondaryId][$primaryId]
                    = $this->setTempSelectCplCpmkMK[$primaryId][$secondaryId]['id'] ?? [];
                break;
            case 'cpmk_mk':
                $this->form['cpmk_mk'][$primaryId] =
                    $this->setTempSelectCpmkMK[$primaryId]['id'] ?? [];
                break;
            case 'bk_mk':
                $this->form['bk_mk'][$primaryId] =
                    $this->setTempSelectBkMK[$primaryId]['id'] ?? [];
                break;
        }
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
    protected function openEdits(int $id, $activeTab = 0): void
    {
        $kurikulum = Kurikulum::with([
            'programStudis:id,name,code',
            'pivotPlCpl:id,kurikulum_id,pl_id,cpl_id',
            'pivotCplBk:id,kurikulum_id,bk_id,cpl_id',
            'pivotBkMk:id,kurikulum_id,bk_id,mk_id',
            'pivotCpmkSubCpmk:id,kurikulum_id,cpmk_id,subcpmk_id',
            'pivotCplMk:id,kurikulum_id,cpl_id,mk_id',
            'pivotCpmkMk:id,kurikulum_id,cpmk_id,mk_id',
            'pivotCplBkMk:id,kurikulum_id,cpl_id,bk_id,mk_id',
            'pivotCplCpmkMk:id,kurikulum_id,cpl_id,cpmk_id,mk_id',
        ])->findOrFail($id);

        switch ($activeTab) {
            case 0:
                $this->tabActive = 0;
                $this->form['name'] = $kurikulum->name;
                $this->form['year'] = $kurikulum->year;
                $this->form['version'] = $kurikulum->version;
                $this->form['type'] = $kurikulum->type;
                $this->form['status'] = $kurikulum->status;
                $this->form['created_by'] = $kurikulum->created_by;
                $this->form['programStudis'] = [$kurikulum->programStudis->id];
                break;
            case 1:
                $this->tabActive = 1;

                foreach ($kurikulum->pivotPlCpl as $pivot) {
                    $this->form['cpl_pl'][$pivot->cpl_id][] = $pivot->pl_id;
                }
                break;
            case 2:
                $this->tabActive = 2;
                // Bind pivot BK ↔ CPL
                foreach ($kurikulum->pivotCplBk as $pivot) {
                    $this->form['bk_cpl'][$pivot->bk_id][] = $pivot->cpl_id;
                }

                break;
            case 3:
                $this->tabActive = 3;
                foreach ($kurikulum->pivotBkMk as $pivot) {
                    $this->form['bk_mk'][$pivot->bk_id][] = $pivot->mk_id;
                }
                break;
            case 4:
                $this->tabActive = 4;
                // Bind pivot CPMK ↔ SubCPMK
                foreach ($kurikulum->pivotCpmkSubCpmk as $pivot) {
                    $this->form['cpmk_subcpmk'][$pivot->cpmk_id][] = $pivot->subcpmk_id;
                }
                break;
            case 5:
                $this->tabActive = 5;
                foreach ($kurikulum->pivotCplMk as $pivot) {
                    $this->form['mk_cpl'][$pivot->mk_id][] = $pivot->cpl_id;
                }
                break;
            case 6:
                $this->tabActive = 6;
                foreach ($kurikulum->pivotCpmkMk as $pivot) {
                    $this->form['cpmk_mk'][$pivot->mk_id][] = $pivot->cpmk_id;
                }
                break;
            case 7:
                $this->tabActive = 7;
                $kurikulumId = $id;

                $data = PivotBkMk::query()
                    ->where('pivot_bk_mks.kurikulum_id', $kurikulumId)
                    ->join('pivot_cpl_bks', function ($join) use ($kurikulumId) {
                        $join->on('pivot_bk_mks.bk_id', '=', 'pivot_cpl_bks.bk_id')
                            ->where('pivot_cpl_bks.kurikulum_id', $kurikulumId);
                    })
                    ->with([
                        'mk:id,code,name',
                        'bk:id,code,name',
                    ])
                    ->select(
                        'pivot_cpl_bks.cpl_id',
                        'pivot_bk_mks.bk_id',
                        'pivot_bk_mks.mk_id'
                    )
                    ->get();

                foreach ($data as $item) {
                    $this->form['cpl_bk_mk'][$item->bk_id][$item->cpl_id][] = $item->mk_id;
                }

                foreach ($this->form['cpl_bk_mk'] as $bkId => $cplIds) {

                    foreach ($cplIds as $cplId => $mkIds) {

                        $mkIds = array_values(array_unique($mkIds));

                        $mks = $this->getMatakuliahsProperty()
                            ->whereIn('id', $mkIds);

                        $this->setTempSelectCplBkMK[$bkId][$cplId] = [
                            'id' => $mkIds,
                            'code' => $mks->pluck('code')->values()->toArray(),
                        ];
                    }
                }
                break;
            case 8:
                $this->tabActive = 8;

                $kurikulum->pivotCplCpmkMk()
                    ->where('kurikulum_id', $id)
                    ->get()
                    ->each(function ($pivot) {
                        $this->form['cpl_cpmk_mk'][$pivot->mk_id][$pivot->cpmk_id][] = $pivot->cpl_id;
                    });

                break;
            case 9:
                $this->tabActive = 9;
                $matakuliahList = $this->getMatakuliahsProperty();
                foreach ($matakuliahList as $mk) {
                    $this->form['distribusi_mk'][$mk->id] = [
                        'sks' => $mk->sks ?? null,
                        'semester' => $mk->semester ?? null,
                    ];
                }
                break;
            default:
                $this->tabActive = 0;
        }

    }

    protected function buildPivotData(
        array $source,
        array $mapping,
        int $kurikulumId
    ): array {
        $result = [];

        foreach ($source as $key1 => $level2) {

            // level 1 → level 2 (array of ids)
            if (is_array($level2) && isset($mapping['level2'])) {
                foreach ((array) $level2 as $value2) {
                    $result[] = array_merge(
                        ['kurikulum_id' => $kurikulumId],
                        [
                            $mapping['level1'] => $key1,
                            $mapping['level2'] => $value2,
                        ]
                    );
                }
            }

            // level 1 → level 2 → level 3
            if (is_array($level2) && isset($mapping['level3'])) {
                foreach ($level2 as $key2 => $level3) {
                    foreach ((array) $level3 as $value3) {
                        $result[] = array_merge(
                            ['kurikulum_id' => $kurikulumId],
                            [
                                $mapping['level1'] => $key1,
                                $mapping['level2'] => $key2,
                                $mapping['level3'] => $value3,
                            ]
                        );
                    }
                }
            }
        }

        return $result;
    }

    protected function syncPivot(
        string $model,
        int $kurikulumId,
        array $data
    ): void {
        $model::where('kurikulum_id', $kurikulumId)->delete();

        if (!empty($data)) {
            $model::insert($data);
        }
    }

    public function saveByNextTab($currentKurikulumId = null, $tabActive = null)
    {
        $kurikulumId = $currentKurikulumId ?? $this->selectedId;
        $notify = [
            'icon' => 'success',
            'title' => 'Sukses!',
            'description' => "Data berhasil disimpan.",
        ];
        switch ($tabActive) {
            case 0:
                $this->form['created_by'] = auth()->user()->id;
                $this->form['status'] = 'draft';

                // Metadata Kurikulum
                $dataMetaDataKurikulum = [
                    'prodi_id' => $this->form['programStudis'][0] ?? null,
                    'name' => $this->form['name'],
                    'year' => $this->form['year'],
                    'version' => $this->form['version'],
                    'type' => $this->form['type'],
                    'status' => $this->form['status'],
                    'created_by' => $this->form['created_by'],
                ];
                if ($this->selectedId) {
                    $metaDataKur = Kurikulum::find($this->selectedId);
                    $metaDataKur->update($dataMetaDataKurikulum);
                    $this->selectedId = $metaDataKur->id;
                } else {
                    $metaDataKur = Kurikulum::create($dataMetaDataKurikulum);
                    $this->selectedId = $metaDataKur->id;
                }
                $notify['description'] = "Data metadata berhasil disimpan.";
                break;
            case 1:
                $dataPivotCplPl = $this->buildPivotData(
                    $this->form['cpl_pl'],
                    [
                        'level1' => 'cpl_id',
                        'level2' => 'pl_id',
                    ],
                    $kurikulumId
                );
                // dump($this->form['cpl_pl'],$dataPivotCplPl);
                // dump($dataPivotCplPl);
                $this->syncPivot(PivotPlCpl::class, $kurikulumId, $dataPivotCplPl);
                $notify['description'] = "Data Relasi CPL PL berhasil disimpan.";
                break;
            case 2:
                $dataPivotCplBk = $this->buildPivotData(
                    $this->form['bk_cpl'],
                    [
                        'level1' => 'bk_id',
                        'level2' => 'cpl_id',
                    ],
                    $kurikulumId
                );

                $this->syncPivot(PivotCplBk::class, $kurikulumId, $dataPivotCplBk);
                $notify['description'] = "Data Relasi CPL BK berhasil disimpan.";
                break;
            case 3:
                $dataPivotBkMk = $this->buildPivotData(
                    $this->form['bk_mk'],
                    [
                        'level1' => 'bk_id',
                        'level2' => 'mk_id',
                    ],
                    $kurikulumId
                );

                $this->syncPivot(PivotBkMk::class, $kurikulumId, $dataPivotBkMk);
                $notify['description'] = "Data Relasi BK MK berhasil disimpan.";

                break;
            case 4:
                $dataPivotCpmkSubCpmk = $this->buildPivotData(
                    $this->form['cpmk_subcpmk'],
                    [
                        'level1' => 'cpmk_id',
                        'level2' => 'subcpmk_id',
                    ],
                    $kurikulumId
                );

                $this->syncPivot(PivotCpmkSubcpmk::class, $kurikulumId, $dataPivotCpmkSubCpmk);
                $notify['description'] = "Data Relasi CPL BK berhasil disimpan.";

                break;
            case 5:
                $dataPivotCplMk = $this->buildPivotData(
                    $this->form['mk_cpl'],
                    [
                        'level1' => 'mk_id',
                        'level2' => 'cpl_id',
                    ],
                    $kurikulumId
                );

                $this->syncPivot(PivotCplMk::class, $kurikulumId, $dataPivotCplMk);
                $notify['description'] = "Data Relasi CPL MK berhasil disimpan.";

                break;
            case 6:
                $dataPivotCpmkMk = $this->buildPivotData(
                    $this->form['cpmk_mk'],
                    [
                        'level1' => 'mk_id',
                        'level2' => 'cpmk_id',
                    ],
                    $kurikulumId
                );
                $this->syncPivot(PivotCpmkMk::class, $kurikulumId, $dataPivotCpmkMk);
                $notify['description'] = "Data Relasi CPL MK berhasil disimpan.";
                break;
            case 7:

                $dataPivotCplBkMk = [];
                foreach ($this->form['cpl_bk_mk'] as $bkId => $cplArr) {
                    foreach ($cplArr as $cplId => $mkIds) {
                        foreach ((array) $mkIds as $mkId) {
                            $dataPivotCplBkMk[] = [
                                'kurikulum_id' => $kurikulumId,
                                'cpl_id' => $cplId,
                                'bk_id' => $bkId,
                                'mk_id' => $mkId,
                            ];
                        }
                    }
                }
                $this->syncPivot(PivotCplBkMk::class, $kurikulumId, $dataPivotCplBkMk);
                $notify['description'] = "Data Relasi CPL BK MK berhasil disimpan.";

                break;
            case 8:
                $dataPivotCplCpmkMk = [];
                foreach ($this->form['cpl_cpmk_mk'] as $mkId => $cpmkArr) {
                    foreach ($cpmkArr as $cpmkId => $cplIds) {
                        foreach ((array) $cplIds as $cplId) {
                            $dataPivotCplCpmkMk[] = [
                                'kurikulum_id' => $kurikulumId,
                                'cpl_id' => $cplId,
                                'cpmk_id' => $cpmkId,
                                'mk_id' => $mkId,
                            ];
                        }
                    }
                }

                $this->syncPivot(PivotCplCpmkMk::class, $kurikulumId, $dataPivotCplCpmkMk);
                $notify['description'] = "Data Relasi CPL CPLMK berhasil disimpan.";
                break;
            case 9:
                DB::transaction(function () {
                    $ids = array_keys($this->form['distribusi_mk']);

                    // Lock baris yang ada
                    $existing = Matakuliah::whereIn('id', $ids)
                        ->lockForUpdate()
                        ->get()
                        ->keyBy('id');

                    foreach ($this->form['distribusi_mk'] as $idMk => $item) {
                        if (!isset($existing[$idMk])) {
                            continue; // skip kalau tidak ada
                        }

                        $existing[$idMk]->update([
                            'sks' => $item['sks'],
                            'semester' => $item['semester'],
                        ]);
                    }
                });
                $notify['description'] = "Data Distribusi MK berhasil disimpan.";
                break;
            default:
                $notify['description'] = "Terjadi Kesalahan! Data belum tersimpan.";
                $notify['icon'] = 'error';
                $notify['title'] = 'Terjadi Kesalahan!';

                break;
        }

        $this->notification()->send([
            'icon' => $notify['icon'],
            'title' => $notify['title'],
            'description' => $notify['description'],
            'timeout' => 2500
        ]);
    }


    protected function beforeSaves(string $action, ?int $cloneKurikulumId = null)
    {
        // Set user & status default
        $this->form['created_by'] = auth()->user()->id;
        $this->form['status'] = 'draft';

        // Metadata Kurikulum
        $dataMetaDataKurikulum = [
            'prodi_id' => $this->form['programStudis'][0] ?? null,
            'name' => $this->form['name'],
            'year' => $this->form['year'],
            'version' => $this->form['version'],
            'type' => $this->form['type'],
            'status' => $this->form['status'],
            'created_by' => $this->form['created_by'],
            'parent_id' => $cloneKurikulumId, // jika clone
        ];

        // Buat Kurikulum baru / revisi
        $kurikulum = Kurikulum::create($dataMetaDataKurikulum);
        $kurikulumId = $kurikulum->id;

        // ===========================
        // Simpan semua pivot
        // ===========================

        foreach ($this->form['cpl_pl'] as $cplId => $plIds) {
            foreach ((array) $plIds as $plId) {
                $kurikulum->pivotPlCpl()->create([
                    'kurikulum_id' => $kurikulumId,
                    'pl_id' => $plId,
                    'cpl_id' => $cplId,
                ]);
            }
        }

        foreach ($this->form['bk_cpl'] as $bkId => $cplIds) {
            foreach ((array) $cplIds as $cplId) {
                $kurikulum->pivotCplBk()->create([
                    'kurikulum_id' => $kurikulumId,
                    'cpl_id' => $cplId,
                    'bk_id' => $bkId,
                ]);
            }
        }

        foreach ($this->form['bk_mk'] as $bkId => $mkIds) {
            foreach ((array) $mkIds as $mkId) {
                $kurikulum->pivotBkMk()->create([
                    'kurikulum_id' => $kurikulumId,
                    'bk_id' => $bkId,
                    'mk_id' => $mkId,
                ]);
            }
        }

        foreach ($this->form['cpmk_subcpmk'] as $cpmkId => $subcpmkIds) {
            foreach ((array) $subcpmkIds as $subcpmkId) {
                $kurikulum->pivotCpmkSubcpmk()->create([
                    'kurikulum_id' => $kurikulumId,
                    'cpmk_id' => $cpmkId,
                    'subcpmk_id' => $subcpmkId,
                ]);
            }
        }

        foreach ($this->form['mk_cpl'] as $mkId => $cplIds) {
            foreach ((array) $cplIds as $cplId) {
                $kurikulum->pivotCplMk()->create([
                    'kurikulum_id' => $kurikulumId,
                    'cpl_id' => $cplId,
                    'mk_id' => $mkId,
                ]);
            }
        }

        foreach ($this->form['cpmk_mk'] as $cpmkId => $mkIds) {
            foreach ((array) $mkIds as $mkId) {
                $kurikulum->pivotCpmkMk()->create([
                    'kurikulum_id' => $kurikulumId,
                    'cpmk_id' => $cpmkId,
                    'mk_id' => $mkId,
                ]);
            }
        }

        foreach ($this->form['cpl_bk_mk'] as $bkId => $cplArr) {
            foreach ($cplArr as $cplId => $mkIds) {
                foreach ((array) $mkIds as $mkId) {
                    $kurikulum->pivotCplBkMk()->create([
                        'kurikulum_id' => $kurikulumId,
                        'cpl_id' => $cplId,
                        'bk_id' => $bkId,
                        'mk_id' => $mkId,
                    ]);
                }
            }
        }

        foreach ($this->form['cpl_cpmk_mk'] as $cpmkId => $cplArr) {
            foreach ($cplArr as $cplId => $mkIds) {
                foreach ((array) $mkIds as $mkId) {
                    $kurikulum->pivotCplCpmkMk()->create([
                        'kurikulum_id' => $kurikulumId,
                        'cpl_id' => $cplId,
                        'cpmk_id' => $cpmkId,
                        'mk_id' => $mkId,
                    ]);
                }
            }
        }

        // 🔥 opsional: kembalikan kurikulum baru
        return $kurikulum;
    }
    public function saveKurikulum()
    {
        // Validasi form metadata sebelum simpan
        $this->validate($this->rules());
        $this->saveByNextTab($this->selectedId, $this->tabActive);



        // // Redirect ke list kurikulum atau detail kurikulum
        return redirect()->route('kurikulum.index');
    }
    // protected function cloneKurikulum(int $id): void
    // {
    //     $old = Kurikulum::with([
    //         'pivotPlCpl',
    //         'pivotCplBk',
    //         'pivotBkMk',
    //         'pivotCpmkSubCpmk',
    //         'pivotMkCpl',
    //         'pivotCpmkMk',
    //         'pivotCplBkMk',
    //         'pivotCplCpmkMk',
    //     ])->findOrFail($id);

    //     // 1️⃣ Buat kurikulum baru sebagai clone
    //     $new = Kurikulum::create([
    //         'prodi_id' => $old->prodi_id,
    //         'name' => $old->name,
    //         'year' => $old->year,
    //         'version' => $old->version,
    //         'parent_id' => $old->id, // parent = kurikulum lama
    //         'type' => $old->type,
    //         'status' => 'draft',
    //         'created_by' => auth()->id(),
    //     ]);

    //     $kurikulumId = $new->id;

    //     // 2️⃣ Copy pivot CPL ↔ PL
    //     foreach ($old->pivotPlCpl as $pivot) {
    //         $new->pivotPlCpl()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'pl_id' => $pivot->pl_id,
    //             'cpl_id' => $pivot->cpl_id,
    //         ]);
    //     }

    //     // 3️⃣ Copy pivot BK ↔ CPL
    //     foreach ($old->pivotCplBk as $pivot) {
    //         $new->pivotCplBk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'bk_id' => $pivot->bk_id,
    //             'cpl_id' => $pivot->cpl_id,
    //         ]);
    //     }

    //     // 4️⃣ Copy pivot BK ↔ MK
    //     foreach ($old->pivotBkMk as $pivot) {
    //         $new->pivotBkMk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'bk_id' => $pivot->bk_id,
    //             'mk_id' => $pivot->mk_id,
    //         ]);
    //     }

    //     // 5️⃣ Copy pivot CPMK ↔ SubCPMK
    //     foreach ($old->pivotCpmkSubCpmk as $pivot) {
    //         $new->pivotCpmkSubCpmk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'cpmk_id' => $pivot->cpmk_id,
    //             'subcpmk_id' => $pivot->subcpmk_id,
    //         ]);
    //     }

    //     // 6️⃣ Copy pivot MK ↔ CPL
    //     foreach ($old->pivotMkCpl as $pivot) {
    //         $new->pivotMkCpl()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'mk_id' => $pivot->mk_id,
    //             'cpl_id' => $pivot->cpl_id,
    //         ]);
    //     }

    //     // 7️⃣ Copy pivot CPMK ↔ MK
    //     foreach ($old->pivotCpmkMk as $pivot) {
    //         $new->pivotCpmkMk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'cpmk_id' => $pivot->cpmk_id,
    //             'mk_id' => $pivot->mk_id,
    //         ]);
    //     }

    //     // 8️⃣ Copy pivot CPL ↔ BK ↔ MK
    //     foreach ($old->pivotCplBkMk as $pivot) {
    //         $new->pivotCplBkMk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'cpl_id' => $pivot->cpl_id,
    //             'bk_id' => $pivot->bk_id,
    //             'mk_id' => $pivot->mk_id,
    //         ]);
    //     }

    //     // 9️⃣ Copy pivot CPL ↔ CPMK ↔ MK
    //     foreach ($old->pivotCplCpmkMk as $pivot) {
    //         $new->pivotCplCpmkMk()->create([
    //             'kurikulum_id' => $kurikulumId,
    //             'cpl_id' => $pivot->cpl_id,
    //             'cpmk_id' => $pivot->cpmk_id,
    //             'mk_id' => $pivot->mk_id,
    //         ]);
    //     }

    //     // 10️⃣ Bind ke form
    //     $this->mount($kurikulumId);
    // }


    public function getProdiProperty()
    {
        return ProgramStudi::all();
    }
    public function render()
    {
        return view('livewire.kurikulum.create-update');
    }
}
