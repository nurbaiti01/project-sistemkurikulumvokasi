<?php

namespace App\Livewire\PerangkatAjar\Rps;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Matakuliah;
use App\Models\Kurikulum;
use App\Models\PivotCplCpmkMk;
use App\Models\Rps;
use App\Models\RpsApproval;
use WireUi\Traits\WireUiActions;
use Flux\Flux;
#[Title('View RPS')]
#[Layout('components.layouts.sidebar')]
class View extends Component
{
    use WireUiActions;
    const MENIT_PER_JAM_AKADEMIK = 170;
    public Rps $rps;

    public $selectedId = null;
    public $catatan = '';
    public array $form = [];
    public array $pertemuans = [];
    public array $referensi = ['utama' => [], 'pendukung' => []];
    public array $penilaian = [];
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

    public array $jenisAlokasi = [
        'PB' => 'Pembelajaran Tatap Muka',
        'PT' => 'Tugas Terstruktur',
        'BM' => 'Belajar Mandiri',
        'ASESMEN' => 'Asesmen',
    ];
    public $rpsSummary = [
        'blok' => 5,
        'jam_per_blok' => 6,
        'menit_per_jam' => 170,
        'asesmen' => 2,
        'jam_asesmen' => 2,
    ];

    public $listMk = [];
    public $activeProdi = null;
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

    public function mount($id)
    {
        $this->setFilterProdi();
        $this->initRpsState();
        $this->loadRps($id);
        // Map referensi
        foreach ($this->rps->referensis as $ref) {
            $this->referensi[$ref->jenis][] = $ref->deskripsi;
        }


        $this->initPenilaian();
        $this->loadPenilaianFromDb();
        // Load RPS beserta relasinya
        // $this->rps = Rps::with(['pertemuans', 'referensis', 'penilaians', 'rpsApprovals.dosen'])->findOrFail($id);

        // // Map master form
        // $this->form = [
        //     'matakuliah_id' => $this->rps->matakuliah_id,
        //     'program_studi_id' => $this->rps->program_studi_id,
        //     'kelas' => $this->rps->class,
        //     'dosen_id' => $this->rps->dosen_id,
        //     'tahun_akademik' => $this->rps->academic_year,
        //     'revisi' => $this->rps->revision,
        //     'metode_pembelajaran' => $this->rps->learning_method,
        //     'pengalaman_belajar_mahasiswa' => $this->rps->learning_experience,
        //     'cpmk_bobot' => $this->rps->cpmk_bobot,
        // ];

        // // Map pertemuan
        // $this->pertemuans = $this->rps->pertemuans->map(function ($p) {
        //     return [
        //         'id' => $p->id,
        //         'pertemuan_ke' => $p->pertemuan_ke,
        //         'materi_ajar' => $p->materi_ajar,
        //         'indikator' => $p->indikator,
        //         'bentuk_pembelajaran' => $p->bentuk_pembelajaran,
        //         'pemberian_tugas' => $p->pemberian_tugas,
        //         'cpmk_id' => $p->cpmk_id,
        //         'alokasi' => $p->alokasi,
        //         'bobots' => $p->bobots,
        //     ];
        // })->toArray();

        // // Map referensi
        // foreach ($this->rps->referensis as $ref) {
        //     $this->referensi[$ref->jenis][] = $ref->deskripsi;
        // }

        // // Map penilaian
        // foreach ($this->rps->penilaians as $pen) {
        //     $this->penilaian[$pen->jenis_penilaian]['persentase'] = $pen->persentase_penilaian;
        //     $this->penilaian[$pen->jenis_penilaian]['cpmk'][$pen->cpmk_id] = $pen->bobot_cpmk;
        // }
        $this->updating('form.matakuliah_id', $this->form['matakuliah_id']);
    }

    protected function initRpsState(): void
    {
        // Form default
        $this->form = [
            'matakuliah_id' => null,
            'program_studi_id' => null,
            'kelas' => '',
            'dosen_id' => null,
            'tahun_akademik' => '',
            'revisi' => '',
            'metode_pembelajaran' => '',
            'pengalaman_belajar_mahasiswa' => '',
            'cpmks' => [], // JSON safe
        ];

        // Minimal 1 pertemuan row
        // Referensi by jenis
        // $this->referensi = [
        //     'buku' => [],
        //     'jurnal' => [],
        //     'web' => [],
        // ];
    }
    protected function emptyPertemuanRow(): array
    {
        return [
            'show' => true,
            'id' => null,
            'pertemuan_ke' => null,
            'materi_ajar' => '',
            'indikator' => '',
            'bentuk_pembelajaran' => '',
            'pemberian_tugas' => '',
            'selected_bobot_index' => null,
            'cpmk_id' => null,
            'alokasi' => 0,
            'bobots' => [],
            'rancangan_penilaian' => [
                'jenis' => '',
                'bentuk' => '',
                'bobot' => 0,
                'topik' => '',
            ],
        ];
    }

    protected function loadRps(int $id): void
    {
        $this->rps = Rps::with(['pertemuans', 'referensis', 'penilaians'])
            ->findOrFail($id);

        // =====================
        // FORM
        // =====================
        $this->form = array_merge($this->form, [
            'matakuliah_id' => $this->rps->matakuliah_id,
            'program_studi_id' => $this->rps->program_studi_id,
            'kelas' => $this->rps->class,
            'dosen_id' => $this->rps->dosen_id,
            'tahun_akademik' => $this->rps->academic_year,
            'revisi' => $this->rps->revision,
            'metode_pembelajaran' => $this->rps->learning_method,
            'pengalaman_belajar_mahasiswa' => $this->rps->learning_experience,
            'cpmks' => is_array($this->rps->cpmk_bobot)
                ? $this->rps->cpmk_bobot
                : json_decode($this->rps->cpmk_bobot ?? '[]', true),
        ]);

        // =====================
        // PERTEMUAN
        // =====================
        if ($this->rps->pertemuans->isNotEmpty()) {
            $this->pertemuans = $this->rps->pertemuans
                ->map(fn($p) => $this->mapPertemuan($p))
                ->toArray();
        }
        // $this->addPertemuan();

        // =====================
        // REFERENSI
        // =====================
        // foreach ($this->rps->referensis as $ref) {
        //     $this->referensi[$ref->jenis][] = $ref->deskripsi;
        // }
    }

    protected function mapPertemuan($p): array
    {
        return [
            'show' => true,
            'id' => $p->id,
            'pertemuan_ke' => $p->pertemuan_ke,
            'materi_ajar' => $p->materi_ajar,
            'indikator' => $p->indikator,
            'bentuk_pembelajaran' => $p->bentuk_pembelajaran,
            'pemberian_tugas' => $p->pemberian_tugas,
            'selected_bobot_index' => null,
            'cpmk_id' => $p->cpmk_id,
            'alokasi' => $p->alokasi,
            'bobots' => $p->bobots ?? [],
            'rancangan_penilaian' => [
                'jenis' => data_get($p, 'rancangan_penilaian.jenis', ''),
                'bentuk' => data_get($p, 'rancangan_penilaian.bentuk', ''),
                'bobot' => (int) data_get($p, 'rancangan_penilaian.bobot', 0),
                'topik' => data_get($p, 'rancangan_penilaian.topik', ''),
            ],
        ];
    }


    protected function initPenilaian(): void
    {
        $this->penilaian = [];

        foreach ($this->kelompokPenilaian as $group => $items) {
            foreach ($items as $key) {
                $this->penilaian[$key] = [
                    'persentase' => 0,
                    'cpmk' => []
                ];
            }
        }
    }

    protected function loadPenilaianFromDb(): void
    {
        foreach ($this->rps->penilaians as $pen) {
            $this->penilaian[$pen->jenis_penilaian]['persentase']
                = $pen->persentase_penilaian ?? 0;

            $this->penilaian[$pen->jenis_penilaian]['cpmk'][$pen->cpmk_id]
                = $pen->bobot_cpmk ?? 0;
        }
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


    public function totalCpmkPersentase($key): int
    {
        return collect($this->penilaian[$key]['cpmk'])->sum();
    }

    public function canSubmit($row): bool
    {
        return $row->status === 'pending'
            && $row->role_proses === 'perumusan'
            && session('active_role') === 'Dosen';
    }

    public function canApprove($row): bool
    {
        $map = [
            'pemeriksaan' => 'Kaprodi',
            'persetujuan' => 'WADIR 1',
            'penetapan' => 'Direktur',
            'pengendalian' => 'BPM',
        ];

        return $row->status === 'pending'
            && isset($map[$row->role_proses])
            && session('active_role') === $map[$row->role_proses];
    }

    public function submit($id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Save the information?',
            'acceptLabel' => 'Yes, save it',
            'method' => 'save',
            'params' => $id,
        ]);
    }

    public function rejected($id)
    {
        $this->selectedId = $id;
        Flux::modal('rejectedRps')->show();
    }

    public function saveRejected()
    {
        $approvals = RpsApproval::find($this->selectedId);
        $approvals->update([
            'dosen_id' => auth()->user()->dosenId(),
            'status' => 'rejected',
            'approved_at' => now(),
            'catatan' => $this->catatan,
            'approved' => 0
        ]);
        $approvals->rps->update([
            'status' => 'rejected'
        ]);
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'RPS Rejected Successfully',
        ]);
        $this->mount($approvals->rps_id);
        $this->selectedId = null;
        $this->catatan = null;
        Flux::modals()->close();
    }

    public function save($data)
    {
        $approvals = RpsApproval::find($data);
        $approvals->update([
            'dosen_id' => auth()->user()->dosenId(),
            'status' => 'approved',
            'approved_at' => now(),
            'approved' => 1
        ]);
        if ($approvals->role_proses == 'perumusan') {
            $approvals->rps->update([
                'status' => 'submitted'
            ]);
        }
        if ($approvals->role_proses == 'pengendalian') {
            $approvals->rps->update([
                'status' => 'published'
            ]);
        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'RPS Approved Successfully',
        ]);
        $this->mount($approvals->rps_id);
    }

    public function download()
    {
        return response()->streamDownload(function () {
            echo '...'; // Echo download contents directly...
        }, 'invoice.pdf');
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.view');
    }
}
