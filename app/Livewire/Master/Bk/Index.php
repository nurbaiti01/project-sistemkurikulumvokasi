<?php

namespace App\Livewire\Master\Bk;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\ProgramStudi;
use App\Models\BahanKajian as BK;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
/**
 * Livewire Bahan Kajian List Table
 *
 * Menggunakan BaseTable untuk:
 *  - pagination
 *  - search
 *  - filter
 *  - auto query builder
 *  - toggle create / update / table
 */
#[Layout('components.layouts.sidebar')]
class Index extends BaseTable
{
    public string $title = 'Bahan Kajian';
    /* ----------------------------------------
     | Model & View
     |---------------------------------------- */
    protected static string $model = BK::class;
    protected static string $view = 'livewire.master.bk.index';

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
    ];

    /**
     * daftar kolom pencarian
     */
    protected array $searchable = ['name', 'code'];

    /**
     * nilai default filter
     */
    public array $filter = [
        'prodi' => null,
    ];
    public $form = [
        'prodi' => [],
        'jumlah' => 1
    ];
    /* ----------------------------------------
     | Computed Property
     |---------------------------------------- */
    /**
     * options dropdown program studi
     */
    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
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
                    $cpl = BK::create([
                        'code' => 'BK-' . $faker->unique()->bothify('###'),
                        'name' => $faker->sentence(5),
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
