<div>

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Capaian Pembelajaran Lulusan" wire-search="search">
                @can('filter', [App\Models\CapaianPembelajaranMatakuliah::class, ['Kaprodi', 'Dosen']])
                    <x-slot name="filter">
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Program Studi</flux:button>

                            <flux:menu>
                                <flux:menu.radio.group wire:model.change="filter.prodi">
                                    @foreach ($this->getProdiOptionsProperty() as $ps)
                                        <flux:menu.radio wire:model="filter.prodi" value="{{ $ps->id }}">
                                            {{ $ps->jenjang }} - {{ $ps->name }}</flux:menu.radio>
                                    @endforeach
                                </flux:menu.radio.group>

                            </flux:menu>
                        </flux:dropdown>
                    </x-slot>
                @endcan
                @can('create', [App\Models\CapaianPembelajaranMatakuliah::class, ['Kaprodi']])
                    <x-slot name="action">
                        <flux:button type="button" wire:click="openSample" variant="primary" color="indigo" size="sm">
                            Sampel Data</flux:button>
                        <flux:button type="button" wire:click="openCreate" variant="primary" color="blue" size="sm">
                            Create</flux:button>
                    </x-slot>
                @endcan
            </x-ui.table.header>
            @php
                $columnHeaders = ['No', 'Program Studi', 'Kode CPMK', 'Deskripsi'];
                if (Gate::allows('create', [App\Models\CapaianPembelajaranMatakuliah::class, ['Kaprodi']])) {
                    unset($columnHeaders[1]);
                }
            @endphp
            <x-ui.table.index :columns="$columnHeaders" :showAction="Gate::allows('create', [App\Models\CapaianPembelajaranMatakuliah::class, ['Kaprodi']])">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4 max-w-[45px]">{{ $loop->iteration }}</td>
                        @if (!Gate::allows('create', [App\Models\CapaianPembelajaranMatakuliah::class, ['Kaprodi']]))
                            <td class="p-4 max-w-[75px]">
                                {{ $row->programStudis->map(fn($prodi) => $prodi->jenjang . ' - ' . $prodi->name)->implode(', ') }}
                            </td>
                        @endif
                        <td class="p-4 max-w-[25px]">{{ $row->code }}</td>
                        <td class="p-4 max-w-[350px]">{{ $row->description }}</td>
                        <x-ui.table.action edit="openEdit({{ $row->id }})" delete="openDelete({{ $row->id }})"
                            :row="$row" :block="['Kaprodi']" />
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif

    <x-ui.pages.section-view>
        @if ($showCreate)
            <livewire:master.cpmk.create-update wire:key="create" />
        @endif
        @if ($showUpdate)
            <livewire:master.cpmk.create-update wire:key="update-{{ $selectedId }}" :id="$selectedId" />
        @endif
    </x-ui.pages.section-view>
  
    <x-ui.forms.sample-data max="15" min="1" />
</div>
