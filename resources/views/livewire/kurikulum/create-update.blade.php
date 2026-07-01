<div class="container py-4 mx-auto">
    <form wire:submit.prevent="saveKurikulum" class="gap-4 mt-2">
        <div class="w-full">
            <div class="flex flex-wrap border-b border-neutral-300 dark:border-neutral-700">
                @foreach ($tabName as $key => $label)
                    <button type="button" wire:click="settabActive({{ $key }})"
                        class="px-4 py-2 text-sm font-medium border-b-2 -mb-px
        {{ $tabActive === $key
            ? 'border-black text-black dark:border-white dark:text-white'
            : 'border-transparent text-neutral-400' }}">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <div class="pt-4">
                <x-card wire:show="tabActive == 0">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Metadata Kurikulum</x-card.title>
                    </x-card.header>
                    <x-card.content>

                        <div class="flex flex-col gap-3">
                            <flux:field>
                                <flux:label>Program Studi </flux:label>
                                <flux:select wire:model.change="form.programStudis" disabled>
                                    <flux:select.option value="">Pilih Program Studi</flux:select.option>
                                    @foreach ($this->getProdiProperty() as $pd)
                                        <flux:select.option value="{{ $pd->id }}">
                                            {{ $pd->jenjang }}-{{ $pd->name }}
                                        </flux:select.option>
                                    @endforeach
                                </flux:select>
                                <flux:error name="form.programStudis" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Nama Kurikulum</flux:label>
                                <flux:input wire:model="form.name" type="text" />
                                <flux:error name="form.name" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Tahun</flux:label>
                                <flux:input wire:model="form.year" type="text" />
                                <flux:error name="form.year" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Version</flux:label>
                                <flux:input wire:model="form.version" type="number" />
                                <flux:error name="form.version" />
                            </flux:field>
                            <flux:field>
                                <flux:label>Type</flux:label>
                                <flux:radio.group wire:model="form.type" variant="segmented">
                                    <flux:radio label="New" value="new" />
                                    <flux:radio label="Revisi Minor" value="minor_revision" />
                                    <flux:radio label="Revisi Major" value="major_revision" />
                                </flux:radio.group>
                                <flux:error name="form.type" />
                            </flux:field>
                        </div>
                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 1">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi CPL - PL</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead
                                    class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <tr>
                                        <th scope="col" class="p-4 w-16">Code CPL</th>
                                        <th scope="col" class="p-4 w-96">Capaian Pembelajaran Lulusan</th>
                                        @foreach ($listPl as $pl)
                                            <th scope="col" class="p-4 w-10">{{ $pl->code }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                    @foreach ($listCpl as $cpl)
                                        <tr>
                                            <td class="p-4">{{ $cpl->code }}</td>
                                            <td class="p-4">{{ $cpl->description }}</td>
                                            @foreach ($listPl as $pl)
                                                <td class="p-4">
                                                    <flux:field variant="inline">
                                                        <flux:checkbox
                                                            id="cpl-{{ $cpl->id }}-pl-{{ $pl->id }}"
                                                            wire:model.defer="form.cpl_pl.{{ $cpl->id }}"
                                                            value="{{ $pl->id }}"
                                                            wire:key="cpl-{{ $cpl->id }}-pl-{{ $pl->id }}" />
                                                        <flux:error name="form.cpl_pl.{{ $cpl->id }}" />
                                                    </flux:field>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 2">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi BK - CPL</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead
                                    class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <tr>
                                        <th scope="col" class="p-4 w-16">Code BK</th>
                                        <th scope="col" class="p-4 w-96">Bahan Kajian</th>
                                        @foreach ($listCpl as $cpl)
                                            <th scope="col" class="p-4 w-10">{{ $cpl->code }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                    @foreach ($listBk as $bk)
                                        <tr>
                                            <td class="p-4">{{ $bk->code }}</td>
                                            <td class="p-4">{{ $bk->name }}</td>
                                            @foreach ($listCpl as $cpl)
                                                <td class="p-4">
                                                    <flux:field variant="inline">
                                                        <flux:checkbox
                                                            id="bk-{{ $bk->id }}-cpl-{{ $cpl->id }}"
                                                            wire:model.defer="form.bk_cpl.{{ $bk->id }}"
                                                            value="{{ $cpl->id }}"
                                                            wire:key="bk-{{ $bk->id }}-cpl-{{ $cpl->id }}" />
                                                        <flux:error name="form.bk_cpl.{{ $bk->id }}" />
                                                    </flux:field>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 3">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi BK - MK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead
                                    class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <tr>
                                        <th class="p-4 w-32">Code BK</th>
                                        <th class="p-4">Bahan Kajian</th>
                                        <th class="p-4 w-32 text-center">Detail</th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                    @foreach ($listBk as $bk)
                                <tbody x-data="{ open: false }">
                                    <tr>
                                        <td class="p-4 font-medium">{{ $bk->code }}</td>
                                        <td class="p-4">{{ $bk->name }}</td>
                                        <td class="p-4 text-center">

                                            <button @click="open = !open" type="button"
                                                class="rounded bg-blue-600 px-3 py-1 text-xs text-white hover:bg-blue-700 transition">
                                                <span x-show="!open">Lihat</span>
                                                <span x-show="open">Tutup</span>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr x-show="open" x-transition>
                                        <td colspan="3" class="p-4 bg-neutral-50 dark:bg-neutral-900">
                                            <div class="flex flex-wrap gap-3 max-h-96 overflow-y-auto">
                                                @foreach ($listMk as $mk)
                                                    <div class="w-full sm:w-1/3 md:w-1/5 lg:w-1/6">
                                                        <flux:checkbox
                                                            wire:key="bk-mk-{{ $bk->id }}-{{ $mk->id }}"
                                                            wire:model.defer="form.bk_mk.{{ $bk->id }}"
                                                            value="{{ $mk->id }}" label="{{ $mk->code }}"
                                                            description="{{ $mk->name }}" />
                                                    </div>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                                @endforeach
                                </tbody>
                            </table>

                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 4">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi CPMK - SUBCPMK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="relative w-full
           max-h-[65vh]
           overflow-x-auto overflow-y-auto
           rounded-sm border border-neutral-300
           dark:border-neutral-700">
                            <table class="min-w-max w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead
                                    class="sticky top-0 z-10
                   border-b border-neutral-300
                   bg-neutral-50 text-sm text-neutral-900
                   dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <tr>
                                        <th class="p-4 w-24 whitespace-nowrap">Code CPMK</th>
                                        <th class="p-4 w-96 whitespace-nowrap">CPMK</th>

                                        @foreach ($listSubCpmk as $subcpmk)
                                            <th class="p-4 w-14 text-center whitespace-nowrap">
                                                {{ $subcpmk->code }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                    @foreach ($listCpmk as $cpmk)
                                        <tr>
                                            <td class="p-4 whitespace-nowrap">
                                                {{ $cpmk->code }}
                                            </td>

                                            <td class="p-4 min-w-[24rem]">
                                                {{ $cpmk->description }}
                                            </td>

                                            @foreach ($listSubCpmk as $subcpmk)
                                                <td class="p-4 text-center">
                                                    <flux:field variant="inline">
                                                        <flux:checkbox
                                                            id="cpmk-{{ $cpmk->id }}-sub-{{ $subcpmk->id }}"
                                                            wire:model.defer="form.cpmk_subcpmk.{{ $cpmk->id }}"
                                                            value="{{ $subcpmk->id }}"
                                                            wire:key="cpmk-{{ $cpmk->id }}-sub-{{ $subcpmk->id }}" />
                                                    </flux:field>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 5">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi MK - CPL</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="relative w-full max-h-[65vh]
           overflow-x-auto overflow-y-auto
           rounded-xl
           border border-neutral-200
           bg-white shadow-sm
           dark:border-neutral-800 dark:bg-neutral-950">

                            <table
                                class="min-w-max w-full
               text-sm
               text-neutral-700
               dark:text-neutral-300
               border-collapse">

                                <thead
                                    class="sticky top-0 z-20
                   bg-neutral-100/90 backdrop-blur
                   border-b border-neutral-200
                   dark:bg-neutral-900/90
                   dark:border-neutral-800">

                                    <tr>
                                        <!-- Sticky Left Headers -->
                                        <th
                                            class="sticky left-0 z-30
                           p-4 w-24 font-semibold
                           bg-neutral-100 dark:bg-neutral-900
                           border-r border-neutral-200
                           dark:border-neutral-800">
                                            Code MK
                                        </th>

                                        <th
                                            class="sticky left-24 z-30
                           p-4 min-w-[360px] font-semibold
                           bg-neutral-100 dark:bg-neutral-900
                           border-r border-neutral-200
                           dark:border-neutral-800">
                                            Mata Kuliah
                                        </th>

                                        @foreach ($listCpl as $cpl)
                                            <th class="p-4 w-14 text-center font-semibold whitespace-nowrap">
                                                {{ $cpl->code }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-200
                   dark:divide-neutral-800">

                                    @foreach ($listMk as $mk)
                                        <tr
                                            class="transition-colors
                           hover:bg-neutral-50
                           dark:hover:bg-neutral-800/50">

                                            <!-- Sticky Left Cells -->
                                            <td
                                                class="sticky left-0 z-10
                               p-4 whitespace-nowrap font-medium
                               bg-white dark:bg-neutral-950
                               border-r border-neutral-200
                               dark:border-neutral-800">
                                                {{ $mk->code }}
                                            </td>

                                            <td
                                                class="sticky left-24 z-10
                               p-4 whitespace-nowrap
                               bg-white dark:bg-neutral-950
                               border-r border-neutral-200
                               dark:border-neutral-800">
                                                {{ $mk->name }}
                                            </td>

                                            @foreach ($listCpl as $cpl)
                                                <td class="p-4 text-center">
                                                    <div class="flex justify-center">
                                                        <flux:field variant="inline">
                                                            <flux:checkbox
                                                                id="mk-{{ $mk->id }}-cpl-{{ $cpl->id }}"
                                                                wire:model.defer="form.mk_cpl.{{ $mk->id }}"
                                                                value="{{ $cpl->id }}"
                                                                wire:key="mk-{{ $mk->id }}-cpl-{{ $cpl->id }}"
                                                                class="scale-110" />
                                                        </flux:field>
                                                    </div>
                                                </td>
                                            @endforeach

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 6">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Relasi CPMK - MK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="relative w-full max-h-[65vh]
           overflow-x-auto overflow-y-auto
           rounded-xl
           border border-neutral-200
           bg-white shadow-sm
           dark:border-neutral-800 dark:bg-neutral-950">

                            <table
                                class="min-w-max w-full
               text-sm
               text-neutral-700
               dark:text-neutral-300">

                                <thead
                                    class="sticky top-0 z-10
                   bg-neutral-100/90 backdrop-blur
                   border-b border-neutral-200
                   text-neutral-900
                   dark:bg-neutral-900/90
                   dark:border-neutral-800
                   dark:text-white">

                                    <tr>
                                        <th class="p-4 w-20 font-semibold whitespace-nowrap">
                                            Code MK
                                        </th>

                                        <th class="p-4 min-w-[260px] font-semibold">
                                            Matakuliah
                                        </th>

                                        @foreach ($listCpmk as $cpmk)
                                            <th class="p-4 w-16 text-center font-semibold whitespace-nowrap">
                                                {{ $cpmk->code }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-200
                   dark:divide-neutral-800">

                                    @foreach ($listMk as $mk)
                                        <tr
                                            class="transition-colors
                           hover:bg-neutral-50
                           dark:hover:bg-neutral-800/50">

                                            <!-- Code -->
                                            <td class="p-4 font-medium whitespace-nowrap">
                                                {{ $mk->code }}
                                            </td>

                                            <!-- Name -->
                                            <td
                                                class="p-4 leading-relaxed
                               text-neutral-800
                               dark:text-neutral-200">
                                                {{ $mk->name }}
                                            </td>

                                            @foreach ($listCpmk as $cpmk)
                                                <td class="p-4 text-center">
                                                    <div class="flex justify-center">
                                                        <flux:checkbox
                                                            id="mk-{{ $mk->id }}-cpmk-{{ $cpmk->id }}"
                                                            wire:model.defer="form.cpmk_mk.{{ $mk->id }}"
                                                            value="{{ $cpmk->id }}"
                                                            wire:key="mk-{{ $mk->id }}-cpmk-{{ $cpmk->id }}"
                                                            class="scale-110" />
                                                    </div>
                                                </td>
                                            @endforeach

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 7">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Matriks CPL - BK - MK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="relative w-full overflow-x-auto rounded-xl
           border border-neutral-200
           bg-white shadow-sm
           dark:border-neutral-800 dark:bg-neutral-950">

                            <table
                                class="w-full min-w-max text-left text-sm
               text-neutral-700
               dark:text-neutral-300">

                                <thead
                                    class="sticky top-0 z-10
                   bg-neutral-100/90 backdrop-blur
                   text-neutral-900
                   border-b border-neutral-200
                   dark:bg-neutral-900/90
                   dark:text-white
                   dark:border-neutral-800">

                                    <tr>
                                        <th scope="col" class="p-4 w-24 font-semibold whitespace-nowrap">
                                            Code BK
                                        </th>

                                        @foreach ($listCpl as $cpl)
                                            <th scope="col" class="p-4 w-14 text-center font-semibold">
                                                {{ $cpl->code }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-200
                   dark:divide-neutral-800">

                                    @foreach ($listBk as $bk)
                                        <tr
                                            class="transition-colors
                           hover:bg-neutral-50
                           dark:hover:bg-neutral-800/50">

                                            <!-- Code BK -->
                                            <td class="p-4 font-medium whitespace-nowrap">
                                                {{ $bk->code }}
                                            </td>

                                            @foreach ($listCpl as $cpl)
                                                <td class="p-3 align-top">
                                                    <div class="flex flex-col gap-2">

                                                        {{-- MK Selected --}}
                                                        @if (!empty($setTempSelectCplBkMK[$bk->id][$cpl->id]['code']))
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach (collect($setTempSelectCplBkMK[$bk->id][$cpl->id]['code'])->take(3) as $code)
                                                                    <span
                                                                        class="inline-flex items-center rounded-full
                                                       bg-blue-100 text-blue-700
                                                       px-2 py-0.5 text-[11px] font-medium
                                                       dark:bg-blue-900/30 dark:text-blue-300">
                                                                        {{ $code }}
                                                                    </span>
                                                                @endforeach

                                                                {{-- +N indicator --}}
                                                                @if (count($setTempSelectCplBkMK[$bk->id][$cpl->id]['code']) > 3)
                                                                    <span
                                                                        class="inline-flex items-center
                                                       text-[11px]
                                                       text-neutral-500
                                                       dark:text-neutral-400">
                                                                        +{{ count($setTempSelectCplBkMK[$bk->id][$cpl->id]['code']) - 3 }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            {{-- Empty State --}}
                                                            <span
                                                                class="text-[11px] italic
                                               text-neutral-400
                                               dark:text-neutral-500">
                                                                Belum ada MK
                                                            </span>
                                                        @endif

                                                        {{-- Action --}}
                                                        <div>
                                                            <flux:button icon="plus" size="xs"
                                                                variant="ghost"
                                                                class="hover:bg-blue-50 dark:hover:bg-blue-900/20"
                                                                wire:click="openAddCplBkMk({{ $cpl->id }}, {{ $bk->id }})">
                                                                <span class="sr-only">Tambah MK</span>
                                                            </flux:button>
                                                        </div>

                                                    </div>
                                                </td>
                                            @endforeach

                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>


                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 8">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Matriks CPL - CPMK - MK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        {{-- <div
                            class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
                            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                                <thead
                                    class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                                    <tr>
                                        <th scope="col" class="p-4 w-16">Code CPL</th>
                                        @foreach ($listCpmk as $cpmk)
                                            <th scope="col" class="p-4 w-10">{{ $cpmk->code }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                                    @foreach ($listCpl as $cpl)
                                        <tr>
                                            <td class="p-4">{{ $cpl->code }}</td>
                                            @foreach ($listCpmk as $cpmk)
                                                <td class="p-4 align-top">
                                                    <div class="flex flex-col gap-2">

                                                        @if (!empty($setTempSelectCplCpmkMK[$cpmk->id][$cpl->id]['code']))
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach (collect($setTempSelectCplCpmkMK[$cpmk->id][$cpl->id]['code'])->take(3) as $code)
                                                                    <span
                                                                        class="inline-flex items-center rounded-md
                                                                            bg-blue-50 text-blue-700
                                                                            px-2 py-0.5 text-xs font-medium
                                                                            dark:bg-blue-900/30 dark:text-blue-300">
                                                                        {{ $code }}
                                                                    </span>
                                                                @endforeach

                                                                @if (count($setTempSelectCplCpmkMK[$cpmk->id][$cpl->id]['code']) > 3)
                                                                    <span
                                                                        class="text-xs text-neutral-500 dark:text-neutral-400">
                                                                        +{{ count($setTempSelectCplCpmkMK[$cpmk->id][$cpl->id]['code']) - 3 }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @else
                                                            <span class="text-xs italic text-neutral-400">
                                                                Belum ada MK
                                                            </span>
                                                        @endif

                                                        <div>
                                                            <flux:button icon="plus" size="xs"
                                                                variant="ghost"
                                                                wire:click="openAddCplCpmkMk({{ $cpmk->id }}, {{ $cpl->id }})">
                                                                <span class="sr-only">Tambah MK</span>
                                                            </flux:button>
                                                        </div>
                                                    </div>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div> --}}
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                            @foreach ($listMk as $mk)
                                @php
                                    $cpls = $mk->MkCpl->pluck('cpl')->unique('id');
                                @endphp

                                <!-- CARD MK -->
                                <div
                                    class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm">

                                    <!-- HEADER -->
                                    <div class="px-5 py-4 border-b dark:border-gray-700">
                                        <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">
                                            {{ $mk->code }}
                                        </h2>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ $mk->name }}
                                        </p>
                                    </div>

                                    <!-- TABLE -->
                                    <div class="overflow-x-auto">
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
                                                @forelse ($mk->MkCpmk->unique('cpmk_id') as $pivotCpmk)
                                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                                        <td
                                                            class="border border-gray-200 dark:border-gray-700 px-3 py-2 font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                            {{ $pivotCpmk->cpmk->code }}
                                                        </td>

                                                        @foreach ($cpls as $cpl)
                                                            <td class="border border-gray-200 dark:border-gray-700">
                                                                <div class="flex items-center justify-center py-2">
                                                                    <flux:checkbox
                                                                        id="mk-{{ $mk->id }}-cpmk-{{ $pivotCpmk->cpmk->id }}-cpl-{{ $cpl->id }}"
                                                                        wire:model.defer="form.cpl_cpmk_mk.{{ $mk->id }}.{{ $pivotCpmk->cpmk->id }}"
                                                                        value="{{ $cpl->id }}"
                                                                        wire:key="mk-{{ $mk->id }}-cpmk-{{ $pivotCpmk->cpmk->id }}-cpl-{{ $cpl->id }}" />
                                                                </div>
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="{{ $cpls->count() + 1 }}"
                                                            class="border border-gray-200 dark:border-gray-700 bg-yellow-50 dark:bg-yellow-900/30 px-4 py-6 text-center">
                                                            <div class="flex flex-col items-center gap-2">
                                                                <svg class="w-8 h-8 text-yellow-500 dark:text-yellow-400"
                                                                    fill="none" stroke="currentColor"
                                                                    stroke-width="1.5" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.4 12.8A1.5 1.5 0 004.17 19h15.66a1.5 1.5 0 001.28-2.34l-7.4-12.8a1.5 1.5 0 00-2.42 0z" />
                                                                </svg>

                                                                <p
                                                                    class="font-semibold text-yellow-700 dark:text-yellow-300">
                                                                    Belum ada CPMK
                                                                </p>

                                                                <p
                                                                    class="text-sm text-yellow-600 dark:text-yellow-400">
                                                                    Silakan tambahkan CPMK terlebih dahulu untuk
                                                                    matakuliah ini
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            @endforeach
                        </div>

                    </x-card.content>
                </x-card>
                <x-card class="col-span-12" wire:show="tabActive == 9">
                    <x-card.header>
                        <x-card.title class="dark:text-white">Distribusi MK</x-card.title>
                    </x-card.header>
                    <x-card.content>
                        <div
                            class="relative w-full
           max-h-[65vh]
           overflow-x-auto overflow-y-auto
           rounded-xl border border-neutral-200
           bg-white
           shadow-sm
           dark:bg-neutral-950
           dark:border-neutral-800">

                            <table
                                class="min-w-max w-full text-left text-sm
               text-neutral-700
               dark:text-neutral-300">

                                <thead
                                    class="sticky top-0 z-10
                   bg-neutral-100/90 backdrop-blur
                   text-neutral-900
                   border-b border-neutral-200
                   dark:bg-neutral-900/90
                   dark:text-white
                   dark:border-neutral-800">

                                    <!-- ROW 1: HEADER UTAMA -->
                                    <tr>
                                        <th rowspan="2"
                                            class="p-4 w-20 whitespace-nowrap align-middle font-semibold">
                                            Code MK
                                        </th>

                                        <th rowspan="2" class="p-4 w-72 align-middle font-semibold">
                                            Matakuliah
                                        </th>

                                        <th rowspan="2" class="p-4 w-32 text-center align-middle font-semibold">
                                            SKS
                                        </th>

                                        <th class="p-4 text-center font-semibold">
                                            Semester
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="divide-y divide-neutral-200
                   dark:divide-neutral-800">

                                    @foreach ($listMk as $mk)
                                        <tr
                                            class="align-top
                           transition-colors
                           hover:bg-neutral-50
                           dark:hover:bg-neutral-800/50">

                                            <!-- Code -->
                                            <td class="p-4 font-medium whitespace-nowrap">
                                                {{ $mk->code }}
                                            </td>

                                            <!-- Name -->
                                            <td class="p-4 text-sm leading-relaxed">
                                                {{ $mk->name }}
                                            </td>

                                            <!-- SKS Input -->
                                            <td class="p-4 text-center">
                                                <div class="mx-auto w-20">
                                                    <flux:input type="number" min="1" max="6"
                                                        placeholder="0" class="text-center"
                                                        wire:model.defer="form.distribusi_mk.{{ $mk->id }}.sks" />
                                                </div>
                                            </td>

                                            <!-- Semester Radios -->
                                            <td class="p-4">
                                                <flux:radio.group
                                                    wire:model.live="form.distribusi_mk.{{ $mk->id }}.semester"
                                                    label="" variant="segmented" size="sm">
                                                    @foreach (range(1, $listSemeter) as $smst)
                                                        <flux:radio value="{{ $smst }}"
                                                            label="{{ $smst }}"
                                                            wire:key="smst-{{ $smst }}-mk-{{ $mk->id }}" />
                                                    @endforeach
                                                </flux:radio.group>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>


                    </x-card.content>
                </x-card>
            </div>
        </div>
        <div class="flex justify-between mt-6">
            {{-- Prev --}}
            <flux:button type="button" wire:click="prevStep" :disabled="$tabActive === 0">
                Sebelumnya
            </flux:button>

            {{-- Next / Submit --}}
            @if ($tabActive < $maxTab)
                <flux:button type="button" variant="primary" wire:click="confirmNextStep">
                    Simpan dan Selanjutnya
                </flux:button>
            @else
                <flux:button type="submit" variant="primary">
                    Simpan Kurikulum
                </flux:button>
            @endif
        </div>
    </form>

    <div x-data="{ modalIsOpen: $wire.entangle('showModalCplBkMK') }">
        <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
            x-on:keydown.esc.window="modalIsOpen = false" class="fixed inset-0 z-40 bg-black/30 " role="dialog"
            aria-modal="true">
            <!-- Click Outside -->
            <div class="absolute inset-0" x-on:click="modalIsOpen = false"></div>

            <!-- Slideover Panel -->
            <div x-show="modalIsOpen" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute right-0 top-0 h-full w-full max-w-lg bg-white dark:bg-neutral-900
                   border-l border-neutral-300 dark:border-neutral-700 flex flex-col">
                <!-- Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60
                       p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
                    <h3 class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                        CPL – BK – Mata Kuliah
                    </h3>
                    <button x-on:click="modalIsOpen = false" aria-label="close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none"
                            stroke="currentColor" stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <p class="text-sm">
                        <strong>CPL:</strong> {{ $tempSelectCplBkMK['cpl']['code'] }}
                    </p>
                    <p class="text-sm">
                        <strong>BK:</strong> {{ $tempSelectCplBkMK['bk']['code'] }}
                    </p>

                    <div class="mt-4 space-y-2">
                        @foreach ($listMk as $mk)
                            <div class="py-3">
                                @if (filled($tempSelectCplBkMK['bk']['id']) && filled($tempSelectCplBkMK['cpl']['id']))
                                    <flux:checkbox wire:key="mk-{{ $mk['id'] }}"
                                        wire:model.defer="form.cpl_bk_mk.{{ $tempSelectCplBkMK['bk']['id'] }}.{{ $tempSelectCplBkMK['cpl']['id'] }}"
                                        value="{{ $mk['id'] }}" label="{{ $mk['code'] }}"
                                        description="Nama MK: {{ $mk['name'] }}" />
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="border-t border-neutral-300 bg-neutral-50/60 p-4
                       dark:border-neutral-700 dark:bg-neutral-950/20 flex justify-end gap-2">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="rounded-sm px-4 py-2 text-sm text-neutral-600
                           hover:opacity-80 dark:text-neutral-300">
                        Batal
                    </button>

                    <button wire:click="setCplBkMK" type="button"
                        class="rounded-sm bg-black text-white px-4 py-2 text-sm
                           hover:opacity-80 dark:bg-white dark:text-black">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div x-data="{ modalIsOpen: $wire.entangle('showModalCplCpmkMK') }">
        <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
            x-on:keydown.esc.window="modalIsOpen = false" class="fixed inset-0 z-40 bg-black/30 " role="dialog"
            aria-modal="true">
            <!-- Click Outside -->
            <div class="absolute inset-0" x-on:click="modalIsOpen = false"></div>

            <!-- Slideover Panel -->
            <div x-show="modalIsOpen" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute right-0 top-0 h-full w-full max-w-lg bg-white dark:bg-neutral-900
                   border-l border-neutral-300 dark:border-neutral-700 flex flex-col">
                <!-- Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60
                       p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
                    <h3 class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                        CPL – CPMK – Mata Kuliah
                    </h3>
                    <button x-on:click="modalIsOpen = false" aria-label="close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none"
                            stroke="currentColor" stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <p class="text-sm">
                        <strong>CPL:</strong> {{ $tempSelectCplCpmkMK['cpl']['code'] }}
                    </p>
                    <p class="text-sm">
                        <strong>CPMK:</strong> {{ $tempSelectCplCpmkMK['cpmk']['code'] }}
                    </p>

                    <div class="mt-4 space-y-2">
                        @foreach ($listMk as $mk)
                            <div class="py-3">
                                @if (filled($tempSelectCplCpmkMK['cpmk']['id']) && filled($tempSelectCplCpmkMK['cpl']['id']))
                                    <flux:checkbox.group label="{{ $mk['name'] }}">
                                        <flux:checkbox wire:key="mk-{{ $opt['id'] }}"
                                            wire:model.defer="form.cpl_cpmk_mk.{{ $tempSelectCplCpmkMK['cpmk']['id'] }}.{{ $tempSelectCplCpmkMK['cpl']['id'] }}"
                                            value="{{ $opt['id'] }}" label="{{ $opt['code'] }}"
                                            description="Nama MK: {{ $opt['name'] }} | SKS: {{ $opt['sks'] }}" />
                                    </flux:checkbox.group>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="border-t border-neutral-300 bg-neutral-50/60 p-4
                       dark:border-neutral-700 dark:bg-neutral-950/20 flex justify-end gap-2">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="rounded-sm px-4 py-2 text-sm text-neutral-600
                           hover:opacity-80 dark:text-neutral-300">
                        Batal
                    </button>

                    <button wire:click="setCplCpmkMK" type="button"
                        class="rounded-sm bg-black text-white px-4 py-2 text-sm
                           hover:opacity-80 dark:bg-white dark:text-black">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
        {{-- </div>
    <div x-data="{ modalIsOpen: $wire.entangle('showModalBkMK') }">
        <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
            x-on:keydown.esc.window="modalIsOpen = true" class="fixed inset-0 z-40 bg-black/30 " role="dialog"
            aria-modal="true">
            <!-- Click Outside -->
            <div class="absolute inset-0" x-on:click="modalIsOpen = true"></div>

            <!-- Slideover Panel -->
            <div x-show="modalIsOpen" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute right-0 top-0 h-full w-full max-w-lg bg-white dark:bg-neutral-900
                   border-l border-neutral-300 dark:border-neutral-700 flex flex-col">
                <!-- Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60
                       p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
                    <h3 class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                        Pilih Mata Kuliah untuk BK {{ $tempSelectBkMK['bk']['code'] }}
                    </h3>
                    <button x-on:click="modalIsOpen = false" aria-label="close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none"
                            stroke="currentColor" stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">

                    <div class="mt-4 space-y-2">
                        @if (filled($tempSelectBkMK['bk']['id']))
                            @foreach ($listMkOption as $mk)
                                <div class="py-2">

                                    <flux:checkbox.group label="{{ $mk['name'] }}" class="space-y-2">
                                        @foreach ($mk['options'] as $opt)
                                            <flux:checkbox
                                                wire:key="bk-mk-{{ $tempSelectBkMK['bk']['id'] }}-{{ $opt['id'] }}"
                                                wire:model.defer="
                                form.bk_mk.{{ $tempSelectBkMK['bk']['id'] }}
                            "
                                                value="{{ $opt['id'] }}" label="{{ $opt['code'] }}"
                                                description="
                                {{ $opt['name'] }}
                                • {{ $opt['sks'] }} SKS
                                • Semester {{ $opt['semester'] }}
                            " />
                                        @endforeach
                                    </flux:checkbox.group>

                                </div>
                            @endforeach
                        @else
                            <p class="text-sm italic text-neutral-400">
                                Pilih BK terlebih dahulu
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="border-t border-neutral-300 bg-neutral-50/60 p-4
                       dark:border-neutral-700 dark:bg-neutral-950/20 flex justify-end gap-2">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="rounded-sm px-4 py-2 text-sm text-neutral-600
                           hover:opacity-80 dark:text-neutral-300">
                        Batal
                    </button>

                    <button wire:click="setBkMk" type="button"
                        class="rounded-sm bg-black text-white px-4 py-2 text-sm
                           hover:opacity-80 dark:bg-white dark:text-black">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div> --}}

        {{-- <div x-data="{ modalIsOpen: $wire.entangle('showModalCpmkMK') }">
        <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
            x-on:keydown.esc.window="modalIsOpen = false" class="fixed inset-0 z-40 bg-black/30 " role="dialog"
            aria-modal="true">
            <!-- Click Outside -->
            <div class="absolute inset-0" x-on:click="modalIsOpen = false"></div>

            <!-- Slideover Panel -->
            <div x-show="modalIsOpen" x-transition:enter="transform transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in duration-200"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
                class="absolute right-0 top-0 h-full w-full max-w-lg bg-white dark:bg-neutral-900
                   border-l border-neutral-300 dark:border-neutral-700 flex flex-col">
                <!-- Header -->
                <div
                    class="flex items-center justify-between border-b border-neutral-300 bg-neutral-50/60
                       p-4 dark:border-neutral-700 dark:bg-neutral-950/20">
                    <h3 class="font-semibold tracking-wide text-neutral-900 dark:text-white">
                        Pilih Mata Kuliah untuk CPMK
                    </h3>
                    <button x-on:click="modalIsOpen = false" aria-label="close">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5" fill="none"
                            stroke="currentColor" stroke-width="1.4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-4 space-y-2">
                    <div class="text-sm">
                        <span class="text-neutral-500">CPMK:</span>
                        <span class="font-semibold">
                            {{ $tempSelectCpmkMK['cpmk']['code'] }}
                        </span>
                    </div>
                    <div class="mt-4 space-y-2">
                        @if (filled($tempSelectCpmkMK['cpmk']['id']))
                            @foreach ($listMkOption as $mk)
                                <div class="py-2">
                                    <flux:checkbox.group label="{{ $mk['name'] }}">
                                        @foreach ($mk['options'] as $opt)
                                            <flux:checkbox
                                                wire:key="cpmk-mk-{{ $tempSelectCpmkMK['cpmk']['id'] }}-{{ $opt['id'] }}"
                                                wire:model.defer="
                                form.cpmk_mk.{{ $tempSelectCpmkMK['cpmk']['id'] }}
                            "
                                                value="{{ $opt['id'] }}" label="{{ $opt['code'] }}"
                                                description="
                                {{ $opt['name'] }}
                                • {{ $opt['sks'] }} SKS
                                • Semester {{ $opt['semester'] }}
                            " />
                                        @endforeach
                                    </flux:checkbox.group>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div
                    class="border-t border-neutral-300 bg-neutral-50/60 p-4
                       dark:border-neutral-700 dark:bg-neutral-950/20 flex justify-end gap-2">
                    <button x-on:click="modalIsOpen = false" type="button"
                        class="rounded-sm px-4 py-2 text-sm text-neutral-600
                           hover:opacity-80 dark:text-neutral-300">
                        Batal
                    </button>

                    <button wire:click="setCpmkMK" type="button"
                        class="rounded-sm bg-black text-white px-4 py-2 text-sm
                           hover:opacity-80 dark:bg-white dark:text-black">
                        Simpan
                    </button>
                </div>
            </div>
        </div>
    </div> --}}
    </div>
