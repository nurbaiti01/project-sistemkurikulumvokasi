<x-card title="Capaian Pembelajaran Lulusan">
    @php
        $cpls = $cplList;
        $cpmks = $cpmkList;
    @endphp
    <div
        class="w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">

        <!-- Header -->
        <div class="px-4 py-3 bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-300 dark:border-neutral-700">
            <h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                A. CPL Prodi yang Dibebankan pada MK
            </h3>
        </div>

        <!-- Content -->
        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
            @if (!empty($cplList))
                @foreach ($cpls as $cpl)
                    <div class="px-4 py-3 grid grid-cols-1 sm:grid-cols-12 gap-3">

                        <!-- Code -->
                        <div class="sm:col-span-2">
                            <span
                                class="inline-flex items-center justify-center rounded-md bg-neutral-100 dark:bg-neutral-800 px-3 py-1 text-sm font-semibold text-neutral-700 dark:text-neutral-200">
                                {{ $cpl->code }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-10 text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed">
                            {{ $cpl->description }}
                        </div>

                    </div>
                @endforeach
            @else
                <div class="px-4 py-4 text-sm text-neutral-500 text-center">
                    Tidak ada CPL yang dibebankan.
                </div>
            @endif
        </div>

    </div>

    <div
        class="mt-3 w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">
        <!-- Header -->
        <div class="px-4 py-3 bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-300 dark:border-neutral-700">
            <h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                B. Capaian Pembelajaran Mata Kuliah (CPMK)
            </h3>
        </div>
        <!-- Content -->
        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
            @if (!empty($cpmks) && is_iterable($cpmks))
                @foreach ($cpmks as $index => $cpmk)
                    <div class="px-4 py-4 grid grid-cols-1 sm:grid-cols-12 gap-4 items-start">

                        <!-- Code -->
                        <div class="sm:col-span-2">
                            <span
                                class="inline-flex items-center justify-center rounded-md bg-blue-50 dark:bg-blue-900/30 px-3 py-1 text-sm font-semibold text-blue-700 dark:text-blue-300">
                                {{ $cpmk->code }}
                            </span>
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-8 text-sm text-neutral-600 dark:text-neutral-300 leading-relaxed">
                            {{ $cpmk->description }}
                        </div>

                        <!-- Weight -->
                        <div class="sm:col-span-2 flex sm:justify-end">
                            <flux:input.group>
                                <flux:input class="w-20 text-right font-mono" placeholder="%"
                                    wire:model.defer="form.cpmks.{{ $cpmk->id }}.bobot" />
                                <flux:input.group.suffix>%</flux:input.group.suffix>
                            </flux:input.group>

                        </div>

                    </div>
                @endforeach
            @else
                <div class="px-4 py-4 text-sm text-neutral-500 text-center">
                    Data CPMK belum tersedia.
                </div>
            @endif
        </div>
    </div>

    <div
        class="mt-3 w-full rounded-xl border border-neutral-300 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">
        <!-- Header -->
        <div class="px-4 py-3 bg-neutral-50 dark:bg-neutral-800 border-b border-neutral-300 dark:border-neutral-700">
            <h3 class="text-sm font-semibold text-neutral-800 dark:text-neutral-100">
                Korelasi CPL Terhadap CPMK
            </h3>
        </div>
        <!-- Content -->
        <div class="divide-y divide-neutral-200 dark:divide-neutral-700">
            <table class="min-w-full text-sm border-collapse">
                <thead class="bg-gray-100 dark:bg-gray-800 sticky top-0 z-10">
                    <tr>
                        <th
                            class="border border-gray-200 dark:border-gray-700 px-3 py-2 text-left text-gray-700 dark:text-gray-200">
                            CPMK
                        </th>
                        @foreach ($cpls as $cpl)
                            <th
                                class="border border-gray-200 dark:border-gray-700 px-2 py-2 text-center text-gray-700 dark:text-gray-200 whitespace-nowrap">
                                {{ $cpl->code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($cpmks as $pivotCpmk)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-3 py-2 font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                {{ $pivotCpmk->code }}
                            </td>

                            @foreach ($cpls as $cpl)
                                <td class="border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-center py-2">
                                        @if ($matrik[$pivotCpmk->id][$cpl->id])
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="2.5" stroke="currentColor" class="size-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @empty
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</x-card>
