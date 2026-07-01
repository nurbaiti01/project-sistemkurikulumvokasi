<tfoot>
    <tr class="bg-gray-50 dark:bg-gray-900">
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="2">Metode
            Pengajaran</td>
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="5">
            <flux:fieldset>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Kuliah --}}
                    <flux:input.group>
                        <flux:input.group.prefix>
                            <span class="text-sm font-medium">Kuliah</span>
                        </flux:input.group.prefix>

                        <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                            wire:model.defer="form.metode.kuliah.jam" :disabled="$isView" />

                        <flux:input.group.suffix>
                            Jam
                        </flux:input.group.suffix>
                    </flux:input.group>

                    {{-- Tutorial --}}
                    <flux:input.group>
                        <flux:input.group.prefix>
                            <span class="text-sm font-medium">Tutorial</span>
                        </flux:input.group.prefix>

                        <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                            wire:model.defer="form.metode.tutorial.jam" :disabled="$isView" />

                        <flux:input.group.suffix>
                            Jam
                        </flux:input.group.suffix>
                    </flux:input.group>

                    {{-- Laboratorium --}}
                    <flux:input.group>
                        <flux:input.group.prefix>
                            <span class="text-sm font-medium">Laboratorium</span>
                        </flux:input.group.prefix>

                        <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                            wire:model.defer="form.metode.laboratorium.jam" :disabled="$isView" />

                        <flux:input.group.suffix>
                            Jam
                        </flux:input.group.suffix>
                    </flux:input.group>
                </div>
            </flux:fieldset>

        </td>
    </tr>
    <tr class="bg-gray-50 dark:bg-gray-900">
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="2">Metode
            Evaluasi</td>
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="5">
            <div class="flex justify-between gap-3">
                <flux:input.group>
                    <flux:input.group.prefix>
                        Tugas-tugas
                    </flux:input.group.prefix>
                    <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                        wire:model.defer="form.evaluasi.tugas_persen" :disabled="$isView" />
                    <flux:input.group.suffix>
                        %
                    </flux:input.group.suffix>
                </flux:input.group>
                <flux:input.group>
                    <flux:input.group.prefix>
                        Test Singkat (Quiz)
                    </flux:input.group.prefix>
                    <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                        wire:model.defer="form.evaluasi.kuis_persen" :disabled="$isView" />
                    <flux:input.group.suffix>
                        %
                    </flux:input.group.suffix>
                </flux:input.group>
                <flux:input.group>
                    <flux:input.group.prefix>
                        Ujian
                    </flux:input.group.prefix>
                    <flux:input type="number" min="0" placeholder="0" class="w-24 text-right"
                        wire:model.defer="form.evaluasi.ujian_persen" :disabled="$isView" />
                    <flux:input.group.suffix>
                        %
                    </flux:input.group.suffix>
                </flux:input.group>
            </div>
        </td>
    </tr>
    <tr class="bg-gray-50 dark:bg-gray-900">
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="2">Diktat/Modul
        </td>
        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="5">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                <thead class="">
                    <tr>
                        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Jenis</td>
                        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Judul</td>
                        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Penerbit</td>
                        @if (!$isView)
                            <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle"></td>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($referensi as $index => $item)
                        <tr wire:key="referensi-{{ $index }}">
                            <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                                <flux:select wire:model="referensi.{{ $index }}.jenis" :disabled="$isView">
                                    <flux:select.option value="">Pilih</flux:select.option>
                                    @foreach ($lisJenisReferensi as $ref)
                                        <flux:select.option value="{{ $ref }}">{{ $ref }}
                                        </flux:select.option>
                                    @endforeach
                                    <!-- ... -->
                                </flux:select>
                            </td>
                            <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                                <flux:input wire:model="referensi.{{ $index }}.judul" :disabled="$isView" />
                            </td>
                            <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                                <flux:input placeholder="Post title" wire:model="referensi.{{ $index }}.penerbit"
                                    :disabled="$isView" />
                            </td>
                            @if (!$isView)
                                <td class="p-4 border w-10 border-gray-200 dark:border-gray-700 text-left align-middle">
                                    <div class="flex flex-col gap-3">
                                        <flux:button icon="plus" size="xs" wire:click="addReferensi">Tambah
                                        </flux:button>
                                        <flux:button icon="x-mark" size="xs"
                                            wire:click="removeReferensi({{ $index }})">Hapus </flux:button>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach

                </tbody>
            </table>
        </td>
    </tr>
</tfoot>
