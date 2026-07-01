<div>

    <x-ui.pages.title :title="$title" />
    @if ($showTable)
        @php
            $showAction = Gate::allows('create', [
                App\Models\ProgramStudi::class,
                ['Kaprodi', 'Dosen', 'BPM', 'WADIR 1', 'Direktur'],
            ]);
        @endphp
        <section>
            <x-ui.table.header :title="$title" wire-search="search">
                @if ($showAction)
                    <x-slot name="action">
                        <flux:button variant="primary" wire:click="openModal" size="sm">Sync Data From Siak
                        </flux:button>
                        <flux:button type="button" wire:click="openCreate" variant="primary" color="blue" size="sm">
                            Create</flux:button>
                    </x-slot>
                @endif
            </x-ui.table.header>

            <x-ui.table.index :columns="['No', 'Kode Program Studi', 'Nama Program Studi', 'Jenjang', 'Singkatan']" :showAction="$showAction">
                @forelse ($data as $row)
                    <x-ui.table.row>
                        <td class="p-4">{{ $loop->iteration }}</td>
                        <td class="p-4">{{ $row->code }}</td>
                        <td class="p-4">{{ $row->name }}</td>
                        <td class="p-4">{{ $row->jenjang_label }}</td>
                        <td class="p-4">{{ $row->singkatan }}</td>
                        <x-ui.table.action edit="openEdit({{ $row->id }})" delete="openDelete({{ $row->id }})"
                            :row="$row" :block="['Kaprodi', 'Dosen', 'BPM', 'WADIR 1', 'Direktur']" />
                    </x-ui.table.row>
                @empty
                    <x-ui.table.empty :searchValue="$search" :FilterValue="$this->filterValue('prodi')" stateFilter="clearFilter"
                        stateSearch="clearSearch" stateAdd="openCreate" />
                @endforelse
            </x-ui.table.index>
            <x-ui.table.pagination :paginator="$data" />
        </section>
    @endif


    @if ($showCreate)
        <div class="grid grid-cols-6 grid-rows-1 gap-4 mt-2">
            <div class="col-span-2 col-start-3">
                <livewire:master.program-studi.create-update wire:key="create" />
            </div>
        </div>
    @endif

    @if ($showUpdate)
        <div class="grid grid-cols-6 grid-rows-1 gap-4 mt-2">
            <div class="col-span-2 col-start-3">
                <livewire:master.program-studi.create-update wire:key="update-{{ $selectedId }}" :id="$selectedId" />
            </div>
        </div>
    @endif


    <flux:modal name="edit-profile" variant="floating" class="md:w-full" :closable="false" :dismissible="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Update profile</flux:heading>
                <flux:text class="mt-2">Make changes to your personal details.</flux:text>
            </div>
            <div>
                <flux:button variant="primary" wire:click="getDataFromApi">Tarik Data</flux:button>
                <div
                    class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700 mt-3">
                    <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                        <thead
                            class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                            <tr>
                                <th scope="col" class="p-4">Kode Prodi</th>
                                <th scope="col" class="p-4">Nama Prodi</th>
                                <th scope="col" class="p-4">Jenjang</th>
                                <th scope="col" class="p-4">Singkatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                            @foreach ($dataApi as $value)
                                <tr>
                                    <td class="p-4">{{ $value['code'] }}</td>
                                    <td class="p-4">{{ $value['name'] }}</td>
                                    <td class="p-4">{{ $value['jenjang'] }}</td>
                                    <td class="p-4">{{ $value['singkatan'] }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>

            </div>
            <div class="flex gap-3">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="button" wire:click="syncToDatabase" variant="primary">Sync To Database</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
