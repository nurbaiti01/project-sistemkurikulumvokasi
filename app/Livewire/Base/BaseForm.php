<?php

namespace App\Livewire\Base;

use Livewire\Component;
use WireUi\Traits\WireUiActions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

abstract class BaseForm extends Component
{
    use WireUiActions;
    protected $user;
    public $isKaprodi = false;

    public ?int $selectedId = null;
    public bool $showForm = false;

    /** Form fields */
    public array $form = [];

    /** Define Model class in child */
    abstract protected function model(): string;

    /** Define validation rules in child */
    abstract protected function rules(): array;

    /** Optional: define relations for sync */
    protected array $relations = [];

    public function boot()
    {
        $this->user = Auth::user();
        $this->isKaprodi();

    }

    protected function isKaprodi()
    {
        $isKaprodi = session('active_role') == 'Kaprodi';

        if ($isKaprodi) {
            $programStudi = $this->user
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();
            // $this->selectedId = $programStudi?->id;
            $this->form['programStudis'] = [$programStudi?->id];
            $this->isKaprodi = true;
        }
    }

    /** Open form for create */
    public function openCreate()
    {
        $this->resetForm();
        $this->selectedId = null;
        $this->showForm = true;
    }

    /** Open form for edit */
    public function openEdit($id)
    {
        $this->selectedId = $id;
        $this->loadData();
        $this->showForm = true;
    }

    /** Load data from model */
    protected function loadData()
    {
        if (!$this->selectedId)
            return;

        $item = $this->model()::findOrFail($this->selectedId);

        $this->form = $item->toArray();

        /** Load relations if defined */
        foreach ($this->relations as $relation) {
            if (method_exists($item, $relation)) {
                $this->form[$relation] = $item->$relation->pluck('id')->toArray();
            }
        }
    }

    /**
     * Hook before save (create / update)
     * Bisa dioverride di child
     */
    protected function beforeSave(string $action, ?int $cloneKurikulumId = null): void
    {
        // default: do nothing
    }

    /**
     * Hook after save (create / update)
     * Bisa dioverride di child
     */
    protected function afterSave(Model $item, string $action): void
    {
        // default: do nothing
    }

    /** Save or update */
    public function save()
    {
        $this->validate();

        $modelClass = $this->model();

        $action = $this->selectedId ? 'updated' : 'created';

        /** 🔥 BEFORE SAVE HOOK */
        $this->beforeSave($action);

        if ($action === 'updated') {
            $item = $modelClass::findOrFail($this->selectedId);
            $item->update($this->form);
        } else {
            $item = $modelClass::create($this->form);
        }

        /** Sync relations if any */
        foreach ($this->relations as $relation) {
            if (isset($this->form[$relation])) {
                $item->$relation()->sync($this->form[$relation]);
            }
        }

        /** 🔥 AFTER SAVE HOOK */
        $this->afterSave($item, $action);

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Sukses!',
            'description' => "Data berhasil {$action}.",
            'timeout' => 2500
        ]);

        $this->dispatch("success-{$action}");

        $this->resetForm();
        $this->showForm = false;
    }


    /** Cancel form */
    public function cancel()
    {
        $this->resetForm();
        $this->showForm = false;
        $this->dispatch('cancel');
    }

    /** Reset form */
    protected function resetForm()
    {
        $this->form = [];
        $this->selectedId = null;
    }
}
