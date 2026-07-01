<?php

namespace App\Livewire\PerangkatAjar\Rps\Section;

use Livewire\Component;
use App\Models\Matakuliah;
use App\Models\Kurikulum;
use App\Models\BebanAjarDosen;

use Livewire\Attributes\On;
class IdentitasRps extends Component
{
    public $listMk = [];
    public $activeProdi = null;
    public $dosenIdActive = null;

    public $activeProdiName = null;
    public $activeDosenName = null;
    public $indentitasMk = [];
    public $dataKurikulum = [];
    public $form = [
        'matakuliah_id' => null,
        'program_studi_id' => null,
        'kelas' => null,
        'dosen_id' => null,
        'tahun_akademik' => null,
        'revisi' => 0,
    ];

    protected $listeners = ['requestFormData'];

    public function requestFormData(): void
    {
        $this->dispatch(
            'formDataReady',
            section: 'identitas',
            data: $this->form
        );
    }
    public function mount()
    {
        $this->setFilterProdi();
        $this->listMk = $this->getListMatakuliah()->get();

    }

    protected function getListBebanAjar()
    {
        $getBebanAjar = BebanAjarDosen::query()
            ->where('dosen_id', $this->dosenIdActive)
            ->get()->pluck('matakuliah_id')->toArray();
        return $getBebanAjar;
    }

    protected function getListMatakuliah()
    {

        return Matakuliah::query()
            ->with(['programStudis', 'MkCpmk.cpmk', 'MkCpl.cpl'])
            ->when($this->activeProdi, function ($q) {
                $q->whereHas('programStudis', function ($q) {
                    $q->where('program_studis.id', $this->activeProdi);
                });
            })
            ->whereIn('id', $this->getListBebanAjar());
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
            $this->dosenIdActive = auth()->user()->dosenId();
            return;
        }

    }
    protected function getKurikulumPublishedByMk($matakuliahId)
    {
        return Kurikulum::query()
            ->where('status', 'published')
            ->where('prodi_id', $this->activeProdi)
            ->first();
    }

    public function updatedFormMatakuliahId()
    {
        if (!$this->form['matakuliah_id']) {
            $this->resetMatakuliahData();
            return;
        }
        $this->indentitasMk = $this->getListMatakuliah()->find($this->form['matakuliah_id']);
        $this->dataKurikulum = $this->getKurikulumPublishedByMk($this->form['matakuliah_id']);
        $params = [
            'matakuliahId' => $this->form['matakuliah_id'],
            'programStudiId' => $this->form['program_studi_id'],
            'kurikulumId' => $this->dataKurikulum['id'] ?? null,
        ];
        $this->setKelasAndTakdFromBebanAjar($this->form['matakuliah_id']);
        $this->dispatch('matakuliahUpdated', $params);
    }

    protected function setKelasAndTakdFromBebanAjar(int $mkId){
        $bebanAjar = BebanAjarDosen::query()
            ->where('dosen_id', $this->dosenIdActive)
            ->where('matakuliah_id', $mkId)
            ->first();
        $this->form['kelas'] = $bebanAjar->kelas;
        $this->form['tahun_akademik'] = $bebanAjar->tahun_ajaran;
    }
    protected function resetMatakuliahData(): void
    {
        $this->indentitasMk = [];
        $this->dataKurikulum = [];
    }
    public function render()
    {
        return view('livewire.perangkat-ajar.rps.section.identitas-rps');
    }
}
