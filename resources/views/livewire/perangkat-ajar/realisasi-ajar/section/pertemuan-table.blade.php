        <tbody>
            <tr class="bg-gray-50 dark:bg-gray-900">
                <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Pertemuan</td>
                <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Tanggal</td>
                <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="2">Pokok
                    Pembahasan</td>
                <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Jam</td>
                <td colspan="{{ $isView ? 2 : 0 }}"
                    class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">Paraf</td>
                @if (!$isView)
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle"></td>
                @endif
            </tr>
            @foreach ($pertemuans as $pIndex => $pertemuan)
                <tr class="bg-gray-50 dark:bg-gray-900" wire:key="pertemuan-{{ $pIndex }}">
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                        {{ $pertemuan['pertemuan_ke'] }}
                    </td>
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                        <flux:input type="date" max="2999-12-31"
                            wire:model.defer="pertemuans.{{ $pIndex }}.tanggal" :disabled="$isView" />
                    </td>
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle" colspan="2">
                        <flux:textarea wire:model.defer="pertemuans.{{ $pIndex }}.pokok_bahasan"
                            :disabled="$isView" />
                    </td>
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                        <flux:input type="time" wire:model.defer="pertemuans.{{ $pIndex }}.jam"
                            :disabled="$isView" />
                    </td>
                    <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle"
                        colspan="{{ $isView ? 2 : 0 }}">
                        <flux:checkbox wire:model.defer="pertemuans.{{ $pIndex }}.paraf" :disabled="$isView" />
                    </td>
                    @if (!$isView)
                        <td class="p-4 border border-gray-200 dark:border-gray-700 text-left align-middle">
                            <div class="flex flex-col gap-3">
                                <flux:button icon="plus" size="xs" wire:click="addPertemuan">Tambah
                                </flux:button>
                                <flux:button icon="x-mark" size="xs"
                                    wire:click="removePertemuan({{ $pIndex }})">Hapus </flux:button>
                            </div>
                        </td>
                    @endif
                </tr>
            @endforeach

        </tbody>
