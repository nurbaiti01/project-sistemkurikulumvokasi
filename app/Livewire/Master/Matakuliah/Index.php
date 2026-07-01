<?php

namespace App\Livewire\Master\Matakuliah;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

use App\Models\ProgramStudi;
use App\Models\Matakuliah as MK;

#[Layout('components.layouts.sidebar')]

class Index extends BaseTable
{
    public string $title = 'Matakuliah';
    public array $filter = [
        'prodi' => null,
        'semester' => null
    ];
    protected static string $model = MK::class;
    protected static string $view = 'livewire.master.matakuliah.index';

    public array $relations = ['programStudis'];
    protected array $filterable = [
        'semester' => ['type' => 'column', 'column' => 'semester'],
        'prodi' => ['type' => 'relation', 'relation' => 'programStudis', 'column' => 'program_studis.id'],
    ];
    protected array $searchable = [
        'name',
        'code'
    ];

    public $jmlSemester = 0;
    public $form = [
        'prodi' => [],
        'jumlah' => 1,
        'semester' => []
    ];

    public function mount(): void
    {
        $this->setFilterProdi();
        $this->jmlSemester = $this->resolveJumlahSemester();
    }

    protected function beforeSetFilterProdi(): void
    {
        if (session('active_role') == 'Dosen') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->filter['prodi'] = $programStudi?->id;

            return;
        }
    }
    protected function resolveJumlahSemester(): int
    {
        $prodiId = $this->filter['prodi'] ?? null;
        if (!$prodiId) {
            return 8;
        }

        $jenjang = ProgramStudi::find($prodiId)?->jenjang;

        return match ($jenjang) {
            'D3' => 6,
            'D4', 'S1' => 8,
            default => 8,
        };
    }
    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
    }

    public function openSample()
    {
        if ($this->filter['prodi'] == null) {
            $this->form['prodi'] = ProgramStudi::pluck('id')->toArray();
            $this->jmlSemester = 0;
        } else {
            $this->form['prodi'] = [$this->filter['prodi']];
            $this->jmlSemester = ProgramStudi::find($this->filter['prodi'])->jenjang == 'D3' ? 6 : 8;
            $this->form['semester'] = range(1, $this->jmlSemester);
        }
        $this->modal()->open('sampleModal');
    }

    public function generateSample()
    {
        $this->validate([
            'form.prodi' => 'required|array|min:1',
            'form.jumlah' => 'required|integer|min:1',
            'form.semester' => 'required|array|min:1',
        ]);

        DB::transaction(function () {
            $faker = Faker::create('id_ID');

            foreach ($this->form['prodi'] as $prodiId) {

                foreach ($this->form['semester'] as $semester) {

                    foreach (range(1, $this->form['jumlah']) as $i) {
                        $mk = MK::create([
                            'code' => 'MK-' . strtoupper($faker->unique()->bothify('???###')),
                            'name' => $faker->words(3, true),
                            'description' => $faker->sentence(15),
                            'jenis' => $faker->randomElement(['T', 'P']),
                        ]);

                        $mk->programStudis()->attach($prodiId);
                    }
                }
            }
        });

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Data berhasil digenerate berdasarkan semester & prodi',
        ]);

        $this->modal()->close('sampleModal');
    }


}
