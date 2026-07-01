<?php

namespace App\Livewire\Master\Cpmk;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;
use Faker\Factory as Faker;
use App\Models\ProgramStudi;
use App\Models\CapaianPembelajaranMatakuliah as CPMK;
use Illuminate\Support\Facades\DB;
#[Layout('components.layouts.sidebar')]

class Index extends BaseTable
{
    public string $title = 'Capaian Pembelajaran Matakuliah';
    public array $filter = [
        'prodi' => null
    ];
    protected static string $model = CPMK::class;
    protected static string $view = 'livewire.master.cpmk.index';

    public array $relations = ['programStudis'];
    protected array $filterable = [
        'prodi' => ['type' => 'relation', 'relation' => 'programStudis', 'column' => 'program_studis.id'],
    ];
    protected array $searchable = [
        'description',
        'code'
    ];

    public $form = [
        'prodi' => [],
        'jumlah' => 1
    ];
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
        } else {
            $this->form['prodi'] = [$this->filter['prodi']];
        }
        $this->modal()->open('sampleModal');
    }

    public function generateSample()
    {
        $this->validate([
            'form.prodi' => 'required',
            'form.jumlah' => 'required',
        ]);

        foreach ($this->form['prodi'] as $prodi) {
            DB::transaction(function () use ($prodi) {
                $faker = Faker::create('id_ID');
                for ($i = 0; $i < $this->form['jumlah']; $i++) {
                    $cpl = CPMK::create([
                        'code' => 'CPMK-' . $faker->unique()->bothify('###'),
                        'description' => $faker->sentence(25),
                    ]);
                    $cpl->programStudis()->attach($prodi);
                }
            });

        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Data Capaian Pembelajaran Lulusan Berhasil Di Generate',
        ]);

        $this->modal()->close('sampleModal');
    }
}
