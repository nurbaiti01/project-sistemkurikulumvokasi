<?php

namespace App\Livewire\Widget;

use Livewire\Component;
use App\Models\Dosen;
use App\Models\Matakuliah;
use App\Models\ProgramStudi;
use App\Models\Kurikulum;
use App\Models\KontrakKuliah;
use App\Models\Rps;
use App\Models\RealisasiPengajaran;
use Illuminate\Support\Facades\DB;

class CradStatistik extends Component
{

    public array $statsDosen = [];
    public array $statsProgramStudi = [];
    public array $statsKurikulum = [];
    public array $statsPerangkatAjar = [];

    public array $statsMatakuliah = [];

    public ?int $prodiId = null;
    public ?int $dosenId = null;

    public function mount()
    {
        $this->filterData();
        $this->getTotalDosen();
        $this->getTotalProgramStudi();
        $this->getTotalKurikulum();
        $this->getTotalPerangkatAjar();
        $this->getTotalMatakuliah();
    }

    public function filterData()
    {
        if (in_array(session('active_role'), ['Kaprodi', 'Dosen'])) {
            $programstudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();
            $this->prodiId = $programstudi?->id;
            $this->dosenId = session('active_role') == 'Dosen' ? auth()->user()->dosenId() : null;
        }
    }

    protected function getTotalDosen()
    {
        $getTotalDosen = Dosen::query()
            ->when($this->prodiId, fn($q) => $q->whereHas('programStudis', fn($q) => $q->where('program_studis.id', $this->prodiId)))->count();

        $this->statsDosen = [
            'show' => !in_array(session('active_role'), ['Dosen']),
            'title' => 'Jumlah Dosen',
            'value' => $getTotalDosen,
        ];
    }

    protected function getTotalProgramStudi()
    {

        $getotalProgramStudi = ProgramStudi::count();

        $this->statsProgramStudi = [
            'show' => !in_array(session('active_role'), ['Dosen', 'Kaprodi']),
            'title' => 'Jumlah Program Studi',
            'value' => $getotalProgramStudi
        ];
    }

    protected function getTotalMatakuliah()
    {
        $getTotalMatakuliah = Matakuliah::query()
            ->when($this->prodiId, fn($q) => $q->whereHas('programStudis', fn($q) => $q->where('program_studis.id', $this->prodiId)))->count();
        $this->statsMatakuliah = [
            'show' => in_array(session('active_role'), ['Dosen', 'Kaprodi', 'Akademik', 'BPM', 'Direktur', 'WADIR 1']),
            'title' => 'Jumlah Matakuliah',
            'value' => $getTotalMatakuliah
        ];
    }

    protected function getTotalKurikulum()
    {
        $status = ['submitted', 'published', 'archived'];
        $getTotalKurikulum = Kurikulum::query()
            ->when($this->prodiId, fn($q) => $q->whereHas('programStudis', fn($q) => $q->where('program_studis.id', $this->prodiId)))
            ->count();
        $details = [];
        foreach ($status as $s) {
            $details[$s] = Kurikulum::where('status', $s)->when($this->prodiId, fn($q) => $q->whereHas('programStudis', fn($q) => $q->where('program_studis.id', $this->prodiId)))->count();
        }
        $this->statsKurikulum = [
            'show' => in_array(session('active_role'), ['Kaprodi', 'Akademik', 'BPM', 'Direktur', 'WADIR 1']),
            'title' => 'Jumlah Kurikulum',
            'value' => $getTotalKurikulum,
            'details' => $details
        ];
    }

    protected function getTotalPerangkatAjar()
    {
        $getTotalKontrak = KontrakKuliah::query()
            ->where('status', 'published')
            ->when($this->prodiId, fn($q) => $q->whereHas('programStudis', fn($q) => $q->where('program_studis.id', $this->prodiId)))
            ->when($this->dosenId, fn($q) => $q->where('dosen_id', $this->dosenId))
            ->count();
        $getTotalRps = Rps::where('status', 'published')->when($this->prodiId, fn($q) => $q->whereHas('programStudi', fn($q) => $q->where('program_studis.id', $this->prodiId)))->when($this->dosenId, fn($q) => $q->where('dosen_id', $this->dosenId))->count();
        $getTotalRs = RealisasiPengajaran::where('status', 'published')->when($this->prodiId, fn($q) => $q->whereHas('programStudi', fn($q) => $q->where('program_studis.id', $this->prodiId)))->when($this->dosenId, fn($q) => $q->where('dosen_id', $this->dosenId))->count();
        $this->statsPerangkatAjar = [
            'show' => in_array(session('active_role'), ['Dosen', 'Kaprodi', 'Akademik', 'BPM', 'Direktur', 'WADIR 1']),
            'title' => 'Jumlah Perangkat Ajar',
            'value' => $getTotalKontrak + $getTotalRps + $getTotalRs,
            'details' => [
                'Kontrak Kuliah (Published)' => $getTotalKontrak,
                'Rencana Pembelajaran Semester (Published)' => $getTotalRps,
                'Realisasi Pengajaran (Approved)' => $getTotalRs
            ]
        ];
    }
    public function render()
    {
        return view('livewire.widget.crad-statistik');
    }
}
