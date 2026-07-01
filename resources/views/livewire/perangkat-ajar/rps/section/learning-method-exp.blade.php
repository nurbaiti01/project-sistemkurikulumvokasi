<div>
    <x-card class="space-y-4">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                Metode Pembelajaran
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Pendekatan dan metode yang digunakan dalam proses pembelajaran
            </p>
        </div>

        <flux:textarea label="Deskripsi Metode" rows="4"
            placeholder="Contoh: Project-based Learning, Case-based Learning, PBL, blended / hybrid learning..."
            wire:model.defer="form.metode_pembelajaran" />
    </x-card>

    <x-card class="space-y-3">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                Pengalaman Belajar Mahasiswa
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Aktivitas belajar mahasiswa di kelas, laboratorium, lapangan, dan LMS
            </p>
        </div>

        <flux:textarea rows="6" wire:model.defer="form.pengalaman_belajar_mahasiswa"
            placeholder="Pembelajaran dilaksanakan di kelas, laboratorium, observasi lapangan, serta LMS (self-directed learning)..." />
    </x-card>
    <x-card class="space-y-6">
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                Daftar Referensi
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Referensi utama dan pendukung mata kuliah
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- REFERENSI UTAMA --}}
            <div>
                <h4 class="text-sm font-semibold mb-3">Utama</h4>

                <div class="space-y-2">
                    @foreach ($referensi['utama'] as $i => $ref)
                        <div class="flex gap-2 items-center">
                            <flux:input wire:model.defer="referensi.utama.{{ $i }}"
                                placeholder="Referensi utama" />

                            <flux:button icon="x-mark" size="sm" variant="ghost" color="red"
                                wire:click="removeReferensiUtama({{ $i }})" />
                        </div>
                    @endforeach
                </div>

                <flux:button size="xs" icon="plus" variant="primary" class="mt-3"
                    wire:click="addReferensiUtama">
                    Tambah
                </flux:button>
            </div>

            {{-- REFERENSI PENDUKUNG --}}
            <div>
                <h4 class="text-sm font-semibold mb-3">Pendukung</h4>

                <div class="space-y-2">
                    @foreach ($referensi['pendukung'] as $i => $ref)
                        <div class="flex gap-2 items-center">
                            <flux:input wire:model.defer="referensi.pendukung.{{ $i }}"
                                placeholder="Referensi pendukung" />

                            <flux:button icon="x-mark" size="sm" variant="ghost" color="red"
                                wire:click="removeReferensiPendukung({{ $i }})" />
                        </div>
                    @endforeach
                </div>

                <flux:button size="xs" icon="plus" variant="primary" class="mt-3"
                    wire:click="addReferensiPendukung">
                    Tambah
                </flux:button>
            </div>
        </div>
    </x-card>
</div>
