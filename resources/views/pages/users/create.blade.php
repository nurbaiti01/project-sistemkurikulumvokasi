<div>
    <form wire:submit.prevent="store" class="px-4 py-8 flex flex-col gap-4">

        <flux:field>
            <flux:label>User Name</flux:label>
            <flux:input wire:model="name" type="text" />
            <flux:error name="name" />
        </flux:field>

        <flux:field>
            <flux:label>User Email</flux:label>
            <flux:input wire:model="email" type="email" />
            <flux:error name="email" />
        </flux:field>

        <flux:fieldset>
            <flux:legend>User Role</flux:legend>
            <flux:description>Pilih Role Pengguna</flux:description>

            <div class="flex flex-wrap gap-4 *:gap-x-2">
                @foreach ($listRoles as $item)
                    <flux:checkbox wire:model="role" value="{{ $item->id }}" label="{{ $item->name }}"
                        wire:key="role-{{ $item->id }}" />
                @endforeach
            </div>

            <flux:error name="role" />
        </flux:fieldset>

        <flux:field>
            <flux:label>User Password</flux:label>
            <flux:input wire:model="password" type="password" />
            <flux:error name="password" />
        </flux:field>

        <div
            class="flex flex-col-reverse justify-between gap-2 p-4 sm:flex-row sm:items-center md:justify-end">
            <flux:button type="submit" variant="primary">Simpan</flux:button>
        </div>
    </form>
</div>
