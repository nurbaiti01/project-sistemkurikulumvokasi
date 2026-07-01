<div>
    <x-ui.pages.title :title="$title" />
    <section>
        
        <x-ui.table.header title="Beban Ajar Dosen" wire-search="search">
            @can('filter', [App\Models\BebanAjarDosen::class, ['Kaprodi', 'Dosen']])
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
            @can('create', [App\Models\BebanAjarDosen::class, ['Kaprodi']])
                <x-slot name="action">
                    <flux:button variant="primary" wire:click="openModal" color="blue">Import Excel</flux:button>
                    {{-- <flux:button href="{{ route('perangkat-ajar.kontrak-kuliah.create') }}" variant="primary"
                            color="blue" size="sm">
                            Create</flux:button> --}}
                </x-slot>
            @endcan
        </x-ui.table.header>
        @php
            $columnHeaders = [
                'No',
                'Tahun Ajaran',
                'Program Studi',
                'Matakuliah',
                'Dosen',
                'Peran',
                'Kelas',
                'Semester',
                'SKS Beban',
                'Created At',
            ];
            $show = Gate::any(
                ['update', 'delete'],
                [App\Models\BebanAjarDosen::class, ['Kaprodi', 'Dosen', 'Akademik']],
            );
            // if ($show) {
            //     unset($columnHeaders[1]);
            // }
        @endphp
        <x-ui.table.index :columns="$columnHeaders" :showAction="false">
            @forelse ($data as $row)
                <x-ui.table.row>
                    <td class="p-4">{{ $loop->iteration }}</td>
                    <td class="p-4">{{ $row->tahun_ajaran }}</td>
                    <td class="p-4 max-w-[75px]">
                        {{ $row->homeProdi->name }}
                    </td>
                    <td class="p-4">
                        {{ $row->matakuliah->name }}
                    </td>
                    <td class="p-4">{{ $row->dosen->name }}</td>
                    <td class="p-4">{{ $row->peran }}</td>
                    <td class="p-4">{{ $row->kelas }}</td>
                    <td class="p-4">{{ $row->semester }}</td>
                    <td class="p-4">{{ $row->sks_beban }}</td>
                    <td class="p-4">{{ $row->created_at }}</td>
                    <x-ui.table.action :row="$row">
                        {{-- @can('update', [App\Models\BebanAjarDosen::class, $row, ['Kaprodi']])
                            @if ($row->status == 'draft' || $row->status == 'rejected')
                                <flux:button variant="primary" icon="pencil"
                                    href="{{ route('perangkat-ajar.kontrak-kuliah.update', ['id' => $row->id]) }}"
                                    size="sm" wire:navigate />
                            @endif
                        @endcan
                        @can('delete', [App\Models\BebanAjarDosen::class, $row, ['Kaprodi']])
                            @if ($row->status == 'draft')
                                <flux:button variant="danger" icon="trash" label="{{ $row->status }}"
                                    wire:click="openDelete({{ $row->id }})" size="sm" />
                            @endif
                        @endcan --}}
                        {{-- @can('viewAny', [App\Models\BebanAjarDosen::class])
                            <flux:button variant="primary" icon="eye"
                                :href="route('perangkat-ajar.kontrak-kuliah.view', ['id' => $row->id])" size="sm" />
                        @endcan --}}
                    </x-ui.table.action>
                </x-ui.table.row>
            @empty
                <x-ui.table.empty :searchValue="$search" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                    stateSearch="clearSearch" stateAdd="openCreate" colspan="10" />
            @endforelse
        </x-ui.table.index>
        <x-ui.table.pagination :paginator="$data" />
    </section>
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div
                class="w-full max-w-6xl rounded-xl
               bg-white dark:bg-neutral-900
               shadow-2xl ring-1 ring-black/5 dark:ring-white/10
               flex flex-col max-h-[90vh]">

                {{-- Header --}}
                <div
                    class="flex items-center justify-between px-6 py-4 border-b
                    border-neutral-200 dark:border-neutral-800">
                    <div>
                        <h2 class="text-lg font-semibold text-neutral-900 dark:text-neutral-100">
                            Import Beban Ajar
                        </h2>
                        <p class="text-xs text-neutral-500 dark:text-neutral-400">
                            Upload file Excel dan preview data sebelum disimpan ke sistem
                        </p>
                    </div>

                    <button wire:click="closeModal"
                        class="rounded-md p-2 text-neutral-500 hover:bg-neutral-100
                       dark:hover:bg-neutral-800 dark:text-neutral-400 transition">
                        ✕
                    </button>
                </div>

                {{-- Content --}}
                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-6">

                    {{-- Step 1: Download Template --}}
                    <div
                        class="flex items-center justify-between rounded-lg
                       border border-neutral-200 dark:border-neutral-800
                       bg-neutral-50 dark:bg-neutral-800/50 px-4 py-3">
                        <div>
                            <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                Template Excel
                            </p>
                            <p class="text-xs text-neutral-500 dark:text-neutral-400">
                                Gunakan format ini agar data dapat dibaca sistem dengan benar
                            </p>
                        </div>

                        <a href="{{ asset('template/beban-ajar-template.xlsx') }}"
                            class="inline-flex items-center gap-2
                          rounded-md bg-blue-600 px-3 py-1.5 text-xs font-medium text-white
                          hover:bg-blue-700 transition">
                            ⬇ Download
                        </a>
                    </div>

                    {{-- Step 2: Upload --}}
                    <div class="space-y-2">
                        <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                            Upload File
                        </p>

                        <div
                            class="flex flex-col sm:flex-row gap-3 items-start sm:items-center
                           rounded-lg border border-dashed
                           border-neutral-300 dark:border-neutral-700
                           p-4">

                            <input type="file" wire:model="file"
                                class="text-sm text-neutral-600 dark:text-neutral-300" />

                            <button wire:click="uploadPreview"
                                class="inline-flex items-center gap-2
                               rounded-md bg-neutral-900 dark:bg-white
                               px-4 py-2 text-sm font-medium
                               text-white dark:text-neutral-900
                               hover:opacity-90 transition">
                                ⬆ Upload & Preview
                            </button>
                        </div>
                    </div>

                    {{-- Errors --}}
                    @if ($errors)
                        <div
                            class="rounded-lg border border-red-200 dark:border-red-900
                           bg-red-50 dark:bg-red-900/20 p-4">
                            <p class="text-sm font-medium text-red-700 dark:text-red-300 mb-2">
                                Ditemukan Error
                            </p>
                            <ul class="list-disc ml-5 text-xs text-red-600 dark:text-red-400 max-h-32 overflow-auto">
                                @foreach ($errors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Preview --}}
                    @if ($previewData)
                        <div class="space-y-2">
                            <div class="flex justify-between items-center">
                                <p class="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                                    Preview Data ({{ count($previewData) }} baris)
                                </p>
                                <span class="text-xs text-neutral-500 dark:text-neutral-400">
                                    Pastikan semua data sudah benar sebelum disimpan
                                </span>
                            </div>

                            <div
                                class="overflow-auto max-h-[45vh]
                               rounded-lg border
                               border-neutral-200 dark:border-neutral-800">

                                <table class="min-w-full text-xs text-left text-neutral-700 dark:text-neutral-300">
                                    <thead
                                        class="sticky top-0 z-10
                                       bg-neutral-100 dark:bg-neutral-800
                                       text-neutral-900 dark:text-neutral-100">
                                        <tr>
                                            <th class="p-2">Dosen</th>
                                            <th class="p-2">Mata Kuliah</th>
                                            <th class="p-2 text-center">Prodi Ajar</th>
                                            <th class="p-2 text-center">Prodi MK</th>
                                            <th class="p-2 text-center">Semester</th>
                                            <th class="p-2 text-center">Tahun Ajaran</th>
                                            <th class="p-2 text-center">SKS</th>
                                            <th class="p-2 text-center">Peran</th>
                                            <th class="p-2 text-center">Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-neutral-200 dark:divide-neutral-800">
                                        @foreach ($previewData as $row)
                                            <tr class="hover:bg-neutral-50 dark:hover:bg-neutral-800/50 transition">
                                                <td class="p-2 whitespace-nowrap">
                                                    {{ $row['dosen'] }}
                                                </td>
                                                <td class="p-2">
                                                    {{ $row['mk'] }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ $row['taught_prodi'] }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ $row['home_prodi'] }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    <span
                                                        class="inline-flex rounded bg-neutral-200 dark:bg-neutral-700 px-2 py-0.5">
                                                        {{ ucfirst($row['semester']) }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-center">
                                                    {{ $row['tahun_ajaran'] }}
                                                </td>
                                                <td class="p-2 text-center font-medium">
                                                    {{ $row['sks_beban'] }}
                                                </td>
                                                <td class="p-2 text-center">
                                                    <span
                                                        class="inline-flex rounded-md
                                                       bg-blue-100 dark:bg-blue-900/30
                                                       text-blue-700 dark:text-blue-300
                                                       px-2 py-0.5 text-xs font-medium">
                                                        {{ ucfirst($row['peran']) }}
                                                    </span>
                                                </td>
                                                <td class="p-2 text-center font-medium">
                                                    {{ $row['kelas'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                @if ($previewData)
                    <div
                        class="flex items-center justify-between px-6 py-4
                       border-t border-neutral-200 dark:border-neutral-800">

                        <span class="text-xs text-neutral-500 dark:text-neutral-400">
                            Total data siap disimpan: {{ count($previewData) }}
                        </span>

                        <button wire:click="saveAll"
                            class="inline-flex items-center gap-2
                           rounded-md bg-green-600 px-5 py-2 text-sm font-semibold
                           text-white hover:bg-green-700 transition">
                            ✔ Simpan Semua
                        </button>
                    </div>
                @endif

                {{-- Success --}}
                @if ($successCount)
                    <div
                        class="px-6 py-3
                       bg-green-50 dark:bg-green-900/20
                       border-t border-green-200 dark:border-green-800
                       text-sm text-green-700 dark:text-green-300">
                        Berhasil disimpan: <strong>{{ $successCount }}</strong> data
                    </div>
                @endif

            </div>
        </div>

    @endif

</div>
