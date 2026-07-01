<?php

namespace App\Livewire\PerangkatAjar\KontrakKuliah;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\Matakuliah as MK;
use App\Models\BebanAjarDosen;
use App\Models\KontrakKuliah;
use App\Services\KontrakKuliahService;
use App\Services\UserContextService;
#[Title('Kontrak Kuliah')]
#[Layout('components.layouts.sidebar')]
class CreateUpdate extends Component
{
    public ?int $selectedId = null;
    public int $matakuliahId, $dosenId, $totalJam;

    public string $kelas, $deskripsiMk, $tujuan_pembelajaran, $strategi_perkuliahan, $materi_pembelajaran, $kriteria_penilaian, $tata_tertib, $tahun_akademik;

    public $mk_cpmk = '';

    public array $detailMk = [
        'nama_mk' => '',
        'kode_mk' => '',
        'bobot_sks' => 0,
        'program_studi' => '',
        'semester' => 0,
        'deskripsi_mk' => ''
    ];

    public string $detailDosen = '';

    public array $listMK = [];

    public ?int $activeProdi = null;

    public $dosenIdActive = null;


    public function mount(int $id = null)
    {
        $this->selectedId = $id;
        if ($id != null) {
            $this->openEdit($id);
        }
        $this->detailDosen = auth()->user()->name;
        $this->setUpProdi();
        $this->setListMk();
    }

    public function setUpProdi()
    {
        if (session('active_role') == 'Dosen') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->activeProdi = $programStudi?->id;
            $this->dosenIdActive = auth()->user()->dosenId();
            return;
        }
    }

    protected function getListBebanAjar(){
        $getBebanAjar = BebanAjarDosen::query()
            ->where('dosen_id', $this->dosenIdActive)
            ->get()->pluck('matakuliah_id')->toArray();
        return $getBebanAjar;
    }

    protected function setListMk()
    {
        $getMK = MK::query()
            ->with('programStudis')
            ->when($this->activeProdi, function ($query) {
                $query->whereHas('programStudis', function ($query) {
                    $query->where('program_studis.id', $this->activeProdi);
                });
            })
            ->whereIn('id', $this->getListBebanAjar())
            ->get()
            ->map(function ($mk) {
                return [
                    'id' => $mk->id,
                    'label' => $mk->code,
                    'description' => 'MK :' . $mk->name . ', Semester :' . $mk->semester
                ];
            });

        $this->listMK = $getMK->toArray();
    }

    public function updating($property, $value)
    {
        if ($property == 'matakuliahId') {
            if ($value == null) {
                $this->detailMk = [
                    'nama_mk' => '',
                    'kode_mk' => '',
                    'bobot_sks' => 0,
                    'program_studi' => '',
                    'semester' => 0,
                    'deskripsi_mk' => ''
                ];
                $this->deskripsiMk = '';
                $this->mk_cpmk = '';
                $this->dispatch('updated-editor', model: 'deskripsiMk');
                $this->dispatch('updated-editor', model: 'mk_cpmk');
            } else {

                $this->setDetailMk($value);
            }
        }
    }

    protected function setDetailMk($id)
    {
        $getMk = MK::with(['programStudis', 'MkCpmk'])->where('id', $id)->first();
        // dump($getMk->MkCpmk);
        $dataCpmk = $getMk->MkCpmk->map(fn($cpmk) => [
            'id' => $cpmk->id,
            'code' => $cpmk->cpmk->code,
            'label' => $cpmk->cpmk->description,
        ]);
        $letters = range('a', 'z'); // a, b, c, ... z
        $i = 0;

        $listCpmk = '';

        foreach ($dataCpmk as $item) {
            $prefix = $letters[$i] ?? ($i + 1); // fallback ke angka kalau lewat z
            $listCpmk .= "<p><strong>{$prefix}.</strong> <strong>{$item['code']}</strong> — {$item['label']}</p>";
            $i++;
        }
        $this->mk_cpmk = $listCpmk;
        $this->detailMk = [
            'nama_mk' => $getMk->name,
            'kode_mk' => $getMk->code,
            'bobot_sks' => $getMk->sks,
            'program_studi' => $getMk->programStudis()->first()->name,
            'semester' => $getMk->semester,
            'deskripsi_mk' => $getMk->description
        ];
        $this->deskripsiMk = $getMk->description;
        $this->dispatch('updated-editor', model: 'deskripsiMk');
        $this->dispatch('updated-editor', model: 'mk_cpmk');
    }

    public function save(KontrakKuliahService $service)
    {
        $this->validate(
            [
                'matakuliahId' => 'required',
                'kelas' => 'required',
                'totalJam' => 'required',
                'tujuan_pembelajaran' => 'required',
                'strategi_perkuliahan' => 'required',
                'materi_pembelajaran' => 'required',
                'kriteria_penilaian' => 'required',
                'tata_tertib' => 'required',
                'tahun_akademik' => 'required'
            ],
            [
                'matakuliahId.required' => 'Matakuliah wajib di pilih',
                'kelas.required' => 'Kelas wajib diisi',
                'totalJam.required' => 'Total Jam wajib diisi',
                'tujuan_pembelajaran.required' => 'Tujuan Pembelajaran wajib diisi',
                'strategi_perkuliahan.required' => 'Strategi Perkuliahan wajib diisi',
                'materi_pembelajaran.required' => 'Materi Pembelajaran wajib diisi',
                'kriteria_penilaian.required' => 'Kriteria Penilaian wajib diisi',
                'tata_tertib.required' => 'Tata Tertib wajib diisi',
                'tahun_akademik.required' => 'Tahun Akademik wajib diisi'
            ]
        );
        $dataKontrak = [
            'dosen_id' => auth()->user()->dosens()->first()->id,
            'matakuliah_id' => $this->matakuliahId,
            'prodi_id'=> $this->activeProdi,
            'tahun_akademik' => $this->tahun_akademik,
            'kelas' => $this->kelas,
            'total_jam' => $this->totalJam,
            'tujuan_pembelajaran' => $this->tujuan_pembelajaran,
            'strategi_perkuliahan' => $this->strategi_perkuliahan,
            'materi_pembelajaran' => $this->materi_pembelajaran,
            'kriteria_penilaian' => $this->kriteria_penilaian,
            'tata_tertib' => $this->tata_tertib
        ];

        $service->createUpdateWithApprovals($dataKontrak,$this->selectedId);

        $this->redirect(route('perangkat-ajar.kontrak-kuliah.index'), navigate: true);
    }

    public function openEdit($id)
    {
        $getKontrakKuliah = KontrakKuliah::find($id);
        $this->setDetailMk($getKontrakKuliah->matakuliah_id);

        $this->detailDosen = $getKontrakKuliah->dosen->name;

        $this->matakuliahId = $getKontrakKuliah->matakuliah_id;
        $this->dosenId = $getKontrakKuliah->dosen_id;
        $this->kelas = $getKontrakKuliah->kelas;
        $this->totalJam = $getKontrakKuliah->total_jam;
        $this->tujuan_pembelajaran = $getKontrakKuliah->tujuan_pembelajaran;
        $this->strategi_perkuliahan = $getKontrakKuliah->strategi_perkuliahan;
        $this->materi_pembelajaran = $getKontrakKuliah->materi_pembelajaran;
        $this->kriteria_penilaian = $getKontrakKuliah->kriteria_penilaian;
        $this->tata_tertib = $getKontrakKuliah->tata_tertib;
        $this->tahun_akademik = $getKontrakKuliah->tahun_akademik;

    }
    public function render()
    {
        return view('livewire.perangkat-ajar.kontrak-kuliah.create-update');
    }
}
