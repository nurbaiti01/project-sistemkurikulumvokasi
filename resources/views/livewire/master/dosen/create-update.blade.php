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
                        <flux:label>NRP</flux:label>
                        <flux:input wire:model="form.nrp" type="number" />
                        <flux:error name="form.nrp" />
                    </flux:field>
                    <flux:field>
                        <flux:label>NIDN</flux:label>
                        <flux:input wire:model="form.nidn" type="number" />
                        <flux:error name="form.nidn" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Nama Lengkap</flux:label>
                        <flux:input wire:model="form.name" type="text" />
                        <flux:error name="form.name" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Email</flux:label>
                        <flux:input wire:model="form.email" type="email" />
                        <flux:error name="form.email" />
                    </flux:field>
                    <flux:field>
                        <flux:label>Jenis Kelamin</flux:label>
                        <flux:radio.group wire:model="form.gender" variant="segmented">
                            <flux:radio label="Laki-laki" value="Laki-laki" />
                            <flux:radio label="Perempuan" value="Perempuan" />
                        </flux:radio.group>
                        <flux:error name="form.gender" />
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
