<?php

namespace App\Livewire\PerangkatAjar\Rps;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Matakuliah;
use App\Models\Kurikulum;
use App\Models\PivotCplCpmkMk;
use App\Models\Rps;
use App\Models\RpsPertemuan;
use App\Models\RpsPenilaian;
use App\Models\RpsReferensi;
use App\Models\RpsApproval;
use Illuminate\Support\Facades\DB;

#[Title('Create Kurikulum')]
#[Layout('components.layouts.sidebar')]
class Create extends Component
{
    const MENIT_PER_JAM_AKADEMIK = 170;

    public array $jenisAlokasi = [
        'PB' => 'Pembelajaran Tatap Muka',
        'PT' => 'Tugas Terstruktur',
        'BM' => 'Belajar Mandiri',
        'ASESMEN' => 'Asesmen',
    ];
    public $listMk = [];

    public $activeProdi = null;

    public $form = [
        'matakuliah_id' => null,
        'program_studi_id' => null,
        'kelas' => null,
        'dosen_id' => null,
        'tahun_akademik' => null,
        'revisi' => 0,
        'metode_pembelajaran' => '',
        'pengalaman_belajar_mahasiswa' => '',
        'cpmks' => [],
    ];

    public $activeProdiName = null;
    public $activeDosenName = null;
    public $indentitasMk = [];
    public $dataKurikulum = [];
    public $viewTable = true;
    public $matriksCplCpmk = [
        'cpl' => [],
        'cpmk' => [],
        'matrix' => []
    ];

    public $pertemuans = [
        [
            'show' => true,
            'pertemuan_ke' => 1,
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
        ],
    ];
    public $asesmen = [
        'uts' => true,
        'uas' => true,
    ];

    public $rpsSummary = [
        'blok' => 5,
        'jam_per_blok' => 6,
        'menit_per_jam' => 170,
        'asesmen' => 2,
        'jam_asesmen' => 2,
    ];

    public $metodePembelajaran = [
        'scl' => true,
        'deskripsi' => '',
    ];

    public $pengalamanBelajar = '';

    public $referensi = [
        'utama' => [''],
        'pendukung' => [''],
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


    public function mount()
    {
        $this->setFilterProdi();
        $this->listMk = $this->getListMatakuliah()->get();

    }

    protected function getListMatakuliah()
    {

        return Matakuliah::query()
            ->with(['programStudis', 'MkCpmk.cpmk', 'MkCpl.cpl'])
            ->when($this->activeProdi, function ($q) {
                $q->whereHas('programStudis', function ($q) {
                    $q->where('program_studis.id', $this->activeProdi);
                });
            });
    }

    protected function getKurikulumPublishedByMk($matakuliahId)
    {
        return Kurikulum::query()
            ->where('status', 'published')
            ->when(
                $this->activeProdi,
                fn($q) =>
                $q->whereHas(
                    'programStudis',
                    fn($q) =>
                    $q->where('program_studis.id', $this->activeProdi)
                )
            )
            ->whereHas(
                'pivotCpmkMk',
                fn($q) =>
                $q->where('pivot_cpmk_mks.mk_id', $matakuliahId)
            )
            ->with([
                'pivotCpmkMk' => fn($q) =>
                    $q->where('mk_id', $matakuliahId)->with('cpmk'),
                'pivotCplMk' => fn($q) =>
                    $q->where('mk_id', $matakuliahId)->with('cpl'),
            ])
            ->first();
    }

    protected function setFilterProdi(): void
    {
        if (in_array(session('active_role'), ['Dosen', 'Kaprodi'])) {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();
            $this->activeProdi = $programStudi?->id;
            $this->form['program_studi_id'] = $programStudi?->id;
            $this->form['dosen_id'] = auth()->user()->dosenId();
            $this->activeDosenName = auth()->user()->dosenName();
            $this->activeProdiName = $programStudi?->name;
            return;
        }

    }
    protected function matriksCplCpmkData($mkId, $kurikulumId)
    {

        $matakuliah = Matakuliah::with([
            'MkCpmk' => fn($q) =>
                $q->where('kurikulum_id', $kurikulumId)
                    ->with('cpmk'),

            'MkCpl' => fn($q) =>
                $q->where('kurikulum_id', $kurikulumId)
                    ->with('cpl'),
        ])
            ->find($mkId);

        if ($matakuliah) {
            $cplList = $matakuliah->MkCpl->pluck('cpl', 'cpl_id');
            $cpmkList = $matakuliah->MkCpmk->pluck('cpmk', 'cpmk_id');
            $matakuliah->cplMap = $matakuliah->MkCpl->keyBy('cpl_id');
            $matakuliah->cpmkMap = $matakuliah->MkCpmk->keyBy('cpmk_id');
        }

        $pivot = PivotCplCpmkMk::query()
            ->where('kurikulum_id', $kurikulumId)
            ->where('mk_id', $mkId)
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

        $this->matriksCplCpmk = [
            'cpmk' => $matakuliah->cpmkMap,
            'cpl' => $matakuliah->cplMap,
            'matrix' => $matrix
        ];
        $this->form['cpmks'] = $matakuliah->MkCpmk
            ->mapWithKeys(fn($item) => [
                $item->cpmk_id => [
                    'cpmk_id' => $item->cpmk_id,
                    'bobot' => 0,
                ]
            ])
            ->toArray();
    }
    public function updating($key, $value)
    {
        if ($key !== 'form.matakuliah_id') {
            return;
        }

        if (!$value) {
            $this->resetMatakuliahData();
            return;
        }

        $this->indentitasMk = $this->getListMatakuliah()->find($value);
        $this->dataKurikulum = $this->getKurikulumPublishedByMk($value);

        if ($this->dataKurikulum) {
            $this->matriksCplCpmkData($value, $this->dataKurikulum['id']);
        }
    }

    protected function resetMatakuliahData(): void
    {
        $this->indentitasMk = [];
        $this->dataKurikulum = [];
        $this->matriksCplCpmk = [
            'cpl' => [],
            'cpmk' => [],
            'matrix' => []
        ];
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
    }


    public function removePertemuan($index)
    {
        if (count($this->pertemuans) <= 1) {
            return;
        }

        unset($this->pertemuans[$index]);
        $this->pertemuans = array_values($this->pertemuans);
    }

    public function addAlokasi($pIndex)
    {
        $this->pertemuans[$pIndex]['alokasi'][] = [
            'tipe' => 'PB',
            'jam' => 1,
        ];
    }

    public function removeAlokasi($pIndex, $aIndex)
    {
        if (count($this->pertemuans[$pIndex]['alokasi']) <= 1) {
            return;
        }

        unset($this->pertemuans[$pIndex]['alokasi'][$aIndex]);
        $this->pertemuans[$pIndex]['alokasi'] = array_values(
            $this->pertemuans[$pIndex]['alokasi']
        );
    }

    public function totalJamPertemuan($pIndex): int
    {
        return collect($this->pertemuans[$pIndex]['alokasi'])
            ->sum(fn($row) => (int) $row['jam']);
    }

    public function totalMenitPertemuan($pIndex): int
    {
        return collect($this->pertemuans[$pIndex]['alokasi'])
            ->sum(fn($a) => $a['jumlah'] * $a['menit']);
    }

    public function totalMenitSemester(): int
    {
        return collect($this->pertemuans)
            ->keys()
            ->sum(fn($i) => $this->totalMenitPertemuan($i));
    }

    public function totalJamSemester(): int
    {
        return floor($this->totalMenitSemester() / 60);
    }
    public function totalMenitAsesmen(): int
    {
        $jumlah = collect($this->asesmen)->filter()->count();
        return $jumlah * 2 * self::MENIT_PER_JAM_AKADEMIK;
    }
    public function rpsFormula()
    {
        $totalMenit = $this->totalMenitSemester();

        $blok = 5;
        $jamPerBlok = 6;
        $menitPerJam = 170;

        return [
            'blok' => $blok,
            'jam' => $jamPerBlok,
            'menit' => $menitPerJam,
            'asesmen' => 2,
            'jam_asesmen' => 2,
            'total_menit' => $totalMenit,
            'total_jam' => floor($totalMenit / 60),
        ];
    }
    public function addReferensiUtama(): void
    {
        $this->referensi['utama'][] = '';
    }

    public function addReferensiPendukung(): void
    {
        $this->referensi['pendukung'][] = '';
    }

    public function removeReferensiUtama(int $index): void
    {
        if (count($this->referensi['utama']) <= 1) {
            return;
        }

        unset($this->referensi['utama'][$index]);
        $this->referensi['utama'] = array_values($this->referensi['utama']);
    }

    public function removeReferensiPendukung(int $index): void
    {
        if (count($this->referensi['pendukung']) <= 1) {
            return;
        }

        unset($this->referensi['pendukung'][$index]);
        $this->referensi['pendukung'] = array_values($this->referensi['pendukung']);
    }

    public function totalCpmkPersentase($key): int
    {
        return collect($this->penilaian[$key]['cpmk'])->sum();
    }

    public function save()
    {
        $this->validate(
            [
                'form.matakuliah_id' => 'required',
                'form.program_studi_id' => 'required',
                'form.kelas' => 'required',
                'form.tahun_akademik' => 'required',
                'form.dosen_id' => 'required',
                'form.metode_pembelajaran' => 'required',
                'form.pengalaman_belajar_mahasiswa' => 'required',
            ]
        );
        DB::transaction(function () {
            $rps = $this->saveRpsMaster();
            $this->saveRpsPertemuans($rps->id);
            $this->saveRpsReferensi($rps->id);
            $this->saveRpsPenilaian($rps->id);
            $this->createRpsAproval($rps->id);
        });
    }
    protected function saveRpsMaster()
    {
        return Rps::create([
            'matakuliah_id' => $this->form['matakuliah_id'],
            'program_studi_id' => $this->form['program_studi_id'],
            'class' => $this->form['kelas'],
            'dosen_id' => $this->form['dosen_id'],
            'academic_year' => $this->form['tahun_akademik'],
            'revision' => $this->form['revisi'] ?? 0,
            'learning_method' => $this->form['metode_pembelajaran'],
            'learning_experience' => $this->form['pengalaman_belajar_mahasiswa'],
        ]);
    }

    protected function saveRpsPertemuans(int $rpsId)
    {
        foreach ($this->pertemuans as $p) {
            $rpsPertemuan = RpsPertemuan::create([
                'rps_id' => $rpsId,
                'pertemuan_ke' => $p['pertemuan_ke'],
                'materi_ajar' => $p['materi_ajar'],
                'indikator' => $p['indikator'],
                'bentuk_pembelajaran' => $p['bentuk_pembelajaran'],
                'cpmk_id' => $p['cpmk_id'],
                'pemberian_tugas' => $p['pemberian_tugas'],
                'alokasi' => $p['alokasi'], // simpan json
                'bobots' => $p['bobots'],
                'rancangan_penilaian' => $p['rancangan_penilaian'],  // simpan json
            ]);
        }
    }

    protected function saveRpsReferensi(int $rpsId)
    {
        foreach ($this->referensi as $jenis => $items) {
            foreach ($items as $deskripsi) {
                if (!trim($deskripsi))
                    continue;

                RpsReferensi::create([
                    'rps_id' => $rpsId,
                    'jenis' => $jenis, // utama | pendukung
                    'deskripsi' => $deskripsi,
                ]);
            }
        }
    }

    protected function saveRpsPenilaian(int $rpsId)
    {
        foreach ($this->penilaian as $jenis => $item) {
            foreach ($item['cpmk'] as $cpmkId => $nilai) {

                RpsPenilaian::create([
                    'rps_id' => $rpsId,
                    'jenis_penilaian' => $jenis,
                    'cpmk_id' => $cpmkId,
                    'persentase_penilaian' => $item['persentase'],
                    'bobot_cpmk' => $nilai,
                    'kelompok' => in_array($jenis, $this->kelompokPenilaian['kognitif'])
                        ? 'kognitif'
                        : 'default',
                ]);
            }
        }
    }

    protected function createRpsAproval(int $rpsId)
    {
        RpsApproval::create([
            'rps_id' => $rpsId,
            'role_proses' => 'perumusan',
            'dosen_id' => auth()->user()->dosenId(),
            'status' => 'Pending',
            'approved' => 1,
            'approved_at' => now()
        ]);
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.create');
    }
}
