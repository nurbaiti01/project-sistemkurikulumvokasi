<?php

namespace App\Livewire\Master\Dosen;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use App\Repositories\ApiClientRepository;
use App\Services\UserCreatorService;
use Flux\Flux;
use App\Models\ProgramStudi;
use App\Models\Dosen as DSN;
use App\Models\User;
#[Title('Dosen')]
#[Layout('components.layouts.sidebar')]

class Index extends BaseTable
{
    public string $title = 'Dosen';
    protected string $apiUrl = "https://siak.poltek-kampar.ac.id/data_dosen/apidosen";
    public array $filter = [
        'prodi' => null
    ];
    protected static string $model = DSN::class;
    protected static string $view = 'livewire.master.dosen.index';

    public array $relations = ['programStudis'];
    protected array $filterable = [
        'prodi' => ['type' => 'relation', 'relation' => 'programStudis', 'column' => 'program_studis.id'],
    ];
    protected array $searchable = [
        'nrp',
        'nidn',
        'name',
        'email'
    ];

    public array $dataApi = [];

    public function getProdiOptionsProperty()
    {
        return ProgramStudi::query()
            ->orderBy('name')
            ->get(['id', 'name', 'jenjang']);
    }

    public function openModal()
    {
        $this->dataApi = [];
        $this->modal()->open('simpleModal');
        // Flux::modal('edit-profile')->show();
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
            'nrp' => $item['nrp'],
            'nidn' => $item['nidn'],
            'name' => $item['nama_dsn'],
            'email' => $item['email'],
            'gender' => $item['jenis_k'] == 'L' ? 'Laki-laki' : 'Perempuan',
            'programStudis' => [$item['prodi_id']],
        ])->toArray();
    }

    public function syncToDatabase()
    {
        DB::transaction(function () {

            $userService = new UserCreatorService();
            $now = now();

            $payload = collect($this->dataApi);

            /**
             * =====================
             * 1️⃣ UPSERT DSN (BULK)
             * =====================
             */
            DSN::upsert(
                $payload->map(fn($item) => [
                    'nrp' => $item['nrp'],
                    'nidn' => $item['nidn'],
                    'name' => $item['name'],
                    'email' => $item['email'],
                    'gender' => $item['gender'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ])->toArray(),
                ['nrp', 'nidn'],
                ['name', 'email', 'gender', 'updated_at']
            );

            /**
             * =====================
             * 2️⃣ CACHE DSN MAP
             * =====================
             */
            $dsnMap = DSN::whereIn('nrp', $payload->pluck('nrp'))
                ->orWhereIn('nidn', $payload->pluck('nidn'))
                ->get()
                ->keyBy(fn($d) => $d->nrp ?: $d->nidn);

            /**
             * =====================
             * 3️⃣ CACHE PROGRAM STUDI MAP
             * =====================
             */
            $allProdiCodes = $payload
                ->pluck('programStudis')
                ->flatten()
                ->unique()
                ->values();

            $prodiMap = ProgramStudi::whereIn('code', $allProdiCodes)
                ->pluck('id', 'code');

            /**
             * =====================
             * 4️⃣ CACHE USERS MAP
             * =====================
             */
            $emails = $payload->pluck('email')->unique();

            $userMap = User::whereIn('email', $emails)
                ->get()
                ->keyBy('email');

            $userInsert = [];

            foreach ($payload as $item) {
                if (!isset($userMap[$item['email']])) {
                    $userInsert[] = [
                        'name' => $item['name'],
                        'email' => $item['email'],
                        'password' => bcrypt('12345678'), // atau random
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            if (!empty($userInsert)) {
                User::insert($userInsert);

                $userMap = User::whereIn('email', $emails)
                    ->get()
                    ->keyBy('email');
            }

            /**
             * =====================
             * 5️⃣ PREPARE PIVOTS (BULK)
             * =====================
             */
            $dsnUserPivot = [];
            $dsnProdiPivot = [];
            $userRolePivot = [];

            foreach ($payload as $item) {
                $dsn = $dsnMap[$item['nrp']] ?? $dsnMap[$item['nidn']] ?? null;
                $user = $userMap[$item['email']] ?? null;

                if (!$dsn || !$user) {
                    continue;
                }

                // DSN → USER
                $dsnUserPivot[] = [
                    'dosen_id' => $dsn->id,
                    'user_id' => $user->id,
                ];

                // ROLE DEFAULT = 4
                $userRolePivot[] = [
                    'user_id' => $user->id,
                    'role_id' => 3,
                ];

                // DSN → PROGRAM STUDI
                foreach ($item['programStudis'] as $code) {
                    if (!isset($prodiMap[$code]))
                        continue;

                    $dsnProdiPivot[] = [
                        'dosen_id' => $dsn->id,
                        'prodi_id' => $prodiMap[$code],
                    ];
                }
            }

            /**
             * =====================
             * 6️⃣ BULK INSERT PIVOTS
             * =====================
             */
            DB::table('tx_user_dosens')->upsert(
                $dsnUserPivot,
                ['dosen_id', 'user_id'],
                []
            );

            DB::table('tx_dosen_prodis')->upsert(
                $dsnProdiPivot,
                ['dosen_id', 'prodi_id'],
                []
            );

            DB::table('tx_user_roles')->upsert(
                $userRolePivot,
                ['user_id', 'role_id'],
                []
            );
        });

        /**
         * =====================
         * UI FEEDBACK
         * =====================
         */
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success',
            'description' => 'Data Berhasil di sinkronkan (Optimized)',
        ]);

        $this->modal()->close('simpleModal');
    }

    // public function syncToDatabase()
    // {
    //     $userService = new UserCreatorService();

    //     DSN::upsert(
    //         collect($this->dataApi)
    //             ->map(fn($item) => [
    //                 'nrp' => $item['nrp'],
    //                 'nidn' => $item['nidn'],
    //                 'name' => $item['name'],
    //                 'email' => $item['email'],
    //                 'gender' => $item['gender'],
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ])->toArray(),
    //         ['nrp', 'nidn'], // unique keys
    //         ['name', 'email', 'gender', 'updated_at']
    //     );

    //     foreach ($this->dataApi as $item) {
    //         $dsn = DSN::where('nrp', $item['nrp'])
    //             ->orWhere('nidn', $item['nidn'])
    //             ->first();

    //         if (!$dsn)
    //             continue;
    //         $prodiIds = ProgramStudi::whereIn('code', $item['programStudis'])
    //             ->pluck('id')
    //             ->toArray();

    //         if (!empty($prodiIds)) {
    //             $dsn->programStudis()->sync($prodiIds);
    //         }
    //         // ⬇️ Create or retrieve user
    //         $user = $userService->createOrGet(
    //             email: $item['email'],
    //             name: $item['name']
    //         );

    //         // ⬇️ Attach pivot DSN → User
    //         $userService->attachToPivot($dsn, $user);

    //         // ⬇️ Assign default role (role_id=4)
    //         $userService->assignRoles($user, 4);
    //     }
    //     $this->notification()->send([
    //         'icon' => 'success',
    //         'title' => 'Success',
    //         'description' => 'Data Berhasil di sinkronkan',
    //     ]);

    //     $this->modal()->close('simpleModal');
    // }
}
