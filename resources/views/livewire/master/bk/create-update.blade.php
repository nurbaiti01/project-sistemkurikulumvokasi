<div>
    <x-card>
        <x-card.header>
            <x-card.title class="dark:text-white">Form Capaian Pembelajaran Lulusan</x-card.title>
        </x-card.header>
        <x-card.content>
            <form wire:submit.prevent="save">
                <div class="flex flex-col gap-3">
                    @if (!$isKaprodi)
                        <flux:field>
                            <flux:label>Program Studi</flux:label>
                            <flux:select wire:model="form.programStudis">
                                <flux:select.option value="">Pilih Program Studi</flux:select.option>
                                @foreach ($this->getProdiProperty() as $pd)
                                    <flux:select.option value="{{ $pd->id }}">
                                        {{ $pd->jenjang }}-{{ $pd->name }}
                                    </flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="form.programStudis" />
                        </flux:field>
                    @endif
                    <flux:field>
                        <flux:label>Kode BK</flux:label>
                        <flux:input wire:model="form.code" type="text" />
                        <flux:error name="form.code" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Nama Bahan Kajian</flux:label>
                        <flux:input wire:model="form.name" type="text" />
                        <flux:error name="form.name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Deskripsi</flux:label>
                        <flux:textarea wire:model="form.description" placeholder="No lettuce, tomato, or onion..." />
                        <flux:error name="form.description" />
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
