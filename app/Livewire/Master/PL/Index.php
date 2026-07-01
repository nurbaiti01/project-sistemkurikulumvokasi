<?php

namespace App\Livewire\Master\PL;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use App\Models\ProfileLulusan;
use App\Models\ProgramStudi;
use Faker\Factory as Faker;


#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{
    public string $title = 'Profile Lulusan';
    public array $filter = [
        'prodi' => null,
    ];

    public $form = [
        'prodi' => [],
        'jumlah' => 1
    ];
    protected static string $model = ProfileLulusan::class;
    protected static string $view = 'livewire.master.p-l.index';

    public array $relations = ['programStudis'];
    protected array $filterable = [
        'prodi' => ['type' => 'relation', 'relation' => 'programStudis', 'column' => 'program_studis.id'],
    ];
    protected array $searchable = [
        'name',
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

    public function getProgramStudisProperty()
    {
        return ProgramStudi::all();
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

        DB::transaction(function () {
            $profiles = ProfileLulusan::factory()
                ->count($this->form['jumlah'])
                ->create();

            $profiles->each(function ($profile) {
                $profile->programStudis()->attach($this->form['prodi']);
            });
        });
       
        $this->modal()->close('sampleModal');
    }

}
