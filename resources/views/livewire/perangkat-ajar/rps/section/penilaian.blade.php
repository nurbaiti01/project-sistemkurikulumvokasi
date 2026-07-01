<div>
    <x-card class="space-y-4">
        {{-- HEADER --}}
        <div>
            <h3 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                Metode Penilaian dan Keselarasan CPMK
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                Bobot teknik penilaian dan distribusi kontribusi CPMK
            </p>
        </div>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="p-2 border text-left">Teknik Penilaian</th>
                        <th class="p-2 border text-center w-20">%</th>
                        @foreach ($cpmkList as $cpmk)
                            <th class="p-2 border text-center">
                                {{ $cpmk->code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    @foreach (['default' => 'Kelompok Default', 'kognitif' => 'Kelompok Kognitif'] as $group => $label)
                        {{-- GROUP HEADER --}}
                        <tr>
                            <td colspan="{{ 2 + count($cpmkList) }}"
                                class="px-3 py-2 bg-gray-50 dark:bg-gray-900 font-semibold text-gray-700 dark:text-gray-200">
                                {{ $label }}
                            </td>
                        </tr>

                        @foreach ($kelompokPenilaian[$group] as $key)
                            @php
                                $totalCpmk = collect($penilaian[$key]['cpmk'] ?? [])->sum();
                                $sisa = (int) $penilaian[$key]['persentase'] - (int) $totalCpmk;
                            @endphp

                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900">
                                {{-- NAMA TEKNIK --}}
                                <td class="p-2 border capitalize">
                                    {{ str_replace('_', ' ', $key) }}
                                    <div class="text-xs text-gray-500">
                                        Sisa: {{ $sisa }}%
                                    </div>
                                </td>

                                {{-- TOTAL PERSENTASE --}}
                                <td class="p-2 border text-center font-medium">
                                    <flux:input class="max-w-xs" class:input="font-mono" wire:model.live="penilaian.{{ $key }}.persentase"/>
                                    {{-- {{ $penilaian[$key]['persentase'] }}% --}}
                                </td>

                                {{-- INPUT CPMK --}}
                                @foreach ($cpmkList as $cpmkId => $cpmk)
                                    <td class="p-2 border text-center">
                                        <flux:input type="number" min="0" size="xs"
                                            class="w-16 text-center"
                                            :max="$sisa + ($penilaian[$key]['cpmk'][$cpmk->id] ?? 0)"
                                            wire:model.live="penilaian.{{ $key }}.cpmk.{{ $cpmk->id }}" />
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                @php
                    $totalCpmk = [];

                    foreach ($cpmkList as $cpmkId => $cpmk) {
                        $totalCpmk[$cpmk->id] = collect($penilaian)->sum(fn($item) => $item['cpmk'][$cpmk->id] ?? 0);
                    }
                @endphp
                {{-- FOOTER TOTAL --}}
                <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                    <tr>
                        <td class="p-2 border">Total</td>
                        <td class="p-2 border text-center">
                            {{ collect($penilaian)->sum('persentase') }} %
                        </td>
                        @foreach ($cpmkList as $cpmkId => $cpmk)
                            <td class="p-2 border text-center">
                                {{ $totalCpmk[$cpmk->id] }} %
                            </td>
                        @endforeach
                    </tr>
                </tfoot>
            </table>
        </div>
    </x-card>
</div>
