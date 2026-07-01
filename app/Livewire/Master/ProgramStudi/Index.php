<?php

namespace App\Livewire\Master\ProgramStudi;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;

use App\Models\ProgramStudi;
use App\Models\ProgramStudi as PRODI;
use App\Repositories\ApiClientRepository;
use Flux\Flux;
#[Title('Program Studi')]
#[Layout('components.layouts.sidebar')]

class Index extends BaseTable
{
    protected string $apiUrl = "https://siak.poltek-kampar.ac.id/data_prodi/apiProdi";
    public string $title = 'Program Studi';
    protected array $searchable = [
        'code',
        'name',
    ];
    protected static string $model = PRODI::class;
    protected static string $view = 'livewire.master.program-studi.index';

    public array $dataApi = [];
    
    public function openModal()
    {
        $this->dataApi = [];
        Flux::modal('edit-profile')->show();
    }

    public function getDataFromApi(ApiClientRepository $api)
    {
        $response = $api->get($this->apiUrl);

        if (empty($response)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => 'Gagal mengambil data dari API (timeout). Coba lagi nanti',
            ]);
            return;
        }

        $this->dataApi = collect($response)->map(fn($item) => [
            'code' => $item['kode_prodi'],
            'name' => $item['nama_prodi'],
            'jenjang' => $item['jenjang'],
            'singkatan' => $item['jenjang'] . '-' . $item['singkatan'],
        ])->toArray();
    }

    public function syncToDatabase()
    {
        PRODI::upsert(
            collect($this->dataApi)
                ->map(fn($item) => [
                    ...$item,
                    'created_at' => now(),
                    'updated_at' => now()
                ])->toArray(),
            ['code'],
            ['name', 'jenjang', 'singkatan', 'updated_at']
        );

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Data Berhasil di sinkronkan',
        ]);

        Flux::modal('edit-profile')->close();
    }

}
