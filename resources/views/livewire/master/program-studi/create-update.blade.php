<div>
    <x-card>
        <x-card.header>
            <x-card.title class="dark:text-white">Form Capaian Pembelajaran Lulusan</x-card.title>
        </x-card.header>
        <x-card.content>
            <form wire:submit.prevent="save">
                <div class="flex flex-col gap-3">
                    <flux:field>
                        <flux:label>Kode Prodi</flux:label>
                        <flux:input wire:model="form.code" type="text" />
                        <flux:error name="form.code" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Nama Prodi</flux:label>
                        <flux:input wire:model.live.debounce.500ms="form.name" type="text" />
                        <flux:error name="form.name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Jenjang</flux:label>
                        <flux:select wire:model="form.jenjang">
                            <flux:select.option value="">Pilih Jenjang</flux:select.option>
                            @foreach ($listJenjang as $key=>$pd)
                                <flux:select.option value="{{ $key }}">{{ $pd }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                        <flux:error name="form.jenjang" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Singkatan</flux:label>
                        <flux:input wire:model="form.singkatan" type="text" />
                        <flux:error singkatan="form.name" />
                    </flux:field>

                </div>

                <div class="flex flex-row justify-end mt-5 gap-3">
                    <flux:button type="button" wire:click="cancel">Batal</flux:button>
                    <flux:button type="submit" variant="primary">Simpan</flux:button>
                </div>
            </form>
        </x-card.content>

    </x-card>

</div>
