<div>
    @php
        $cpmks = $cpmkList;
    @endphp
    <table class="w-full border border-gray-200 dark:border-gray-700 text-sm">
        <thead class="bg-gray-100 dark:bg-gray-800">
            <tr>
                <th class="p-2 border">Pertemuan</th>
                <th class="p-2 border">Materi Ajar</th>
                <th class="p-2 border">Indikator</th>
                <th class="p-2 border">Bentuk Pembelajaran</th>
                <th class="p-2 border">Alokasi Waktu</th>
                <th class="p-2 border">CPMK</th>
                <th class="p-2 border">Bobot CPMK</th>
                <th class="p-2 border">Aksi</th>
            </tr>
        </thead>
        @foreach ($pertemuans as $pIndex => $pertemuan)
            <tbody x-data="{ open: @entangle('pertemuans.' . $pIndex . '.show') }">

                <tr x-show="!open" x-transition.opacity.duration.300ms x-cloak
                    wire:key="pertemuan-{{ $pIndex }}-header">
                    <td class="border p-2" colspan="8">
                        {{ $pertemuan['pertemuan_ke'] }}
                        <flux:button icon="eye" size="xs" variant="ghost" @click="open = true">
                            Show Detail
                        </flux:button>
                    </td>
                </tr>
                <tr x-show="open" x-transition.opacity.scale.origin.top.duration.300ms x-cloak class="align-top"
                    wire:key="pertemuan-{{ $pIndex }}-rows">
                    {{-- Pertemuan --}}
                    <td class="p-2 border-t border-r border-l w-10">
                        {{ $pertemuan['pertemuan_ke'] }}
                        <flux:input type="number" wire:model.defer="pertemuans.{{ $pIndex }}.pertemuan_ke"
                            size="sm" hidden />
                    </td>

                    {{-- Materi --}}
                    <td class="p-2 border w-64">
                        <flux:textarea rows="4" wire:model.defer="pertemuans.{{ $pIndex }}.materi_ajar" />
                    </td>

                    {{-- Indikator --}}
                    <td class="p-2 border w-64">
                        <flux:textarea rows="4" wire:model.defer="pertemuans.{{ $pIndex }}.indikator" />
                    </td>

                    {{-- Bentuk Pembelajaran --}}
                    <td class="p-2 border w-64">
                        <flux:textarea rows="4"
                            wire:model.defer="pertemuans.{{ $pIndex }}.bentuk_pembelajaran" />
                    </td>

                    {{-- Alokasi Waktu --}}
                    <td class="p-3 border w-72 align-top">
                        <div class="space-y-2 text-sm">

                            {{-- LIST ALOKASI --}}
                            @foreach ($pertemuan['alokasi'] as $aIndex => $alokasi)
                                <div class="flex flex-col items-center gap-2">

                                    {{-- TIPE --}}
                                    <flux:select size="xs" class="w-20"
                                        wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.tipe">
                                        @foreach ($jenisAlokasi as $key => $label)
                                            <option value="{{ $key }}">{{ $key }}
                                            </option>
                                        @endforeach
                                    </flux:select>
                                    <div class="flex items-center gap-2">
                                        {{-- JUMLAH --}}
                                        <flux:input type="number" min="1" size="xs" class="w-14"
                                            wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.jumlah" />

                                        <span class="text-xs text-gray-400">×</span>

                                        {{-- MENIT --}}
                                        <flux:input type="number" min="10" step="10" size="xs"
                                            class="w-16"
                                            wire:model.live="pertemuans.{{ $pIndex }}.alokasi.{{ $aIndex }}.menit" />

                                        <span class="text-xs text-gray-400">m</span>
                                        <flux:button icon="x-mark" size="xs" variant="ghost" color="red"
                                            wire:click="removeAlokasi({{ $pIndex }}, {{ $aIndex }})" />
                                    </div>
                                    {{-- REMOVE --}}

                                </div>

                                {{-- FORMAT RPS --}}
                                <div class="col-span-12 text-xs text-gray-500 font-mono">
                                    {{ $alokasi['tipe'] }} : 1 × {{ $alokasi['jumlah'] }} ×
                                    {{ $alokasi['menit'] }}’
                                </div>
                            @endforeach
                            <flux:checkbox class="mt-2" label="Tugas"
                                wire:model.defer="pertemuans.{{ $pIndex }}.pemberian_tugas" />
                            {{-- ACTION --}}
                            <flux:button icon="plus" size="xs" variant="primary"
                                wire:click="addAlokasi({{ $pIndex }})">
                                Tambah
                            </flux:button>

                            {{-- TOTAL --}}
                            <div class="pt-2 mt-2 border-t text-xs text-gray-600 dark:text-white">
                                Total:
                                <b>{{ $this->totalMenitPertemuan($pIndex) }}</b> menit
                            </div>

                        </div>
                    </td>

                    {{-- CPMK --}}
                    <td class="p-2 border w-40">
                        <flux:radio.group wire:model.defer="pertemuans.{{ $pIndex }}.cpmk_id">
                            <div class="flex flex-col gap-1">
                                @foreach ($cpmks as $cpmk)
                                    <flux:radio value="{{ $cpmk->id }}" label="{{ $cpmk->code }}" />
                                @endforeach
                            </div>
                        </flux:radio.group>
                    </td>

                    {{-- Bobot CPMK --}}
                    <td class="p-2 border min-w-[320px]">
                        <table class="w-full text-xs border">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-900">
                                    <th class="p-1 border">Jenis</th>
                                    <th class="p-1 border w-20">Bobot</th>
                                    <th class="p-1 border w-8"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pertemuan['bobots'] as $bIndex => $bobot)
                                    <tr>
                                        <td class="p-1 border" width="20%">
                                            <flux:input
                                                wire:model.defer="pertemuans.{{ $pIndex }}.bobots.{{ $bIndex }}.jenis"
                                                size="xs" />
                                        </td>
                                        <td class="p-1 border" width="10%">
                                            <flux:input type="number"
                                                wire:model.live.debounce.500ms="pertemuans.{{ $pIndex }}.bobots.{{ $bIndex }}.bobot"
                                                size="xs" />
                                        </td>
                                        <td class="p-1 border text-center" width="5%">
                                            <flux:button icon="x-mark" size="xs" variant="ghost" color="red"
                                                wire:click="removeBobot({{ $pIndex }}, {{ $bIndex }})" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-sm mt-2">
                            Total Bobot Pertemuan:
                            <span class="font-semibold">
                                {{ $this->totalBobotPertemuan($pIndex) }}
                            </span>
                        </div>
                        <flux:button class="mt-2" icon="plus" size="xs" variant="primary"
                            wire:click="addBobot({{ $pIndex }})">
                            Tambah
                        </flux:button>
                    </td>

                    {{-- Aksi --}}
                    <td class="p-2 border w-32">
                        <div class="flex flex-col gap-2">
                            <flux:button icon="document-duplicate" size="xs" wire:click="addPertemuan">
                                Tambah
                            </flux:button>

                            <flux:button icon="trash" size="xs" color="red" variant="ghost"
                                wire:click="removePertemuan({{ $pIndex }})">
                                Hapus
                            </flux:button>
                            <flux:button icon="eye-slash" size="xs" variant="ghost" @click="open = false">
                                Hide Baris Ini
                            </flux:button>
                        </div>
                    </td>
                </tr>
                <tr x-show="open" x-transition.opacity.duration.300ms x-cloak
                    wire:key="pertemuan-{{ $pIndex }}-rancangan">
                    <td class="border-b border-r border-l p-2"></td>
                    <td class="border p-2" colspan="7">
                        @php
                            $jenis = data_get(
                                $pertemuans,
                                $pIndex . '.bobots.' . ($pertemuans[$pIndex]['selected_bobot_index'] ?? '') . '.jenis',
                            );
                        @endphp
                        <flux:heading>Rancangan Penilaian ({{ $jenis ?? '' }}) : Pertemuan
                            {{ $pIndex + 1 }} </flux:heading>
                        <div class="grid grid-cols-3 gap-4 mt-3">
                            <flux:select label="Jenis"
                                wire:model.live="pertemuans.{{ $pIndex }}.selected_bobot_index">

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
                    </td>
                </tr>
            </tbody>
        @endforeach

    </table>
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
