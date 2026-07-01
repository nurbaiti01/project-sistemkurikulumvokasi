<div>

    <x-ui.pages.title title="Profile Lulusan" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Profile Lulusan" wire-search="search">
                <x-slot name="filter">
                    @can('filter', [\App\Models\ProfileLulusan::class, ['Kaprodi', 'Dosen']])
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Program Studi</flux:button>

                            <flux:menu>
                                <flux:menu.radio.group wire:model.change="filter.prodi">
                                    @foreach ($this->getProgramStudisProperty() as $ps)
                                        <flux:menu.radio wire:model="filter.prodi" value="{{ $ps->id }}">
                                            {{ $ps->jenjang }} - {{ $ps->name }}</flux:menu.radio>
                                    @endforeach
                                </flux:menu.radio.group>

                            </flux:menu>
                        </flux:dropdown>
                    @endcan
                </x-slot>
                @can('create', [\App\Models\ProfileLulusan::class, ['Kaprodi']])
                    <x-slot name="action">
                        <flux:button type="button" wire:click="openSample" variant="primary" color="indigo" size="sm">
                            Sampel Data</flux:button>
                        <button wire:click="openCreate"
                            class="inline-flex items-center gap-2 rounded-sm bg-sky-500 px-4 py-1.5 text-sm text-white hover:opacity-75">
                            + Create
                        </button>
                    </x-slot>
                @endcan
            </x-ui.table.header>

            <x-ui.table.index :columns="['No', 'Program Studi', 'Kode PL', 'Profile Lulusan', 'Deskripsi']" :showAction="Gate::allows('create', [App\Models\ProfileLulusan::class, ['Kaprodi']])">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            {{ $row->programStudis->map(fn($prodi) => $prodi->jenjang . ' - ' . $prodi->name)->implode(', ') }}
                        </td>
                        <td class="p-4">{{ $row->code }}</td>
                        <td class="p-4">{{ $row->name }}</td>
                        <td class="p-4">{{ $row->description }}</td>
                        <x-ui.table.action edit="openEdit({{ $row->id }})" delete="openDelete({{ $row->id }})"
                            :row="$row" :block="['Kaprodi']" />
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :FilterValue="$filter['prodi']" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif

    <x-ui.pages.section-view>
        @if ($showCreate)
            <livewire:master.p-l.create wire:key="create" />
        @endif
        @if ($showUpdate)
            <livewire:master.p-l.update wire:key="update-{{ $selectedId }}" :selectedId="$selectedId" />
        @endif
    </x-ui.pages.section-view>

    <x-ui.forms.sample-data max="15" min="1" />
</div>
