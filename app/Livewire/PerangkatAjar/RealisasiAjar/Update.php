<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\RealisasiPengajaran;
use App\Models\RealisasiPengajaranDetail;
use App\Models\RealisasiPengajaranEvaluasi;
use App\Models\RealisasiPengajaranMetode;
use App\Models\RealisasiPengajaranReferensi;
use App\Models\RealisasiPengajaranApproval;
use Illuminate\Support\Facades\DB;
use WireUi\Traits\WireUiActions;

#[Layout('components.layouts.sidebar')]
class Update extends Component
{
    use WireUiActions;
    public $payload = [];
    public $isDraft = false;
    protected $listeners = [
        'formDataReady',
    ];

    public ?int $realisasiPengajaranId = null;
    public function mount(int $id)
    {
        $this->realisasiPengajaranId = $id;
        $this->dispatch('editData', $id);
    }

    public function save($isDraft = false)
    {
        $this->isDraft = $isDraft;
        $this->payload = [];
        $this->dispatch('requestFormData');
    }

    public function formDataReady(string $section, array $data)
    {
        $this->payload[$section] = $data;

        if ($this->allSectionsReady()) {
            $this->persist();
        }
    }

    protected function allSectionsReady(): bool
    {
        return collect([
            'header',
            'pertemuan',
            'footer'
        ])->every(fn($key) => array_key_exists($key, $this->payload));

    }

    protected function persist()
    {
        $dataMaster = $this->payload['header'];
        $dataMaster['status'] = $this->isDraft === true ? 'draft' : 'submitted';
        DB::transaction(function () use ($dataMaster) {
            $realisasi = RealisasiPengajaran::find($this->realisasiPengajaranId);
            $realisasi->update($dataMaster);

            $realisasi->details()->delete();
            $this->savePertemuans($realisasi->id, $this->payload['pertemuan']);
            $realisasi->metodes()->delete();
            $this->saveMetode($realisasi->id, $this->payload['footer']['metode']);
            $realisasi->evaluasis()->delete();
            $this->saveEvaluasi($realisasi->id, $this->payload['footer']['evaluasi']);
            $realisasi->referensis()->delete();
            $this->saveReferensi($realisasi->id, $this->payload['footer']['referensi']);
            $this->saveApproval($realisasi->id);
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success Notification!',
                'description' => 'Data berhasil disimpan.',
            ]);
        });
        $this->redirect(route('perangkat-ajar.realisasi-ajar.index'), navigate: true);
    }
    protected function savePertemuans(int $realisasiPengajaranId, array $pertemuans)
    {
        foreach ($pertemuans as $pertemuan) {
            RealisasiPengajaranDetail::create([
                'realisasi_id' => $realisasiPengajaranId,
                'pertemuan_ke' => $pertemuan['pertemuan_ke'],
                'tanggal' => $pertemuan['tanggal'],
                'pokok_bahasan' => $pertemuan['pokok_bahasan'],
                'jam' => $pertemuan['jam'],
                'paraf' => $pertemuan['paraf'],  // simpan json
            ]);
        }
    }

    protected function saveEvaluasi(int $realisasiPengajaranId, array $evaluasi)
    {
        RealisasiPengajaranEvaluasi::create([
            'tugas_persen' => $evaluasi['tugas_persen'],
            'kuis_persen' => $evaluasi['kuis_persen'],
            'ujian_persen' => $evaluasi['ujian_persen'],
            'realisasi_id' => $realisasiPengajaranId
        ]);
    }

    protected function saveMetode(int $realisasiPengajaranId, array $metode)
    {
        foreach ($metode as $key => $value) {
            RealisasiPengajaranMetode::create([
                'jenis' => $key,
                'jam' => $value['jam'],
                'realisasi_id' => $realisasiPengajaranId
            ]);
        }

    }

    protected function saveReferensi(int $realisasiPengajaranId, array $referensi)
    {
        foreach ($referensi as $jenis => $items) {
            RealisasiPengajaranReferensi::create([
                'realisasi_id' => $realisasiPengajaranId,
                'jenis' => $items['jenis'], // utama | pendukung
                'judul' => $items['judul'],
                'penerbit' => $items['penerbit'],
            ]);
        }
    }

    protected function saveApproval(int $realisasiPengajaranId)
    {
        $listRoleProsess = ['perumusan', 'pemeriksaan'];
        foreach ($listRoleProsess as $roleProses) {
            RealisasiPengajaranApproval::updateOrCreate(
                [
                    'realisasi_id' => $realisasiPengajaranId,
                    'role_proses' => $roleProses
                ],
                [
                    'dosen_id' => $roleProses == 'perumusan' ? auth()->user()->dosenId() : null,
                    'status' => $this->resolveInitialStatus($roleProses, $this->isDraft),
                    'approved' => $this->isDraft ? 0 : 1,
                    'approved_at' => now()
                ]
            );
        }
    }

    protected function resolveInitialStatus(string $roleProses, bool $isDraft): string
    {
        if ($roleProses !== 'perumusan') {
            return 'pending';
        }

        return $isDraft ? 'pending' : 'approved';
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.update');
    }
}
