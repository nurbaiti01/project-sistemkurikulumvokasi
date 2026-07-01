<?php

namespace App\Livewire\Master\Dosen;

use App\Livewire\Base\BaseForm;
use App\Models\ProgramStudi;
use App\Models\Dosen as DSN;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Throwable;
class CreateUpdate extends BaseForm
{
    protected array $relations = ['programStudis'];

    public function mount($id = null)
    {
        if ($id) {
            $this->openEdit($id);
        }


    }
    protected function model(): string
    {
        return DSN::class;
    }

    public function rules(): array
    {
        return [
            'form.nrp' => 'required|string|unique:dosens,nrp,' . $this->selectedId,
            'form.nidn' => 'required|string|unique:dosens,nidn,' . $this->selectedId,
            'form.name' => 'required|string',
            'form.email' => 'required|email|unique:dosens,email,' . $this->selectedId,
            'form.gender' => 'required|string',
            'form.programStudis' => 'required|min:1',
            'form.programStudis.*' => 'exists:program_studis,id',
        ];
    }

    protected function afterSave(Model $item, string $action): void
    {
        if (!in_array($action, ['created', 'updated'], true)) {
            return;
        }

        DB::beginTransaction();

        try {
            if ($action === 'created') {
                // Cegah duplicate user (email unique)
                $user = User::firstOrCreate(
                    ['email' => $item->email],
                    [
                        'name' => $item->name,
                        'password' => Hash::make('12345678'),
                    ]
                );

                // Aman untuk pivot (tidak detach yang sudah ada)
                $item->users()->syncWithoutDetaching($user->id);
                $user->roles()->sync(4);
            }

            if ($action === 'updated') {
                $user = $item->users()->first();
                $user = User::firstOrCreate(
                    ['email' => $item->email],
                    [
                        'name' => $item->name,
                        'password' => Hash::make('12345678'),
                    ]
                );
                $item->users()->syncWithoutDetaching($user->id);
                // }
                $user->roles()->sync(4);
            }

            DB::commit();

        } catch (Throwable $e) {
            DB::rollBack();

            report($e);

            // Opsional: feedback ke UI (Livewire + WireUI)
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Gagal',
                'description' => 'Terjadi kesalahan saat sinkronisasi user.',
            ]);

            throw $e; // biar Livewire tahu ada error
        }
    }
    public function getProdiProperty()
    {
        return ProgramStudi::all();
    }
    public function render()
    {
        return view('livewire.master.dosen.create-update');
    }
}
