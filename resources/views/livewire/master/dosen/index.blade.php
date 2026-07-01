<div>

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        @php
            $showAction = Gate::allows('create', \App\Models\Dosen::class);
        @endphp
        <section>
            <x-ui.table.header :title="$title" wire-search="search">
                @can('filter', [\App\Models\Dosen::class, ['Kaprodi']])
                    <x-slot name="filter">
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down">Program Studi</flux:button>

                            <flux:menu>
                                <flux:menu.radio.group wire:model.change="filter.prodi">
                                    <flux:menu.radio value="">All</flux:menu.radio>
                                    @foreach ($this->getProdiOptionsProperty() as $ps)
                                        <flux:menu.radio wire:model="filter.prodi" value="{{ $ps->id }}">
                                            {{ $ps->jenjang }} - {{ $ps->name }}</flux:menu.radio>
                                    @endforeach
                                </flux:menu.radio.group>

                            </flux:menu>
                        </flux:dropdown>
                    </x-slot>
                @endcan
                @can('create', [\App\Models\Dosen::class, ['Kaprodi', 'Superadmin', 'Akademik']])
                    <x-slot name="action">
                        <flux:button variant="primary" wire:click="openModal" size="sm">Sync Data From Siak
                        </flux:button>
                        <flux:button type="button" wire:click="openCreate" variant="primary" color="blue" size="sm">
                            Create</flux:button>
                    </x-slot>
                @endcan
            </x-ui.table.header>

            <x-ui.table.index :columns="['No', 'Program Studi', 'NRP/NIDN', 'Nama Dosen', 'Email', 'Gender']" :showAction="Gate::allows('create', [\App\Models\Dosen::class, ['Kaprodi', 'Superadmin', 'Akademik']])">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">
                            {{ $row->programStudis->map(fn($prodi) => $prodi->name)->implode(', ') }}
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-2">
                                <span>NRP : {{ $row->nrp }}</span>
                                <span>NIDN : {{ $row->nidn }}</span>
                            </div>
                        </td>
                        <td class="p-4">{{ $row->name }}</td>
                        <td class="p-4">{{ $row->email }}</td>
                        <td class="p-4">{{ $row->gender }}</td>
                        <x-ui.table.action edit="openEdit({{ $row->id }})"
                            delete="openDelete({{ $row->id }})" :row="$row" :allow="['Kaprodi', 'Superadmin', 'Akademik']" />
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" colspan="7" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif


    <x-ui.pages.section-view>
        @if ($showCreate)
            <livewire:master.dosen.create-update wire:key="create" />
        @endif
        @if ($showUpdate)
            <livewire:master.dosen.create-update wire:key="update-{{ $selectedId }}" :id="$selectedId" />
        @endif
    </x-ui.pages.section-view>

    <x-modal-card name="simpleModal" title="Sync Data" persistent width="4xl" align="center">

        <div>
            <flux:button variant="primary" wire:click="getDataFromApi">Tarik Data</flux:button>
            <div class="w-full rounded-sm border border-neutral-300 dark:border-neutral-700 mt-3">

                <!-- WRAPPER SCROLLABLE -->
                <div class="max-h-72 overflow-y-auto">
                    <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                        <thead
                            class="sticky top-0 z-10 border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <tr>
                                <th scope="col" class="p-4">NRP/NID</th>
                                <th scope="col" class="p-4">Nama Dosen</th>
                                <th scope="col" class="p-4">Email</th>
                                <th scope="col" class="p-4">Jenis Kelamin</th>
                                <th scope="col" class="p-4">Kode Prodi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                            @foreach ($dataApi as $value)
                                <tr>
                                    <td class="p-4">{{ $value['nrp'] }} / {{ $value['nidn'] }}</td>
                                    <td class="p-4">{{ $value['name'] }}</td>
                                    <td class="p-4">{{ $value['email'] }}</td>
                                    <td class="p-4">{{ $value['gender'] }}</td>
                                    <td class="p-4">
                                        {{ !empty($value['programStudis']) ? implode(', ', $value['programStudis']) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <span class="text-gray-800 dark:text-white">Jumlah Data Di Tarik : {{ count($dataApi) }}</span>

        </div>
        <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />

            <flux:button type="button" wire:click="syncToDatabase" variant="primary">Sync To Database</flux:button>
        </x-slot>
    </x-modal-card>

</div>
