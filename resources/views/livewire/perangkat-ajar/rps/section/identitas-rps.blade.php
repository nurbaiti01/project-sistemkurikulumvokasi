<x-card title="Identitas RPS">
    <div class="grid grid-cols-2 gap-10">
        <div class="space-y-4 text-sm">
            <!-- Mata Kuliah -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Mata Kuliah
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:select wire:model.change="form.matakuliah_id" class="w-full">
                        <flux:select.option value="">Pilih Matakuliah</flux:select.option>
                        @foreach ($listMk as $mk)
                            <flux:select.option value="{{ $mk->id }}">
                                {{ $mk->name }}
                            </flux:select.option>
                        @endforeach
                    </flux:select>
                </div>
            </div>
            <!-- Kode Matakuliah -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Kode Matakuliah
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <span class="font-semibold text-neutral-800 dark:text-neutral-100">
                        {{ optional($indentitasMk)->code ?? '-' }}
                    </span>
                </div>
            </div>
            <!-- Jumlah SKS -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Jumlah SKS
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <span class="font-semibold text-neutral-800 dark:text-neutral-100">
                        {{ optional($indentitasMk)->sks ?? '-' }}
                    </span>
                </div>
            </div>
            <!-- Semester -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Semester
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <span class="font-semibold text-neutral-800 dark:text-neutral-100">
                        {{ optional($indentitasMk)->semester ?? '-' }}
                    </span>
                </div>
            </div>
            <!-- Deskripsi -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Deskripsi Matakuliah
                </div>
                <div class="md:col-span-9 flex gap-2">
                    <span class="hidden md:block text-neutral-400 mt-1">:</span>
                    <p class="text-neutral-700 dark:text-neutral-200 leading-relaxed text-justify">
                        {{ optional($indentitasMk)->description ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="space-y-4 text-sm">
            <!-- Kurikulum -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Program Studi
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:input wire:model="activeProdiName" type="text" disabled />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Kelas
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:input wire:model="form.kelas" type="text" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Dosen Pengampu
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:input wire:model="activeDosenName" type="text" disabled />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Tahun Akademik
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:input wire:model="form.tahun_akademik" type="text" />
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-12 gap-2 items-center">
                <div class="md:col-span-3 font-medium text-neutral-600 dark:text-neutral-300">
                    Revisi
                </div>
                <div class="md:col-span-9 flex gap-2 items-center">
                    <span class="hidden md:block text-neutral-400">:</span>
                    <flux:input wire:model="form.revisi" type="text" />
                </div>
            </div>
        </div>
    </div>
</x-card>
