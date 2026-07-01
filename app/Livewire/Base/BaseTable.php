<?php

namespace App\Livewire\Base;

use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\BaseQuery;
use Dom\ChildNode;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;

abstract class BaseTable extends Component
{
    use WithPagination, WireUiActions, BaseQuery;

    /**
     * CHILD WAJIB set model & view
     * ex:
     *  protected static string $model = User::class;
     *  protected static string $view  = 'livewire.users.index';
     */
    protected static string $model;
    protected static string $view;

    /* ------------ UI State ------------ */
    public bool $showTable = true;
    public bool $showCreate = false;
    public bool $showUpdate = false;

    /* ------------ Data ------------ */
    public array $relations = [];
    public ?int $selectedId = null;
    public ?int $activeProdi = null;
    public array $filter = [];
    public string $search = '';

    /* ------------ Sort & Pagination ------------ */
    public string $sortBy = 'id';
    public string $sortDirection = 'desc';
    public int $perPage = 10;
    protected array $allowedSorts = ['id', 'created_at'];
    protected array $allowedDirections = ['asc', 'desc'];

    /* ------------ Query Config ------------ */
    protected array $searchable = [];
    protected array $filterable = [];
    protected string $prodiColumn = 'prodi_id';

    /* ------------ Page Info ------------ */
    public string $title = 'Page Title';

    /**
     * Mount lifecycle
     */
    public function mount(): void
    {
        $this->setFilterProdi();
    }

    protected function beforeSetFilterProdi(): void
    {
        
    }

    protected function afterSetFilterProdi(): void
    {
        
    }
    protected function setFilterProdi(): void
    {
        $this->beforeSetFilterProdi();
        if (session('active_role') == 'Kaprodi') {

            $programStudi = auth()->user()
                    ?->dosens()
                    ?->with('programStudis')
                    ?->first()
                    ?->programStudis()
                    ?->first();

            $this->filter['prodi'] = $programStudi?->id;
            $this->activeProdi = $programStudi?->id;
            return;
        }
        $this->afterSetFilterProdi();

        // default reset
        // $this->filter['prodi'] = null;
        // $this->activeProdi = null;
    }


    /**
     * Base query (default)
     */
    protected function baseModelQuery(): Builder
    {
        // dd($this->getModel());
        if (!static::$model) {
            throw new \RuntimeException("Static model belum di-set pada " . static::class);
        }

        if (!empty($this->relations)) {
            return static::$model::query()->with($this->relations);
        }
        return static::$model::query();
    }

    /**
     * Final unified query
     * - use child custom query() if exists
     * - else fallback to baseModelQuery()
     */
    protected function finalQuery(): Builder
    {
        if (method_exists($this, 'query')) {
            return $this->query();
        }

        $builder = $this->baseModelQuery();
        return $this->autoQuery(
            builder: $builder,
            searchble: $this->searchable,
            filterable: $this->filterable,
        );
    }


    protected function filterValue(string $key, $default = null)
    {
        return $this->filter[$key] ?? $default;
    }
    /**
     * Render ke view static
     */
    // #[Title($title: 'title')]

    public function render()
    {
        if (!static::$view) {
            throw new \RuntimeException("Static view belum di-set pada " . static::class);
        }
        $this->dispatch('set-browser-title', title: $this->title);
        return view(static::$view, [
            'data' => $this->finalQuery()->paginate($this->perPage),
        ])->title($this->title);
    }


    /* ------------ UI ACTIONS ------------ */
    public function openCreate(): void
    {
        $this->resetSelected();
        $this->showCreate = true;
        $this->showTable = false;
    }

    public function openEdit(int $id): void
    {
        $this->selectedId = $id;
        $this->showUpdate = true;
        $this->showTable = false;
    }

    public function openDelete(int $id): void
    {
        $this->selectedId = $id;

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Delete the information?',
            'acceptLabel' => 'Yes, delete it',
            'method' => 'confirmDelete',
        ]);
    }

    protected function beforeDelete()
    {

    }

    public function confirmDelete(): void
    {
        $this->beforeDelete();
        static::$model::findOrFail($this->selectedId)->delete();
        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success Notification!',
            'description' => 'Data Berhasil Dihapus',
            'timeout' => 2500
        ]);
        $this->resetPages();
    }

    #[On('cancel'), On('success-created'), On('success-updated')]
    public function cancelForm(): void
    {
        $this->showCreate = false;
        $this->showUpdate = false;
        $this->showTable = true;
        $this->resetPages();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }
    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    protected function resetPages(): void
    {
        $this->resetPage();
        $this->resetSelected();
        $this->reset();
        $this->setFilterProdi();
    }

    protected function resetSelected(): void
    {
        $this->selectedId = null;
    }

    public function clearFilter(): void
    {
        $this->filter = [];
        $this->resetPage();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    public static function getModel(): string
    {
        return static::$model ?? (string) str(class_basename(static::class))
            ->beforeLast('Resource')
            ->prepend('App\\Models\\');
    }
}
