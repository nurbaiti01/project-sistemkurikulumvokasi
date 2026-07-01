        <thead>
            <tr class="bg-gray-50 dark:bg-gray-900">
                <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top" colspan="7">
                    @php
                        $grids = (!$isEdit && !$isView ? 3 : 2);
                        $disabled = !$isEdit && !$isView;
                    @endphp
                    <div class="grid grid-cols-{{ $grids }} gap-4">
                        @if (!$isEdit && !$isView)
                            <div x-data="{
                                open: false,
                                search: '',
                                selectedId: @entangle('form.matakuliah_id'),
                                options: @js($listMk),
                                get filtered() {
                                    return this.options.filter(o =>
                                        o.name.toLowerCase().includes(this.search.toLowerCase())
                                    )
                                }
                            }" class="relative w-full" @click.outside="open = false">
                                <!-- Trigger -->
                                <button type="button" @click="open = !open"
                                    class="w-full flex justify-between items-center
                   px-3 py-2 rounded-md border
                   border-gray-300 dark:border-gray-600
                   bg-white dark:bg-gray-800
                   text-sm text-gray-700 dark:text-gray-200
                   focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <span
                                        x-text="options.find(o => o.id == selectedId)?.name || 'Pilih Matakuliah'"></span>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown -->
                                <div x-show="open" x-transition
                                    class="absolute z-50 mt-1 w-full
                   rounded-md shadow-lg
                   bg-white dark:bg-gray-800
                   border border-gray-200 dark:border-gray-700">
                                    <!-- Search -->
                                    <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                        <input type="text" x-model="search" placeholder="Cari matakuliah..."
                                            class="w-full px-2 py-1 text-sm rounded-md
                           border border-gray-300 dark:border-gray-600
                           bg-white dark:bg-gray-900
                           text-gray-700 dark:text-gray-200
                           focus:outline-none focus:ring-1 focus:ring-indigo-500" />
                                    </div>

                                    <!-- Options -->
                                    <ul class="max-h-48 overflow-y-auto text-sm">
                                        <template x-for="item in filtered" :key="item.id">
                                            <li>
                                                <button type="button"
                                                    @click="$wire.set('form.matakuliah_id', item.id);
                                selectedId = item.id;
                                open = false;
                                search = '';
                            "
                                                    class="w-full text-left px-3 py-2
                                   hover:bg-indigo-50 dark:hover:bg-gray-700
                                   text-gray-700 dark:text-gray-200">
                                                    <span x-text="item.name"></span>
                                                </button>
                                            </li>
                                        </template>

                                        <li x-show="filtered.length === 0"
                                            class="px-3 py-2 text-gray-500 dark:text-gray-400">
                                            Tidak ada data
                                        </li>
                                    </ul>
                                </div>
                                <flux:error name="form.matakuliah_id" />
                            </div>
                        @endif
                        <flux:field>
                            <flux:input.group>
                                <flux:input.group.prefix>Kelas</flux:input.group.prefix>
                                <flux:input wire:model.defer="form.kelas" :disabled="$isView"/>
                            </flux:input.group>
                            <flux:error name="form.kelas" />
                        </flux:field>
                        <flux:field>
                            <flux:input.group>
                                <flux:input.group.prefix>T.A</flux:input.group.prefix>
                                <flux:input wire:model.live="form.tahun_akademik" :disabled="$isView"/>
                            </flux:input.group>
                            <flux:error name="form.tahun_akademik" />

                        </flux:field>
                    </div>
                </td>

            </tr>
            <tr class="bg-gray-50 dark:bg-gray-900">
                {{-- Program Studi --}}
                <th scope="col" colspan="2"
                    class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Program Studi
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $indentitasMk['program_studi_name'] ?? '-' }}
                        </p>
                    </div>
                </th>
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Dosen Pengampu
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $indentitasMk['dosen_pengampu'] ?? '-' }}
                        </p>
                    </div>
                </th>

                {{-- Matakuliah --}}
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Matakuliah
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $indentitasMk['matakuliah_code'] }} - {{ $indentitasMk['matakuliah_name'] }}
                        </p>
                    </div>
                </th>

                {{-- Jumlah SKS --}}
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Jumlah SKS
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $indentitasMk['matakuliah_sks'] ?? '-' }}
                        </p>
                    </div>
                </th>

                {{-- Semester --}}
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Semester
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $indentitasMk['matakuliah_semester'] ?? '-' }}
                        </p>
                    </div>
                </th>

                {{-- Dosen Pengampu --}}


                {{-- Tahun Akademik --}}
                <th scope="col" class="p-4 w-10 border border-gray-200 dark:border-gray-700 text-left align-top">
                    <div class="space-y-1">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            T.A
                        </p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $form['tahun_akademik'] ?? '-' }}
                        </p>
                    </div>
                </th>
            </tr>

            <tr class="bg-gray-50 dark:bg-gray-900">
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle"
                    colspan="2">Tujuan Intruksional</th>
                <th scope="col" class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle"
                    colspan="5">
                    <flux:textarea wire:model.defer="form.tujuan_instruksional_umum" :disabled="$isView"/>
                </th>
            </tr>

        </thead>
