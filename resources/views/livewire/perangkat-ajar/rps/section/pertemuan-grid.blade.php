<div>
    @php
        $cpmks = $cpmkList;
    @endphp
    @foreach ($pertemuans as $pIndex => $pertemuan)
        <x-card title="Pertemuan {{ $pIndex + 1 }}">

            {{-- SECTION 1 : FORM UTAMA --}}
            <div class="grid grid-cols-12 gap-4">

                <div class="col-span-2 flex flex-col gap-3">
                    <flux:input label="Pertemuan Ke" type="number"
                        wire:model.defer="pertemuans.{{ $pIndex }}.pertemuan_ke" />

                    <flux:checkbox label="Pemberian Tugas"
                        wire:model.defer="pertemuans.{{ $pIndex }}.pemberian_tugas" />
                </div>

                <div class="col-span-3">
                    <flux:textarea label="Materi Ajar" wire:model.defer="pertemuans.{{ $pIndex }}.materi_ajar" />
                </div>

                <div class="col-span-4">
                    <flux:textarea label="Indikator Penilaian"
                        wire:model.defer="pertemuans.{{ $pIndex }}.indikator" />
                </div>

                <div class="col-span-3">
                    <flux:textarea label="Bentuk Pembelajaran"
                        wire:model.defer="pertemuans.{{ $pIndex }}.bentuk_pembelajaran" />
                </div>

                <div class="col-span-10 col-start-3 row-start-2">
                    <div class="rounded-xl border p-4 bg-base-100 space-y-4">

                        {{-- HEADER --}}
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-semibold">Alokasi Waktu</h4>

                            <span class="text-xs text-gray-500">
                                {{ $this->totalMenitPertemuan($pIndex) }} menit
                            </span>
                        </div>

                        {{-- LIST ALOKASI --}}
                        <div class="space-y-4">
                            @foreach ($pertemuan['alokasi'] as $aIndex => $alokasi)
                                <div class="grid grid-cols-12 gap-3 items-end">

                                    {{-- JENIS --}}
                                    <div class="col-span-4">
                                        <flux:select label="Jenis"
                                            wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.tipe">
                                            @foreach ($jenisAlokasi as $key => $label)
                                                <option value="{{ $key }}">{{ $label }}
                                                </option>
                                            @endforeach
                                        </flux:select>
                                    </div>

                                    {{-- JUMLAH --}}
                                    <div class="col-span-3">
                                        <flux:input type="number" min="1" label="Jumlah"
                                            wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.jumlah" />
                                    </div>

                                    {{-- MENIT --}}
                                    <div class="col-span-3">
                                        <flux:input type="number" min="10" step="10" label="Menit"
                                            wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.menit" />
                                    </div>

                                    {{-- REMOVE --}}
                                    <div class="col-span-2 flex justify-end">
                                        <flux:button icon="x-mark" size="sm" variant="ghost" color="red"
                                            wire:click="removeAlokasi({{ $pIndex }}, {{ $aIndex }})" />
                                    </div>

                                    {{-- FORMAT RPS --}}
                                    <div class="col-span-12 text-xs text-gray-500 font-mono">
                                        {{ $alokasi['tipe'] }} : 1 × {{ $alokasi['jumlah'] }} ×
                                        {{ $alokasi['menit'] }}’
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {{-- ACTION --}}
                        <flux:button icon="plus" size="sm" variant="primary"
                            wire:click="addAlokasi({{ $pIndex }})">
                            Tambah Alokasi
                        </flux:button>

                    </div>
                </div>
            </div>

            {{-- DIVIDER --}}
            <div class="my-6 border-t border-gray-200 dark:border-gray-700"></div>
            <div class="grid grid-cols-12 gap-4">
                <div class="col-span-2">
                    <flux:radio.group label="Pilih CPMK Terkait" wire:model="pertemuans.{{ $pIndex }}.cpmk_id">
                        <div class="grid grid-cols-1 md:grid-cols-1 gap-3">
                            @foreach ($cpmks as $cpmk)
                                <flux:radio value="{{ $cpmk->id }}" label="{{ $cpmk->code }}" />
                            @endforeach
                        </div>
                    </flux:radio.group>
                </div>
                <div class="col-span-10">
                    <div class="mb-5 py-2">
                        @foreach ($pertemuan['bobots'] as $bIndex => $bobot)
                            <div class="flex items-end gap-3 py-2">
                                <div class="flex-1">
                                    <flux:input label="Jenis Bobot"
                                        wire:model.defer="pertemuans.{{ $pIndex }}.bobots.{{ $bIndex }}.jenis" />
                                </div>

                                <div class="w-32">
                                    <flux:input label="Bobot" type="number"
                                        wire:model.live.debounce.500ms="pertemuans.{{ $pIndex }}.bobots.{{ $bIndex }}.bobot" />
                                </div>

                                <flux:button icon="x-mark" size="sm" variant="ghost" color="red"
                                    wire:click="removeBobot({{ $pIndex }}, {{ $bIndex }})" />
                            </div>
                        @endforeach
                    </div>
                    <div class="text-sm mb-2">
                        Total Bobot Pertemuan:
                        <span class="font-semibold">
                            {{ $this->totalBobotPertemuan($pIndex) }}
                        </span>
                    </div>
                    <flux:button icon="plus" variant="primary" wire:click="addBobot({{ $pIndex }})">
                        Tambah Bobot
                    </flux:button>
                </div>
            </div>
            <div class="my-6 border-t border-gray-200 dark:border-gray-700"></div>
            <div class="grid grid-cols-1 gap-4">
                @php
                    $jenis = data_get(
                        $pertemuans,
                        $pIndex . '.bobots.' . ($pertemuans[$pIndex]['selected_bobot_index'] ?? '') . '.jenis',
                    );
                @endphp
                <flux:heading>Rancangan Penilaian ({{ $jenis ?? '' }}) : Pertemuan {{ $pIndex + 1 }}
                </flux:heading>
                <div class="grid grid-cols-3 gap-4 mt-3">
                    <flux:select label="Jenis" wire:model.live="pertemuans.{{ $pIndex }}.selected_bobot_index">

                        <flux:select.option value="">Pilih Jenis</flux:select.option>

                        @foreach ($pertemuan['bobots'] as $bIndex => $bobot)
                            <flux:select.option value="{{ $bIndex }}">
                                {{ $bobot['jenis'] }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:input label="Bentuk" placeholder=""
                        wire:model="pertemuans.{{ $pIndex }}.rancangan_penilaian.bentuk" />

                    <flux:input label="Bobot" type="number" readonly
                        wire:model="pertemuans.{{ $pIndex }}.rancangan_penilaian.bobot" />
                    <div class="col-span-3">
                        <flux:textarea label="Topik" rows="2"
                            wire:model="pertemuans.{{ $pIndex }}.rancangan_penilaian.topik" />
                    </div>
                </div>
            </div>
            {{-- FOOTER ACTION --}}
            <div class="flex justify-between mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <flux:button icon="plus" wire:click="addPertemuan">
                    Tambah
                </flux:button>

                <flux:button icon="trash" color="red" variant="ghost"
                    wire:click="removePertemuan({{ $pIndex }})">
                    Hapus
                </flux:button>
            </div>

        </x-card>
    @endforeach
    <table class="w-full text-sm border">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th class="p-2 border">CPMK</th>
                <th class="p-2 border text-right">Total Bobot</th>
            </tr>
        </thead>
        <tbody wire:poll.500ms="totalBobotPerCpmk">
            @foreach ($this->totalBobotPerCpmk() as $cpmkId => $total)
                <tr>
                    <td class="p-2 border font-medium">
                        {{ $this->getCpmkCode($cpmkId) }}
                    </td>
                    <td
                        class="p-2 border text-right font-semibold
                        {{ $total > 100 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $total }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
