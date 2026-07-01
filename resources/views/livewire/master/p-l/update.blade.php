<div>
    <x-card>
        <x-card.header>
            <x-card.title class="dark:text-white">Form Tambah Profile Lulusan</x-card.title>
        </x-card.header>
        <x-card.content>
            <form wire:submit.prevent="save">
                <div class="flex flex-col gap-3">
                    @if (!$isKaprodi)
                        <flux:field>
                            <flux:label>Program Studi</flux:label>
                            <flux:select wire:model="prodi_id" placeholder="Pilih Program Studi">
                                @foreach ($this->getProdiProperty() as $pd)
                                    <flux:select.option value="{{ $pd->id }}">
                                        {{ $pd->jenjang }}-{{ $pd->name }}</flux:select.option>
                                @endforeach
                            </flux:select>
                            <flux:error name="prodi_id" />
                        </flux:field>
                    @endif
                    <flux:field>
                        <flux:label>Kode Profile Lulusan</flux:label>
                        <flux:input wire:model="code" type="text" />
                        <flux:error name="code" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Nama Profile Lulusan</flux:label>
                        <flux:input wire:model="name" type="text" />
                        <flux:error name="name" />
                    </flux:field>

                    <flux:field>
                        <flux:label>Deskripsi</flux:label>
                        <flux:textarea wire:model="description" placeholder="No lettuce, tomato, or onion..." />
                        <flux:error name="description" />
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
