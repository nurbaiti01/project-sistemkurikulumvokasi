<?php

namespace App\Livewire\PerangkatAjar\BebanAjar;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\BebanAjarDosen;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\Rps;
use App\Models\KontrakKuliah;
use App\Models\RealisasiPengajaran;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{
    use WithFileUploads;

    use WithFileUploads;

    public bool $showModal = false;
    public $file;

    public array $previewData = [];
    public array $errors = [];
    public int $successCount = 0;

    protected $rules = [
        'file' => 'required|mimes:xlsx,xls,csv|max:10240'
    ];
    protected array $requiredHeader = [
        'NIDN',
        'NAMA DOSEN',
        'KODE PRODI DOSEN',
        'KODE MATAKULIAH',
        'NAMA MATAKULIAH',
        'TAHUN AJARAN',
        'PERAN AJAR',
        'KELAS',
    ];

    public string $title = 'Bahan Kajian';
    /* ----------------------------------------
     | Model & View
     |---------------------------------------- */
    protected static string $model = BebanAjarDosen::class;
    protected static string $view = 'livewire.perangkat-ajar.beban-ajar.index';

    protected array $filterable = [
        'prodi' => [
            'type' => 'relation',
            'relation' => 'homeProdi',
            'column' => 'program_studis.id',
        ],
        'dosen' => [
            'type' => 'relation',
            'relation' => 'dosen',
            'column' => 'dosens.id',
        ],
    ];

    /**
     * daftar kolom pencarian
     */
    protected array $searchable = [
        'matakuliah' => [
            'type' => 'relation',
            'relation' => 'matakuliah',
            'column' => 'matakuliahs.id',
        ],
        'dosen' => [
            'type' => 'relation',
            'relation' => 'dosen',
            'column' => 'dosens.id',
        ],
    ];

    /**
     * nilai default filter
     */
    public array $filter = [
        'prodi' => null,
        'dosen' => null
    ];

    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
    }
    protected function beforeSetFilterProdi(): void
    {
        if (session('active_role') == 'Dosen') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->filter['prodi'] = $programStudi?->id;

            return;
        }
    }

    public function openModal()
    {
        $this->resetState();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }

    protected function resetState()
    {
        $this->reset([
            'file',
            'previewData',
            'errors',
            'successCount'
        ]);
    }

    public function uploadPreview()
    {
        $this->validate();

        $rows = Excel::toCollection(null, $this->file)[0];

        $header = $rows->shift()
            ->map(fn($h) => trim($h));

        if ($header->toArray() !== $this->requiredHeader) {
            $this->errors[] = "Format header tidak sesuai template";
            dump($header->toArray(), $this->requiredHeader);
            return;
        }

        $this->previewData = [];
        $this->errors = [];

        foreach ($rows as $i => $row) {
            try {
                $this->previewData[] = $this->mapPreviewRow(
                    $header,
                    $row,
                    $i + 2
                );
            } catch (\Throwable $e) {
                $this->errors[] = "Row " . ($i + 2) . ": " . $e->getMessage();
            }
        }
    }

    protected function mapPreviewRow(Collection $header, Collection $row, int $line)
    {
        $data = array_combine(
            $header->toArray(),
            $row->toArray()
        );

        /* =====================
           DOSEN
        ===================== */
        $dosen = Dosen::where('nidn', trim($data['NIDN']))->first();
        if (!$dosen) {
            throw new \Exception("Row {$line}: Dosen tidak ditemukan");
        }

        /* =====================
           HOME PRODI
           = dari Excel (kode prodi dosen)
        ===================== */
        $homeProdi = ProgramStudi::where('code', trim($data['KODE PRODI DOSEN']))->first();
        if (!$homeProdi) {
            throw new \Exception("Row {$line}: Prodi dosen tidak ditemukan");
        }

        /* =====================
           MATAKULIAH
        ===================== */
        $mk = Matakuliah::where('code', trim($data['KODE MATAKULIAH']))->first();
        if (!$mk) {
            throw new \Exception("Row {$line}: Matakuliah tidak ditemukan");
        }

        /* =====================
           TAUGHT PRODI
           = dari RELASI MK → Program Studi
        ===================== */
        $taughtProdi = $mk->programStudis()->first();
        if (!$taughtProdi) {
            throw new \Exception("Row {$line}: Matakuliah belum terhubung ke Program Studi");
        }

        /* =====================
           SEMESTER & SKS
           = dari tabel MK
        ===================== */
        if (!$mk->semester || $mk->semester < 1) {
            throw new \Exception("Row {$line}: Semester MK belum valid");
        }

        if (!$mk->sks || $mk->sks <= 0) {
            throw new \Exception("Row {$line}: SKS MK belum valid");
        }

        /* =====================
           PERAN
           = dari Excel
        ===================== */
        $peran = trim($data['PERAN AJAR']);
        if (!in_array($peran, ['koordinator', 'pengampu', 'asisten'])) {
            throw new \Exception("Row {$line}: Peran invalid");
        }

        return [
            'dosen_id' => $dosen->id,
            'dosen' => $dosen->name,

            'matakuliah_id' => $mk->id,
            'mk' => $mk->name,

            // SOURCE OF TRUTH
            'home_prodi_id' => $homeProdi->id,     // dari Excel
            'home_prodi' => $homeProdi->name,
            'taught_prodi_id' => $taughtProdi->id,  // dari relasi MK
            'taught_prodi' => $taughtProdi->name,

            'tahun_ajaran' => trim($data['TAHUN AJARAN']),

            // dari MK
            'semester' => $mk->semester,
            'sks_beban' => $mk->sks,

            'peran' => $peran,
            'kelas' => trim($data['KELAS']),
        ];
    }



    /* ===================== SAVE ===================== */

    public function saveAll()
    {
        if (empty($this->previewData)) {
            $this->errors[] = "Tidak ada data untuk disimpan";
            return;
        }

        DB::transaction(function () {
            foreach ($this->previewData as $row) {

                // 1. UPSERT BEBAN AJAR
                $bebanAjar = BebanAjarDosen::updateOrCreate(
                    [
                        'dosen_id' => $row['dosen_id'],
                        'matakuliah_id' => $row['matakuliah_id'],
                        'taught_prodi_id' => $row['taught_prodi_id'],
                        'home_prodi_id' => $row['home_prodi_id'],
                    ],
                    [
                        'semester' => $row['semester'],
                        'tahun_ajaran' => $row['tahun_ajaran'],
                        'kelas' => $row['kelas'],
                        'sks_beban' => $row['sks_beban'],
                        'peran' => $row['peran'],
                    ]
                );

                // 2. CREATE / ENSURE TURUNAN
                $this->ensureRps($row);
                $this->ensureKontrakKuliah($row);
                $this->ensureRealisasiKuliah($row);
            }

            $this->successCount = count($this->previewData);
        });

        $this->previewData = [];
    }


    protected function ensureRps(array $row): void
    {
        Rps::firstOrCreate(
            [
                'matakuliah_id' => $row['matakuliah_id'],
                'program_studi_id' => $row['home_prodi_id'],
                'class' => $row['kelas'],
                'dosen_id' => $row['dosen_id'],
                'academic_year' => $row['tahun_ajaran'],
            ],
            [
                'revision' => 1,
                'learning_method' => '',
                'learning_experience' => '',
                'cpmk_bobot' => [],
            ]
        );
    }


    protected function ensureKontrakKuliah(array $row): void
    {
        KontrakKuliah::firstOrCreate(
            [
                'prodi_id' => $row['home_prodi_id'],
                'matakuliah_id' => $row['matakuliah_id'],
                'dosen_id' => $row['dosen_id'],
                'tahun_akademik' => $row['tahun_ajaran'],
                'kelas' => $row['kelas'],
            ],
            [
                'total_jam' => 0,
                'tujuan_pembelajaran' => '',
                'strategi_perkuliahan' => '',
                'materi_pembelajaran' => '',
                'kriteria_penilaian' => '',
                'tata_tertib' => '',
            ]
        );
    }


    protected function ensureRealisasiKuliah(array $row): void
    {
        RealisasiPengajaran::firstOrCreate(
            [
                'program_studi_id' => $row['home_prodi_id'],
                'matakuliah_id' => $row['matakuliah_id'],
                'dosen_id' => $row['dosen_id'],
                'tahun_akademik' => $row['tahun_ajaran'],
                'kelas' => $row['kelas'],
            ],
            [
                'semester' => $row['semester'],
                'jumlah_sks' => $row['sks_beban'],
            ]
        );
    }


}
