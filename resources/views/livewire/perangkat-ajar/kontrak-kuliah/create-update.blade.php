<form wire:submit.prevent="save" class="flex flex-col gap-2">
    <x-card title="Form Pembuatan Kontrak Kuliah">
        <div class="p-3 flex flex-col gap-4">
            <div class="flex gap-3">
                <flux:label class="w-1/8">Mata Kuliah</flux:label>
                <div class="w-full">
                    <flux:select wire:model.change="matakuliahId">
                        <flux:select.option value="">Pilih Matakuliah</flux:select.option>
                        @foreach ($listMK as $mk)
                            <flux:select.option value="{{ $mk['id'] }}">{{ $mk['label'] }}</flux:select.option>
                        @endforeach
                    </flux:select>
                    <flux:error name="matakuliahId" />
                </div>

            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Nama Matakuliah</flux:label>
                <flux:input readonly variant="filled" wire:model="detailMk.nama_mk" />
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Kode MK</flux:label>
                <flux:input readonly variant="filled" wire:model="detailMk.kode_mk" />
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Bobot SKS</flux:label>
                <flux:input readonly variant="filled" wire:model="detailMk.bobot_sks" />
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Program Studi</flux:label>
                <flux:input readonly variant="filled" wire:model="detailMk.program_studi" />
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Semester/kelas</flux:label>
                <flux:input.group class="gap-3">
                    <flux:input readonly wire:model="detailMk.semester" placeholder="Semester" />
                    <div class="w-full">
                        <flux:input wire:model="kelas" placeholder="Nama Kelas" />
                        <flux:error name="kelas" />
                    </div>

                </flux:input.group>
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Total Jam Pelajaran</flux:label>
                <div class="w-full">
                    <flux:input variant="filled" wire:model="totalJam" />
                    <flux:error name="totalJam" />
                </div>

            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Dosen Pengampu</flux:label>
                <flux:input readonly variant="filled" wire:model="detailDosen" />
            </div>
            <div class="flex gap-3">
                <flux:label class="w-1/8">Tahun Akademik</flux:label>
                <flux:input variant="filled" wire:model="tahun_akademik" />
                <flux:error name="tahun_akademik" />
            </div>
        </div>
    </x-card>
    <x-card title="Detail Kontrak Kuliah">
        <div class="flex flex-col gap-10">
            <div class="flex flex-col gap-2">
                <flux:label class="w-1/8">Deskripsi Matakuliah</flux:label>
                <x-ui.forms.text-editor model="deskripsiMk" wire:model="deskripsiMk" wire:key="deskripsiMk" disabled />

            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="w-1/8">Tujuan Pembelajaran</flux:label>
                <x-ui.forms.text-editor model="tujuan_pembelajaran" wire:model="tujuan_pembelajaran"
                    wire:key="tujuan_pembelajaran" />
                <flux:error name="tujuan_pembelajaran" />
            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="">Capaian Pembelajaran Matakuliah</flux:label>
                <x-ui.forms.text-editor model="mk_cpmk" wire:model="mk_cpmk" wire:key="mk_cpmk" disabled />
            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="">Strategi Perkuliahan</flux:label>
                <x-ui.forms.text-editor model="strategi_perkuliahan" wire:model="strategi_perkuliahan"
                    wire:key="strategi_perkuliahan" />
                <flux:error name="strategi_perkuliahan" />

            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="">Organisasi Materi</flux:label>
                <x-ui.forms.text-editor model="materi_pembelajaran" wire:model="materi_pembelajaran"
                    wire:key="materi_pembelajaran" />
                <flux:error name="materi_pembelajaran" />

            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="">Kriteria dan Standar Penilaian</flux:label>
                <x-ui.forms.text-editor model="kriteria_penilaian" wire:model="kriteria_penilaian"
                    wire:key="kriteria_penilaian" />
                <flux:error name="kriteria_penilaian" />

            </div>
            <div class="flex flex-col gap-2">
                <flux:label class="">Tata Tertib Perkuliahan</flux:label>
                <x-ui.forms.text-editor model="tata_tertib" wire:model="tata_tertib" wire:key="tata_tertib" />
                <flux:error name="tata_tertib" />
            </div>
        </div>
        <x-slot name="footer" class="flex items-center justify-between">
            <x-link label="Batal" href="{{ route('perangkat-ajar.kontrak-kuliah.index') }}" secondary wire:navigate />

            <x-button label="Simpan" type="submit" primary />
        </x-slot>
    </x-card>

</form>
