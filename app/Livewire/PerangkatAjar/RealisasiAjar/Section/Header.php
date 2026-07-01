<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar\Section;

use Livewire\Component;
use App\Models\Matakuliah;
use App\Models\Kurikulum;
use App\Models\RealisasiPengajaran;
use App\Models\BebanAjarDosen;
class Header extends Component
{
    public $listMk = [];
    public $activeProdi = null;
    public $activeProdiName = null;
    public $activeDosenName = null;
    public $indentitasMk = [
        'program_studi_name' => null,
        'dosen_pengampu' => null,
        'matakuliah_name' => null,
        'matakuliah_code' => null,
        'matakuliah_sks' => null,
        'matakuliah_semester' => null,
    ];
    public $dataKurikulum = [];
    public $form = [
        'matakuliah_id' => null,
        'program_studi_id' => null,
        'kelas' => null,
        'dosen_id' => null,
        'tahun_akademik' => null,
        'tujuan_instruksional_umum' => null,
        'semester' => null,
        'jumlah_sks' => null
    ];

    public $selectedId = null;
    public $isEdit = false;
    public $isView = false;
    public $dosenIdActive = null;
    protected $listeners = ['requestFormData'];

    // public function editData($id)
    // {
    //     $this->selectedId = $id;
    //     $this->isEdit = true;
    //     $this->openEdit();
    // }

    protected function openEdit()
    {
        $getData = RealisasiPengajaran::find($this->selectedId);
        $this->form = [
            'matakuliah_id' => $getData->matakuliah_id,
            'program_studi_id' => $getData->program_studi_id,
            'kelas' => $getData->kelas,
            'dosen_id' => $getData->dosen_id,
            'tahun_akademik' => $getData->tahun_akademik,
            'tujuan_instruksional_umum' => $getData->tujuan_instruksional_umum,
            'semester' => $getData->semester,
            'jumlah_sks' => $getData->jumlah_sks
        ];
        $this->updatedFormMatakuliahId();
    }
    public function requestFormData()
    {
        $this->validate([
            'form.matakuliah_id' => 'required',
            'form.kelas' => 'required',
            'form.tahun_akademik' => 'required',
            'form.tujuan_instruksional_umum' => 'required',
        ]);

        $this->dispatch(
            'formDataReady',
            section: 'header',
            data: $this->form
        );
    }
    public function mount(?int $selectedId = null, bool $isEdit = false, bool $isView = false)
    {
        $this->setFilterProdi();

        if ($selectedId && ($isEdit || $isView)) {
            $this->selectedId = $selectedId;
            $this->isEdit = $isEdit;
            $this->isView = $isView;
            $this->openEdit();
        }

        $this->listMk = $this->resolveValueListMk();

    }

    protected function resolveValueListMk()
    {
        $getListMk = $this->getListMatakuliah()->get();

        $listMk = [];
        foreach ($getListMk as $mk) {
            $listMk[] = [
                'id' => $mk->id,
                'name' => $mk->code . ' - ' . $mk->name . ' - SMT ' . $mk->semester
            ];
        }
        return $listMk;
    }

    protected function getListBebanAjar(){
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
        // dd($this->getListBebanAjar(),$this->activeProdi);
        $dataMatakuliah = $this->getListMatakuliah()->find($this->form['matakuliah_id']);
        $this->indentitasMk = [
            'program_studi_name' => $dataMatakuliah->programStudis->first()->name,
            'dosen_pengampu' => auth()->user()->dosenName(),
            'matakuliah_name' => $dataMatakuliah->name,
            'matakuliah_code' => $dataMatakuliah->code,
            'matakuliah_sks' => $dataMatakuliah->sks,
            'matakuliah_semester' => $dataMatakuliah->semester,
        ];
        $this->form['jumlah_sks'] = $dataMatakuliah->sks;
        $this->form['semester'] = $dataMatakuliah->semester;
    }
    protected function resetMatakuliahData(): void
    {
        $this->indentitasMk = [];
        $this->dataKurikulum = [];
    }

    public function render()
    {
        return view('livewire.perangkat-ajar.realisasi-ajar.section.header');
    }
}
