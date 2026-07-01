<?php

namespace App\Livewire\PerangkatAjar\Rps;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
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

    public bool $viewTable = true;
    public int $jumlahPertemuan = 1;
    public array $pertemuans = [];
    public array $cpmkList = [];
    public array $cplList = [];
    public array $matriksCpmkCpl = [];
    public array $form = [];

    public int $totalMenitSemester = 0;
    public int $totalJamSemester = 0;
    public ?int $matakuliahId = null;
    public ?int $programStudiId = null;
    public ?int $kurikulumId = null;
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
    protected $listeners = [
        'cpmkCplMatrikUpdated',
        'pertemuanUpdated',
        'matakuliahUpdated',
        'totalMenitSemesterUpdated',
        'totalJamSemesterUpdated',
        'formDataReady'
    ];

    public array $payload = [];

    public function cpmkCplMatrikUpdated($cpmkList, $cplList, $matriksCpmkCpl)
    {
        $this->cpmkList = $cpmkList;
        $this->cplList = $cplList;
        $this->matriksCpmkCpl = $matriksCpmkCpl;

    }

    public function pertemuanUpdated(array $pertemuans)
    {
        $this->pertemuans = $pertemuans;
    }

    public function matakuliahUpdated(array $params): void
    {
        $this->matakuliahId = $params['matakuliahId'] ?? null;
        $this->programStudiId = $params['programStudiId'] ?? null;
        $this->kurikulumId = $params['kurikulumId'] ?? null;
    }

    public function totalMenitSemesterUpdated(int $totalMenitSemester): void
    {
        $this->totalMenitSemester = $totalMenitSemester;
    }

    public function totalJamSemesterUpdated(int $totalJamSemester): void
    {
        $this->totalJamSemester = $totalJamSemester;
    }

    public function save(): void
    {
        // reset payload
        $this->payload = [];

        // minta semua child kirim data
        $this->dispatch('requestFormData');

        // tunggu → lalu proses save (via listener terakhir)
    }

    public function formDataReady(string $section, array $data): void
    {
        $this->payload[$section] = $data;

        // dump($section, $data);

        // cek apakah semua child sudah kirim
        if ($this->allSectionsReady()) {
            $this->persist();
        }
    }

    protected function allSectionsReady(): bool
    {
        if ($this->viewTable) {
            return collect([
                'identitas',
                'cpl_cpmk',
                'pertemuan_table',
                'learning_method_exp',
                'penilaian'
            ])->every(fn($key) => array_key_exists($key, $this->payload));
        }
        return collect([
            'identitas',
            'cpl_cpmk',
            'pertemuan_grid',
            'learning_method_exp',
            'penilaian'
        ])->every(fn($key) => array_key_exists($key, $this->payload));
    }

    protected function validateGlobal(array $data): array
    {
        $validator = Validator::make($data, [

            // =====================
            // FORM / IDENTITAS RPS
            // =====================
            'form.matakuliah_id' => ['required', 'integer', 'exists:matakuliahs,id'],
            'form.program_studi_id' => ['required', 'integer', 'exists:program_studis,id'],

            'form.class' => ['required', 'string', 'max:20'],
            'form.dosen_id' => ['required', 'integer', 'exists:dosens,id'],
            'form.academic_year' => ['required', 'string'],
            'form.revision' => ['required', 'integer', 'min:0'],

            // =====================
            // METODE & PENGALAMAN
            // =====================
            'form.learning_method' => ['required', 'string'],
            'form.learning_experience' => ['required', 'string'],

            // =====================
            // CPMK BOBOT
            // =====================
            'form.cpmk_bobot' => ['required', 'array', 'min:1'],
            'form.cpmk_bobot.*.cpmk_id' => ['required', 'integer'],
            'form.cpmk_bobot.*.bobot' => ['required', 'numeric', 'min:1'],

            // =====================
            // PERTEMUAN
            // =====================
            'pertemuans' => ['required', 'array', 'min:1'],

            'pertemuans.*.pertemuan_ke' => ['required', 'integer', 'min:1'],
            'pertemuans.*.materi_ajar' => ['required', 'string'],
            'pertemuans.*.indikator' => ['required', 'string'],
            'pertemuans.*.bentuk_pembelajaran' => ['required', 'string'],
            'pertemuans.*.cpmk_id' => ['required', 'integer'],

            // ALOKASI
            'pertemuans.*.alokasi' => ['required', 'array', 'min:1'],
            'pertemuans.*.alokasi.*.tipe' => ['required', 'string'],
            'pertemuans.*.alokasi.*.jumlah' => ['required', 'integer', 'min:1'],
            'pertemuans.*.alokasi.*.menit' => ['required', 'integer', 'min:10'],

            'pertemuans.*.bobots' => ['required', 'array', 'min:1'],
            'pertemuans.*.bobots.*.bobot' => ['required', 'integer', 'min:1'],
            'pertemuans.*.bobots.*.jenis' => ['required', 'string'],

            'pertemuans.*.rancangan_penilaian.jenis' => ['required', 'string'],
            'pertemuans.*.rancangan_penilaian.bobot' => ['required', 'integer', 'min:1'],
            'pertemuans.*.rancangan_penilaian.bentuk' => ['required', 'string'],
            'pertemuans.*.rancangan_penilaian.topik' => ['required', 'string'],

            // =====================
            // REFERENSI
            // =====================
            'referensi' => ['required', 'array'],
            'referensi.utama' => ['nullable', 'array'],
            'referensi.utama.*' => ['nullable', 'string'],
            'referensi.pendukung' => ['nullable', 'array'],
            'referensi.pendukung.*' => ['nullable', 'string'],

            // =====================
            // PENILAIAN (FIXED)
            // =====================
            'penilaian' => ['required', 'array', 'min:1'],

            'penilaian.*.persentase' => ['required', 'numeric', 'min:0', 'max:100'],
            'penilaian.*.cpmk' => ['required', 'array'],
            'penilaian.*.cpmk.*' => ['required', 'numeric', 'min:0', 'max:100'],

        ], [
            'required' => 'Field :attribute wajib diisi',
            'pertemuans.min' => 'Minimal 1 pertemuan harus diisi',
            'penilaian.*.persentase.max' => 'Persentase maksimal 100%',
        ]);

        // =====================
        // VALIDASI LOGIC TAMBAHAN
        // =====================
        $validator->after(function ($validator) use ($data) {

            // Total penilaian harus 100%
            $total = collect($data['penilaian'])
                ->sum(fn($item) => (float) ($item['persentase'] ?? 0));

            if ($total !== 100.0) {
                $validator->errors()->add(
                    'penilaian',
                    'Total persentase penilaian harus 100%'
                );
            }

            // Jika persentase > 0, CPMK wajib ada
            foreach ($data['penilaian'] as $jenis => $item) {
                if (($item['persentase'] ?? 0) > 0 && empty($item['cpmk'])) {
                    $validator->errors()->add(
                        "penilaian.$jenis.cpmk",
                        "CPMK wajib dipilih untuk penilaian {$jenis}"
                    );
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }



    protected function persist(): void
    {
        // 🔒 VALIDASI GLOBAL
        // 🔥 TRANSACTION
        // 💾 SAVE DATABASE
        $this->form = [
            'matakuliah_id' => $this->matakuliahId,
            'program_studi_id' => $this->programStudiId,
            'class' => $this->payload['identitas']['kelas'],
            'dosen_id' => $this->payload['identitas']['dosen_id'],
            'academic_year' => $this->payload['identitas']['tahun_akademik'],
            'revision' => $this->payload['identitas']['revisi'],
            'learning_method' => $this->payload['learning_method_exp']['form']['metode_pembelajaran'],
            'learning_experience' => $this->payload['learning_method_exp']['form']['pengalaman_belajar_mahasiswa'],
            'cpmk_bobot' => $this->payload['cpl_cpmk']['cpmks'],
        ];
        $finals = [
            'form' => $this->form,
            'pertemuans' => $this->viewTable ? $this->payload['pertemuan_table'] : $this->payload['pertemuan_grid'],
            'referensi' => $this->payload['learning_method_exp']['referensi'],
            'penilaian' => $this->payload['penilaian'],
        ];
        try {
            $this->validateGlobal($finals);
        } catch (ValidationException $e) {
            $this->modal('errorForm')->show();
            $this->addError('global', 'Data RPS belum lengkap');
            throw $e; // ⬅️ PENTING
        }
        DB::transaction(function () use ($finals) {
            $rps = $this->saveRpsMaster();
            $this->saveRpsPertemuans($rps->id, $finals['pertemuans']);
            $this->saveRpsReferensi($rps->id, $finals['referensi']);
            $this->saveRpsPenilaian($rps->id, $finals['penilaian']);
            $this->createRpsAproval($rps->id);
        });
        $this->dispatch('success-created');
        $this->redirect(route('perangkat-ajar.rps.index'), navigate: true);
    }

    protected function saveRpsMaster()
    {
        return Rps::create($this->form);
    }

    protected function saveRpsPertemuans(int $rpsId, array $pertemuans)
    {
        foreach ($pertemuans as $p) {
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

    protected function saveRpsReferensi(int $rpsId, array $referensi)
    {
        foreach ($referensi as $jenis => $items) {
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

    protected function saveRpsPenilaian(int $rpsId, array $penilaian)
    {
        foreach ($penilaian as $jenis => $item) {
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
