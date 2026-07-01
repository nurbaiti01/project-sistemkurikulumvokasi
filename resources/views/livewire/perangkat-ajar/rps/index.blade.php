<div class="">

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Rencana Perangkat Ajar" wire-search="search">
                @can('filter', [App\Models\Rps::class, ['Kaprodi', 'Dosen']])
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
                @can('create', [App\Models\Rps::class, ['Dosen']])
                    <x-slot name="action">
                        <flux:button variant="primary" size="sm" color="blue"
                            href="{{ route('perangkat-ajar.rps.create') }}">Create</flux:button>
                    </x-slot>
                @endcan
            </x-ui.table.header>
            @php
                $showAction = Gate::allows('create', [App\Models\Rps::class]);
                $columnHeaders = [
                    'No',
                    'Program Studi',
                    'Matakuliah',
                    'Tahun Akademik',
                    'Kelas',
                    'Di Buat Oleh',
                    'status',
                ];
            @endphp

            <x-ui.table.index :columns="$columnHeaders" :showAction="$showAction">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            {{ $row->programStudi->name }}
                        </td>
                        <td class="p-4">{{ $row->matakuliah->name }}</td>
                        <td class="p-4">{{ $row->academic_year }}</td>
                        <td class="p-4">{{ $row->class }}</td>
                        <td class="p-4">
                            {{ $row->rpsApprovals()?->where('role_proses', 'perumusan')->first()?->dosen?->name }}</td>
                        <td class="p-4">
                            @php
                                $status = match ($row->status) {
                                    'draft' => 'zinc',
                                    'submitted' => 'blue',
                                    'published' => 'green',
                                    'rejected' => 'red',
                                };
                            @endphp
                            <flux:badge color="{{ $status }}" variant="solid">{{ $row->status }}</flux:badge>
                        </td>
                        <x-ui.table.action :row="$row">
                            @can('update', [App\Models\Rps::class, $row, ['Dosen']])
                                @if ($row->status == 'draft' || $row->status == 'rejected')
                                    <flux:button variant="primary" color="blue" size="sm" icon="pencil"
                                        :href="route('perangkat-ajar.rps.update',['id'=>$row->id])"></flux:button>
                                @endif
                            @endcan
                            @can('delete', [$row, ['Dosen']])
                                @if ($row->status == 'draft')
                                    <flux:button variant="primary" color="red" size="sm" icon="trash"
                                        wire:click="openDelete({{ $row->id }})"></flux:button>
                                @endif
                            @endcan
                            <flux:button variant="primary" color="zinc" size="sm" icon="eye"
                                href="{{ route('perangkat-ajar.rps.view', ['id' => $row->id]) }}"></flux:button>
                            @if($row->status == 'published')
                            <flux:button variant="primary" icon="document" target="_blank"
                                :href="route('pdf.preview.rps', ['id' => $row->id])" size="sm" />
                            @endif
                        </x-ui.table.action>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :colspan="10" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" :models="App\Models\Kurikulum::class" :block="['Dosen', 'BPM', 'WADIR 1', 'Direktur', 'Akademik', 'Superadmin']" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif


    @if ($showCreate)
        <livewire:kurikulum.create-update wire:key="create" />
    @endif

    @if ($showUpdate)
        <livewire:kurikulum.create-update wire:key="update-{{ $selectedId }}" :id="$selectedId" />
    @endif
</div>
