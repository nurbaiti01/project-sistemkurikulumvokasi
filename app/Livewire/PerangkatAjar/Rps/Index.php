<?php

namespace App\Livewire\PerangkatAjar\Rps;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\Rps;


#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{

    public string $title = 'RPS';
    protected static string $model = Rps::class;
    protected static string $view = 'livewire.perangkat-ajar.rps.index';

    protected array $filterable = [
        'prodi' => [
            'type' => 'column',
            'column' => 'program_studi_id'
        ],
        'status' => [
            'type' => 'column',
            'column' => 'status'
        ],
        'dosen' => [
            'type' => 'relation',
            'relation' => 'dosen',
            'column' => 'dosen.name'
        ],
    ];

    protected array $searchable = [
        'matakuliah' => [
            'type' => 'relation',
            'relation' => 'matakuliah',
            'column' => 'matakuliah.name'
        ],
        'dosen' => [
            'type' => 'relation',
            'relation' => 'dosen',
            'column' => 'dosen.name'
        ],

    ];

    public array $filter = [
        'prodi' => null,
        'status' => null,
        'dosen' => null
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
        // $this->beforeSetFilterProdi();
        if (session('active_role') == 'Kaprodi') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->filter['prodi'] = $programStudi?->id;
            $this->activeProdi = $programStudi?->id;
            $this->filter['status'] = ['submitted', 'published', 'rejected', 'approved'];
            return;
        }
        if (session('active_role') == 'Akademik') {
            $this->filter['status'] = ['published'];
        }
        if (in_array(session('active_role'), ['WADIR 1','Direktur','BPM'])) {
            $this->filter['status'] = ['submitted', 'published', 'rejected', 'approved'];
        }
        if (session('active_role') == 'Dosen') {
            $this->filter['dosen_id'] = auth()->user()->dosenId();
        }

    }

    protected function beforeDelete()
    {
        $rpsData = Rps::find($this->selectedId);
        $rpsData->pertemuans()->delete();
        $rpsData->referensis()->delete();
        $rpsData->penilaians()->delete();
        $rpsData->rpsApprovals()->delete();
    }
}
