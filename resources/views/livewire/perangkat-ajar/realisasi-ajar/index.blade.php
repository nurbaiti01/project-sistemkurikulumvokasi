<div>

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Capaian Pembelajaran Lulusan" wire-search="search">
                @can('filter', [App\Models\RealisasiPengajaran::class, ['Kaprodi', 'Dosen']])
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
                @can('create', [App\Models\RealisasiPengajaran::class, ['Dosen']])
                    <x-slot name="action">
                        <flux:button href="{{ route('perangkat-ajar.realisasi-ajar.create') }}" variant="primary"
                            color="blue" size="sm">
                            Create</flux:button>
                    </x-slot>
                @endcan
            </x-ui.table.header>
            @php
                $columnHeaders = [
                    'No',
                    'Program Studi',
                    'Matakuliah',
                    'Semester',
                    'Jumlah SKS',
                    'Tahun AKD',
                    'Dosen Pengampu',
                    'Created At',
                    'Status',
                ];
                $show = Gate::any(['update', 'delete'], [App\Models\RealisasiPengajaran::class, ['Kaprodi', 'Dosen']]);
                if ($show) {
                    unset($columnHeaders[1]);
                }
            @endphp
            <x-ui.table.index :columns="$columnHeaders" :showAction="$show">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        @if (!$show)
                            <td class="p-4 max-w-[75px]">
                                {{ $row->programStudi->name }}
                            </td>
                        @endif
                        <td class="p-4">
                            <div class="flex flex-col">
                                <span class="font-bold">{{ $row->matakuliah->code }}</span>
                                <span>{{ $row->matakuliah->name }}</span>
                            </div>
                        </td>
                        <td class="p-4">{{ $row->matakuliah->semester }}</td>
                        <td class="p-4">{{ $row->matakuliah->sks }}</td>
                        <td class="p-4">{{ $row->tahun_akademik }}</td>
                        <td class="p-4">{{ $row->dosen->name }}</td>
                        <td class="p-4">{{ $row->created_at }}</td>
                        <td class="p-4">{{ $row->status }}</td>
                        <x-ui.table.action :row="$row">
                            @can('update', [App\Models\RealisasiPengajaran::class, $row, ['Dosen']])
                                @if ($row->status == 'draft' || $row->status == 'rejected')
                                    <flux:button variant="primary" icon="pencil"
                                        href="{{ route('perangkat-ajar.realisasi-ajar.update', ['id' => $row->id]) }}"
                                        size="sm" wire:navigate />
                                @endif
                            @endcan
                            @can('delete', [App\Models\RealisasiPengajaran::class, $row, ['Dosen']])
                                @if ($row->status == 'draft')
                                    <flux:button variant="danger" icon="trash" label="{{ $row->status }}"
                                        wire:click="openDelete({{ $row->id }})" size="sm" />
                                @endif
                            @endcan
                            {{-- @can('viewAny', [App\Models\KontrakKuliah::class]) --}}
                            <flux:button variant="primary" icon="eye"
                                :href="route('perangkat-ajar.realisasi-ajar.view', ['id' => $row->id])" wire:navigate
                                size="sm" />
                            @if ($row->status == 'approved')
                                <flux:button variant="primary" icon="document" target="_blank"
                                    :href="route('pdf.preview.realisasi-ajar', ['id' => $row->id])" size="sm" />
                            @endif

                            {{-- @endcan --}}
                        </x-ui.table.action>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" colspan="8" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif

</div>
