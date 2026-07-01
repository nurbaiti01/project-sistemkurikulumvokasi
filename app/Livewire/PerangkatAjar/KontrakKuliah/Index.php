<?php

namespace App\Livewire\PerangkatAjar\KontrakKuliah;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\KontrakKuliah;
use App\Models\ProgramStudi;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{

    public string $title = 'Perangkat Ajar Kontrak Kuliah';
    /* ----------------------------------------
     | Model & View
     |---------------------------------------- */
    protected static string $model = KontrakKuliah::class;
    protected static string $view = 'livewire.perangkat-ajar.kontrak-kuliah.index';

    /* ----------------------------------------
     | Table Config
     |---------------------------------------- */
    public array $relations = ['programStudis'];

    /**
     * daftar filter yang didukung
     * cara kerja:
     *  - type `relation` → auto whereHas
     *  - column opsional → jika pivot / custom field
     */
    protected array $filterable = [
        'prodi' => [
            'type' => 'relation',
            'relation' => 'programStudis',
            'column' => 'program_studis.id',
        ],
        'status' => [
            'type' => 'column',
            'column' => 'status',
        ],
        'dosen_id'=> [
            'type' => 'column',
            'column' => 'dosen_id',
        ]
    ];

    /**
     * daftar kolom pencarian
     */
    protected array $searchable = ['tahun_akademik', 'kelas'];

    /**
     * nilai default filter
     */
    public array $filter = [
        'prodi' => null,
        'status' => null,
        'dosen_id' => null
    ];

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
            $this->filter['status'] = ['submitted', 'published', 'rejected'];
            return;
        }
        if (session('active_role') == 'Akademik') {
            $this->filter['status'] = ['published'];
        }
        if ( in_array(session('active_role'), ['WADIR 1']) ) {
            $this->filter['status'] = ['submitted','published','rejected'];
        }
        if (session('active_role') == 'Dosen') {
            $this->filter['dosen_id'] = auth()->user()->dosenId();
        }

    }

    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
    }

    public function previewPdf($id)
    {
        $data = KontrakKuliah::with('programStudis')->find($id);

        $this->redirect(route('pdf-preview', ['id' => $data->id]));
    }

}
