<div>

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Capaian Pembelajaran Lulusan" wire-search="search">

                <x-slot name="filter">
                    @can('filter', [App\Models\Matakuliah::class, ['Kaprodi', 'Dosen']])
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Program Studi</flux:button>

                            <flux:menu>
                                <flux:menu.radio.group wire:model.change="filter.prodi">
                                    <flux:menu.radio wire:model="filter.prodi" value="">Semua Program Studi
                                    </flux:menu.radio>
                                    @foreach ($this->getProdiOptionsProperty() as $ps)
                                        <flux:menu.radio wire:model="filter.prodi" value="{{ $ps->id }}">
                                            {{ $ps->jenjang }} - {{ $ps->name }}</flux:menu.radio>
                                    @endforeach
                                </flux:menu.radio.group>

                            </flux:menu>
                        </flux:dropdown>
                    @endcan
                    <flux:dropdown>
                        <flux:button icon:trailing="chevron-down">Semester</flux:button>

                        <flux:menu>
                            <flux:menu.checkbox.group wire:model.change="filter.semester">
                                @foreach (range(1, $jmlSemester) as $sm)
                                    <flux:menu.checkbox value="{{ $sm }}" keep-open>Semester
                                        {{ $sm }}</flux:menu.checkbox>
                                @endforeach
                            </flux:menu.checkbox.group>
                            <flux:menu.separator />
                            <flux:menu.item variant="danger" wire:click="clearFilter">Clear</flux:menu.item>
                        </flux:menu>
                    </flux:dropdown>
                </x-slot>
                @can('create', [App\Models\Matakuliah::class, ['Kaprodi']])
                    <x-slot name="action">
                        <flux:button type="button" wire:click="openSample" variant="primary" color="indigo" size="sm">
                            Sampel Data</flux:button>
                        <flux:button type="button" wire:click="openCreate" variant="primary" color="blue" size="sm">
                            Create</flux:button>
                    </x-slot>
                @endcan
            </x-ui.table.header>
            @php
                $showAction = Gate::allows('create', [App\Models\Matakuliah::class, ['Kaprodi']]);
                $columnHeaders = ['No', 'Program Studi', 'Kode MK', 'Nama MK', 'Jenis', 'Deskripsi'];
                if ($showAction) {
                    unset($columnHeaders[1]);
                }
            @endphp
            <x-ui.table.index :columns="$columnHeaders" :showAction="$showAction">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        @if (!$showAction)
                            <td class="p-4">
                                {{ $row->programStudis->map(fn($prodi) => $prodi->jenjang . ' - ' . $prodi->name)->implode(', ') }}
                            </td>
                        @endif
                        <td class="p-4">{{ $row->code }}</td>
                        <td class="p-4">{{ $row->name }}</td>
                        {{-- <td class="p-4">{{ $row->sks }}</td>
                        <td class="p-4">{{ $row->semester }}</td> --}}
                        <td class="p-4">{{ $row->jenis }}</td>
                        <td class="p-4">{{ $row->description }}</td>
                        <x-ui.table.action edit="openEdit({{ $row->id }})"
                            delete="openDelete({{ $row->id }})" :row="$row" :allow="['Kaprodi']" />
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" colspan="9" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif

    <x-ui.pages.section-view>
        @if ($showCreate)
            <livewire:master.matakuliah.create-update wire:key="create" />
        @endif
        @if ($showUpdate)
            <livewire:master.matakuliah.create-update wire:key="update-{{ $selectedId }}" :id="$selectedId" />
        @endif
    </x-ui.pages.section-view>

   
    <x-ui.forms.sample-data max="15" min="1">
        <x-slot name="field">
            @foreach (range(1, $jmlSemester) as $s)
                <x-checkbox id="{{ $s }}" label="Semester {{ $s }}" wire:model="form.semester"
                    value="{{ $s }}" />
            @endforeach
        </x-slot>
    </x-ui.forms.sample-data>

</div>
