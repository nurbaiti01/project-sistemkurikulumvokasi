<div class="flex flex-col gap-2">
    @if ($errors->any())
        <flux:modal name="errorForm" open>
            <div
                class="rounded-xl border border-red-200 dark:border-red-800
                   bg-white dark:bg-gray-900 shadow-lg max-w-lg mx-auto">

                {{-- HEADER --}}
                <div
                    class="flex items-center gap-3 px-5 py-4 border-b
                       border-red-100 dark:border-red-800">
                    <div
                        class="flex h-9 w-9 items-center justify-center rounded-full
                           bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300">
                        <!-- icon -->
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M5.07 19h13.86
                                 c1.54 0 2.5-1.67 1.73-3L13.73 4
                                 c-.77-1.33-2.69-1.33-3.46 0L3.34 16
                                 c-.77 1.33.19 3 1.73 3z" />
                        </svg>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-red-700 dark:text-red-300">
                            Data RPS Belum Lengkap
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">
                            Periksa kembali isian berikut sebelum menyimpan
                        </p>
                    </div>
                </div>

                {{-- BODY --}}
                <div class="px-5 py-4 max-h-72 overflow-y-auto">
                    <ul class="space-y-2 text-sm">
                        @foreach ($errors->all() as $error)
                            <li class="flex items-start gap-2 text-gray-700 dark:text-gray-300">
                                <span
                                    class="mt-1 h-1.5 w-1.5 rounded-full
                                       bg-red-500 dark:bg-red-400"></span>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- FOOTER --}}
                <div
                    class="flex justify-end gap-2 px-5 py-3 border-t
                       border-gray-100 dark:border-gray-800">
                    <flux:modal.close>
                        <flux:button variant="ghost">Close</flux:button>
                    </flux:modal.close>
                </div>
            </div>
        </flux:modal>
    @endif

    <livewire:perangkat-ajar.rps.section.identitas-rps />
    @if ($matakuliahId)
        <livewire:perangkat-ajar.rps.section.cpl-cpmk :matakuliahId="$matakuliahId" :programStudiId="$programStudiId" :kurikulumId="$kurikulumId"
            wire:key="cpl-cpmk-{{ $matakuliahId }}-{{ $kurikulumId }}" />
        <x-card class="" title="Kaitan CPMK dengan Materi dan Bentuk Pembelajaran, serta Alokasi Waktu">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-sm font-medium text-gray-700 dark:text-gray-200">
                        Tampilan Data
                    </h3>
                    <p class="text-xs text-gray-500">
                        Pilih mode tampilan pengisian RPS
                    </p>
                </div>

                <flux:button.group>
                    <flux:button icon="squares-2x2" size="sm" :variant="!$viewTable ? 'primary' : 'ghost'"
                        wire:click="$set('viewTable', false)">
                        Grid
                    </flux:button>

                    <flux:button icon="table-cells" size="sm" :variant="$viewTable ? 'primary' : 'ghost'"
                        wire:click="$set('viewTable', true)">
                        Tabel
                    </flux:button>
                </flux:button.group>
            </div>
            @if (!$viewTable)
                <livewire:perangkat-ajar.rps.section.pertemuan-grid :cpmkList="$cpmkList"
                    wire:key="pertemuan-grid-{{ md5(json_encode($cpmkList)) }}" />
            @else
                <livewire:perangkat-ajar.rps.section.pertemuan-table :cpmkList="$cpmkList"
                    wire:key="pertemuan-table-{{ md5(json_encode($cpmkList)) }}" />
            @endif
        </x-card>
        <livewire:perangkat-ajar.rps.section.rekap-bobot :totalMenitSemester="$totalMenitSemester" :totalJamSemester="$totalJamSemester"
            wire:key="rerkap-bobot-{{ md5(json_encode($totalMenitSemester)) }}" />

        <livewire:perangkat-ajar.rps.section.learning-method-exp
            wire:key="learning-method-exp-{{ md5(json_encode($totalMenitSemester)) }}" />

        <livewire:perangkat-ajar.rps.section.penilaian :cpmkList="$cpmkList"
            wire:key="penilaian-{{ md5(json_encode($cpmkList)) }}" />
        <flux:button variant="primary" wire:click="save">save</flux:button>
    @endif
</div>
