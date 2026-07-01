<?php

namespace App\Livewire\PerangkatAjar\KontrakKuliah;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\KontrakKuliah;
use App\Models\Matakuliah as MK;
use App\Models\Kurikulum;
use App\Models\KontrakKuliahApproval;
use DOMDocument;
use Flux\Flux;
use WireUi\Traits\WireUiActions;
#[Title('Kontrak Kuliah')]
#[Layout('components.layouts.sidebar')]
class View extends Component
{
    use WireUiActions;
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
    public ?int $activeProdi = null;
    public ?int $approvalId = null;
    public $kontrakApprovals = null;

    public $catatan = null;

    public function mount(int $id = null)
    {
        $this->selectedId = $id;
        if ($id != null) {
            $this->openEdit($id);
        }
        $this->detailDosen = auth()->user()->name;
        $this->setUpProdi();
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
            return;
        }
    }

    protected function resolveQueryKontrakkuliah()
    {
        return KontrakKuliah::with(['matakuliah', 'dosen'])->where('id', $this->selectedId)->first();
    }

    protected function resolveKurikulumPublished()
    {
        $prodiKontrakKuliah = $this->resolveQueryKontrakkuliah()->prodi_id;
        $this->activeProdi = $prodiKontrakKuliah;
        return Kurikulum::published()->byProdi($this->activeProdi)->first();
    }
    protected function setDetailMk($id)
    {
        $getKurikulum = $this->resolveKurikulumPublished();
        $getMk = MK::with(['programStudis', 'MkCpmk' => fn($q) => $q->where('kurikulum_id', $getKurikulum->id)])->where('id', $id)->first();
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
    }
    public function openEdit($id)
    {
        $getKontrakKuliah = KontrakKuliah::with('kontrakApprovals')->find($id);
        $this->kontrakApprovals = $getKontrakKuliah;
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

    public function parseTable($html): array
    {
        if (empty($html)) {
            return [];
        }

        libxml_use_internal_errors(true);

        $dom = new DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);

        $rows = [];

        foreach ($dom->getElementsByTagName('tr') as $tr) {
            $cols = [];

            foreach ($tr->getElementsByTagName('td') as $td) {
                $cols[] = trim($td->textContent);
            }

            if (!empty($cols)) {
                $rows[] = $cols;
            }
        }

        return $rows;
    }

    public function submitKontrak()
    {
        // update status kontrak
        $this->kontrakApprovals->update([
            'status' => 'submitted',
        ]);

        // update approval perumusan
        $this->kontrakApprovals
            ->kontrakApprovals()
            ->where('role_proses', 'perumusan')
            ->update([
                'status' => 'approved',
                'approved' => true,
                'approved_at' => now(),
            ]);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Kontrak Submitted Successfully',
        ]);
    }

    public function openDialog($approvalId, $isrejected = false, $role = 'perumusan')
    {
        $this->approvalId = $approvalId;
        if ($isrejected) {
            Flux::modal('rejectedKontrak')->show();
        } else {
            if ($role == 'perumusan') {
                $this->dialog()->confirm([
                    'title' => 'Are you Sure?',
                    'description' => 'Submut Kontrak Kuliah?',
                    'acceptLabel' => 'Yes, submit it',
                    'method' => 'submitKontrak',
                ]);
            } else {
                $this->dialog()->confirm([
                    'title' => 'Are you Sure?',
                    'description' => 'approval kontrak kuliah?',
                    'acceptLabel' => 'Yes, approve it',
                    'method' => 'approve',
                    'params' => $approvalId,
                ]);
            }
        }
    }
    public function approve($approvalId)
    {
        $getApproval = KontrakKuliahApproval::find($approvalId);
        // dump($getApproval);
        $getApproval->update([
            'dosen_id' => auth()->user()->dosenId(),
            'status' => 'approved',
            'approved' => 1,
            'approved_at' => now(),
        ]);
        $getApproval->kontrakKuliah()->update([
            'status' => 'published',
        ]);
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Kontrak Approved Successfully',
        ]);
        $this->approvalId = null;
        $this->catatan = null;
        $this->mount($getApproval->kontrak_kuliah_id);
    }

    public function saveRejected()
    {
        $getKontrakKuliah = KontrakKuliahApproval::findOrFail($this->approvalId)->update([
            'dosen_id' => auth()->user()->dosenId(),
            'status' => 'rejected',
            'approved' => false,
            'approved_at' => now(),
            'catatan' => $this->catatan
        ]);
        $getKontrakKuliah->kontrakKuliah()->update([
            'status' => 'rejected',
        ]);
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Kontrak Rejected Successfully',
        ]);
        $this->approvalId = null;
        $this->catatan = null;
        Flux::modal('rejectedKontrak')->close();
        $this->mount($getKontrakKuliah->kontrak_kuliah_id);
    }

    public function render()
    {
        return view('livewire.perangkat-ajar.kontrak-kuliah.view');
    }
}
