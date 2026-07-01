<div class="container mx-auto py-3">

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        <section>
            <x-ui.table.header title="Capaian Pembelajaran Lulusan" wire-search="search">
                @can('filter', [App\Models\Kurikulum::class,['Kaprodi','Dosen']])
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
                @can('create', [App\Models\Kurikulum::class,['Kaprodi']])
                    <x-slot name="action">
                        <button wire:click="openCreate"
                            class="inline-flex items-center gap-2 rounded-sm bg-sky-500 px-4 py-2 text-sm text-white hover:opacity-75">
                            + Create
                        </button>
                    </x-slot>
                @endcan
            </x-ui.table.header>

            <x-ui.table.index :columns="[
                'No',
                'Program Studi',
                'Kurikulum',
                'Tahun',
                'Version',
                'Type',
                'Status',
                'Approval',
                'Di Buat Oleh',
            ]" :showAction="Gate::allows('create', App\Models\Kurikulum::class)">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            {{ $row->programStudis->name }}
                        </td>
                        <td class="p-4">{{ $row->name }}</td>
                        <td class="p-4">{{ $row->year }}</td>
                        <td class="p-4">{{ $row->version }}</td>
                        <td class="p-4">{{ $row->type }}</td>
                        <td class="p-4">{{ $row->status }}</td>
                        <td class="p-4">
                            <div class="space-y-1 text-sm text-neutral-700 dark:text-neutral-300">
                                <div
                                    class="flex justify-between border-b border-neutral-200 dark:border-neutral-700 pb-1">
                                    <span class="font-medium">WADIR 1</span>
                                    @php
                                        $wadirStatus = optional($row->wadirApproval)->status ?? 'Pending';
                                        $wadirColor =
                                            optional($row->wadirApproval)->status == 'approved'
                                                ? 'green'
                                                : (optional($row->wadirApproval)->status == 'rejected'
                                                    ? 'red'
                                                    : 'yellow');
                                    @endphp
                                    <flux:badge color="{{ $wadirColor }}" size="sm">{{ $wadirStatus }}
                                    </flux:badge>
                                </div>
                                <div
                                    class="flex justify-between border-b border-neutral-200 dark:border-neutral-700 pb-1">
                                    <span class="font-medium">DIREKTUR</span>
                                    @php
                                        $direkturStatus = optional($row->direkturApproval)->status ?? 'Pending';
                                        $direkturColor =
                                            optional($row->direkturApproval)->status == 'approved'
                                                ? 'green'
                                                : (optional($row->direkturApproval)->status == 'rejected'
                                                    ? 'red'
                                                    : 'yellow');
                                    @endphp
                                    <flux:badge color="{{ $direkturColor }}" size="sm">{{ $direkturStatus }}
                                    </flux:badge>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-medium">BPM</span>
                                    @php
                                        $bpmStatus = optional($row->bpmApproval)->status ?? 'Pending';
                                        $bpmColor =
                                            optional($row->bpmApproval)->status == 'approved'
                                                ? 'green'
                                                : (optional($row->bpmApproval)->status == 'rejected'
                                                    ? 'red'
                                                    : 'yellow');
                                    @endphp
                                    <flux:badge color="{{ $bpmColor }}" size="sm">{{ $bpmStatus }}
                                    </flux:badge>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">{{ $row->creator->name }}</td>
                        <x-ui.table.action :row="$row">
                            @can('update', [$row, ['Kaprodi']])
                                @if ($row->status == 'draft')
                                    <flux:button variant="primary" color="blue" size="sm" icon="pencil"
                                        :href="route('kurikulum.update',['id'=>$row->id])"></flux:button>
                                @endif
                            @endcan
                            @can('delete', [$row, ['Kaprodi']])
                                @if ($row->status == 'draft')
                                    <flux:button variant="primary" color="red" size="sm" icon="trash"
                                        wire:click="openDelete({{ $row->id }})"></flux:button>
                                @endif
                            @endcan
                            @can('revisi', [$row, ['Kaprodi']])
                                @if (in_array($row->status,['aprroved_wadir','approved_direktur','approved_bpm']))
                                    <flux:button variant="primary" icon="clipboard-document" color="sky" size="sm"
                                        wire:click="openDialogsClone({{ $row->id }})">
                                    </flux:button>
                                @endif
                            @endcan
                            <flux:button variant="primary" color="zinc" size="sm" icon="eye"
                                href="{{ route('kurikulum.matriks-data', ['id' => $row->id]) }}"></flux:button>
                        </x-ui.table.action>
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :colspan="10" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" :models="App\Models\Kurikulum::class" :block="['Dosen','BPM','WADIR 1','Direktur','Akademik','Superadmin']"/>
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
