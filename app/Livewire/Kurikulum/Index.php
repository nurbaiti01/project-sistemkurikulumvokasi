<?php

namespace App\Livewire\Kurikulum;

use App\Livewire\Base\BaseTable;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Database\Eloquent\Builder;

use App\Models\ProgramStudi;
use App\Models\Kurikulum;


#[Title('Data Kurikulum')]

class Index extends BaseTable
{

    public string $title = 'Data Kurikulum';
    /* ----------------------------------------
     | Model & View
     |---------------------------------------- */
    protected static string $model = Kurikulum::class;
    protected static string $view = 'livewire.kurikulum.index';
    public array $relations = ['programStudis', 'creator'];

    protected array $filterable = [
        'prodi' => [
            'type' => 'relation',
            'relation' => 'programStudis',
            'column' => 'program_studis.id',
        ],
        'status'=> [
            'type' => 'column',
            'column' => 'status'
        ]
    ];

    protected array $searchable = ['name', 'year'];

    public array $filter = [
        'prodi' => null,
        'status' => null
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
            $this->filter['status']= 'published';
            return;
        }
        $role = session('active_role');
        $inArray = ['Kaprodi', 'WADIR 1', 'Direktur', 'BPM'];
        // if(in_array($role, $inArray)){
        //     $this->filter['status']= ['published','approved_direktur','approved_wadir','approved_bpm','archived','submitted'];
        //     return;
        // }
        // if(session('active_role') == 'WADIR 1'){
        //     $this->filter['status']= ['submitted','published','archived'];
        //     return;
        // }
        // if(session('active_role') == 'Direktur'){
        //     $this->filter['status']= ['published','approved_wadir','archived','submitted'];
        //     return;
        // }
    }

    public function confirmDelete(): void
    {
        Kurikulum::findOrFail($this->selectedId)->delete();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Berhasil Dihapus',
            'timeout' => 2500
        ]);
        $this->reset();
    }


    public function getProdiOptionsProperty()
    {
        return ProgramStudi::all();
    }

    public function openDialogsClone(int $id)
    {
        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Aksi ini akan clone kurikulum saat ini dan membuat data baru yang kemudian anda edit sesuia kebutuhan',
            'acceptLabel' => 'Yes, Revisi And Clone',
            'method' => 'cloneKurikulum',
            'params' => $id,
        ]);
    }

    public function cloneKurikulum(int $id): void
    {
        $old = Kurikulum::with([
            'pivotPlCpl',
            'pivotCplBk',
            'pivotBkMk',
            'pivotCpmkSubCpmk',
            'pivotCplMk',
            'pivotCpmkMk',
            'pivotCplBkMk',
            'pivotCplCpmkMk',
        ])->findOrFail($id);

        // 1️⃣ Buat kurikulum baru sebagai clone
        $new = Kurikulum::create([
            'prodi_id' => $old->prodi_id,
            'name' => $old->name,
            'year' => $old->year,
            'version' => $old->version,
            'parent_id' => $old->id, // parent = kurikulum lama
            'type' => $old->type,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        $kurikulumId = $new->id;

        // 2️⃣ Copy pivot CPL ↔ PL
        foreach ($old->pivotPlCpl as $pivot) {
            $new->pivotPlCpl()->create([
                'kurikulum_id' => $kurikulumId,
                'pl_id' => $pivot->pl_id,
                'cpl_id' => $pivot->cpl_id,
            ]);
        }

        // 3️⃣ Copy pivot BK ↔ CPL
        foreach ($old->pivotCplBk as $pivot) {
            $new->pivotCplBk()->create([
                'kurikulum_id' => $kurikulumId,
                'bk_id' => $pivot->bk_id,
                'cpl_id' => $pivot->cpl_id,
            ]);
        }

        // 4️⃣ Copy pivot BK ↔ MK
        foreach ($old->pivotBkMk as $pivot) {
            $new->pivotBkMk()->create([
                'kurikulum_id' => $kurikulumId,
                'bk_id' => $pivot->bk_id,
                'mk_id' => $pivot->mk_id,
            ]);
        }

        // 5️⃣ Copy pivot CPMK ↔ SubCPMK
        foreach ($old->pivotCpmkSubCpmk as $pivot) {
            $new->pivotCpmkSubCpmk()->create([
                'kurikulum_id' => $kurikulumId,
                'cpmk_id' => $pivot->cpmk_id,
                'subcpmk_id' => $pivot->subcpmk_id,
            ]);
        }

        // 6️⃣ Copy pivot MK ↔ CPL
        foreach ($old->pivotCplMK as $pivot) {
            $new->pivotCplMk()->create([
                'kurikulum_id' => $kurikulumId,
                'mk_id' => $pivot->mk_id,
                'cpl_id' => $pivot->cpl_id,
            ]);
        }

        // 7️⃣ Copy pivot CPMK ↔ MK
        foreach ($old->pivotCpmkMk as $pivot) {
            $new->pivotCpmkMk()->create([
                'kurikulum_id' => $kurikulumId,
                'cpmk_id' => $pivot->cpmk_id,
                'mk_id' => $pivot->mk_id,
            ]);
        }

        // 8️⃣ Copy pivot CPL ↔ BK ↔ MK
        foreach ($old->pivotCplBkMk as $pivot) {
            $new->pivotCplBkMk()->create([
                'kurikulum_id' => $kurikulumId,
                'cpl_id' => $pivot->cpl_id,
                'bk_id' => $pivot->bk_id,
                'mk_id' => $pivot->mk_id,
            ]);
        }

        // 9️⃣ Copy pivot CPL ↔ CPMK ↔ MK
        foreach ($old->pivotCplCpmkMk as $pivot) {
            $new->pivotCplCpmkMk()->create([
                'kurikulum_id' => $kurikulumId,
                'cpl_id' => $pivot->cpl_id,
                'cpmk_id' => $pivot->cpmk_id,
                'mk_id' => $pivot->mk_id,
            ]);
        }

        // 10️⃣ Bind ke form
        $this->redirect(route('kurikulum.update', ['id' => $kurikulumId]));
    }

}
