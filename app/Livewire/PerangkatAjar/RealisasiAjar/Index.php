<?php

namespace App\Livewire\PerangkatAjar\RealisasiAjar;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\RealisasiPengajaran;
#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{
    public string $title = 'Bahan Kajian';
    /* ----------------------------------------
     | Model & View
     |---------------------------------------- */
    protected static string $model = RealisasiPengajaran::class;
    protected static string $view = 'livewire.perangkat-ajar.realisasi-ajar.index';

    /* ----------------------------------------
     | Table Config
     |---------------------------------------- */
    public array $relations = ['programStudi'];

    /**
     * daftar filter yang didukung
     * cara kerja:
     *  - type `relation` → auto whereHas
     *  - column opsional → jika pivot / custom field
     */
    protected array $filterable = [
        'prodi' => [
            'type' => 'relation',
            'relation' => 'programStudi',
            'column' => 'program_studis.id',
        ],
        'dosen' => [
            'type' => 'relation',
            'relation' => 'dosen',
            'column' => 'dosens.id',
        ],
        'status' => [
            'type' => 'column',
            'column' => 'status',
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
        'dosen' => null,
        'status' => null,
    ];

    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
    }
    // protected function beforeSetFilterProdi(): void
    // {
    //     if (session('active_role') == 'Dosen') {

    //         $programStudi = auth()->user()
    //                 ?->dosens()
    //                 ?->with('programStudis')
    //                 ?->first()
    //                 ?->programStudis()
    //                 ?->first();

    //         $this->filter['prodi'] = $programStudi?->id;

    //         return;
    //     }
    // }

    protected function setFilterProdi(): void
    {
        if (session('active_role') == 'Kaprodi') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->filter['prodi'] = $programStudi?->id;
            $this->activeProdi = $programStudi?->id;
            $this->filter['status'] = ['submitted', 'approved', 'rejected'];
            return;
        }
        if (session('active_role') == 'Akademik') {
            $this->filter['status'] = ['approved'];
        }
        if (in_array(session('active_role'), ['WADIR 1','BPM','Direktur'])) {
            $this->filter['status'] = ['approved'];
        }
        if (session('active_role') == 'Dosen') {
            $this->filter['dosen_id'] = auth()->user()->dosenId();
        }

    }


}
