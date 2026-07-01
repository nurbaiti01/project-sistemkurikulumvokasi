<div class="grid grid-cols-1 md:grid-cols-6 gap-4">
    <div class="col-span-2">
        <x-card title="Riwayat Persetujuan Kontrak Kuliah">
            <div class="p-4 space-y-4">
                <div class="mb-4">
                    <span class="mb-10 text-sm text-neutral-600 dark:text-neutral-400">
                        Status Kontrak Kuliah:
                        @php
                            $statusKontrak = match ($kontrakApprovals->status) {
                                'draft' => 'zinc',
                                'submitted' => 'blue',
                                'published' => 'green',
                                'rejected' => 'red',
                            };
                        @endphp
                        <flux:badge variant="solid" color="{{ $statusKontrak }}">{{ $kontrakApprovals->status }}
                        </flux:badge>
                    </span>
                </div>
                @php
                    $approvals = $kontrakApprovals;
                @endphp
                @forelse ($approvals->kontrakApprovals as $approval)
                    <div
                        class="relative flex gap-4 p-4 rounded-xl
                       border border-neutral-200 dark:border-neutral-700
                       bg-white dark:bg-neutral-900">

                        {{-- TIMELINE DOT --}}
                        <div class="flex flex-col items-center">
                            <span
                                class="w-3 h-3 rounded-full mt-1
                            @if ($approval->approved === true) bg-emerald-500
                            @elseif($approval->approved === false) bg-red-500
                            @else bg-neutral-400 @endif">
                            </span>
                            <span class="flex-1 w-px bg-neutral-300 dark:bg-neutral-700"></span>
                        </div>

                        {{-- CONTENT --}}
                        {{-- CONTENT --}}
                        <div class="flex-1 space-y-2 text-sm">

                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-semibold text-neutral-700 dark:text-neutral-200">
                                        {{ ucfirst($approval->role_proses) }}
                                    </h4>

                                    @php
                                        $status = match ($approval->status) {
                                            'pending' => 'zinc',
                                            'approved' => 'green',
                                            'rejected' => 'red',
                                        };
                                    @endphp

                                    <flux:badge variant="solid" color="{{ $status }}" size="sm">
                                        {{ $approval->status }}
                                    </flux:badge>
                                </div>

                                {{-- ACTION BUTTON --}}
                                <div class="flex gap-2">
                                    {{-- PERUMUSAN --}}
                                    @if ($approval->role_proses === 'perumusan' && $approval->status === 'pending' && session('active_role') == 'Dosen')
                                        <flux:button size="sm" color="blue" variant="primary"
                                            wire:click="openDialog({{ $approval->id }})" wire:loading.attr="disabled">
                                            Submit
                                        </flux:button>
                                    @endif

                                    {{-- PEMERIKSAAN --}}
                                    @if (
                                        $approval->role_proses === 'pemeriksaan' &&
                                            $approval->status === 'pending' &&
                                            session('active_role') == 'Kaprodi' &&
                                            $approvals->status === 'submitted')
                                        <flux:button variant="primary" size="sm" color="green"
                                            wire:click="openDialog({{ $approval->id }},false,'pemeriksaan')"
                                            wire:loading.attr="disabled">
                                            Approve
                                        </flux:button>

                                        <flux:button variant="primary" size="sm" color="red"
                                            wire:click="openDialog({{ $approval->id }},true,'pemeriksaan')"
                                            wire:loading.attr="disabled">
                                            Reject
                                        </flux:button>
                                    @endif
                                </div>
                            </div>

                            <p class="text-neutral-600 dark:text-neutral-400">
                                {{ $approval->role_proses == 'perumusan' ? 'Dosen Pengampu' : 'Kaprodi' }}:
                                <span class="font-medium">
                                    {{ $approval->dosen->name ?? '-' }}
                                </span>
                            </p>

                            @if ($approval->approved_at)
                                <p class="text-xs text-neutral-500 dark:text-neutral-500">
                                    {{ $approval->role_proses == 'perumusan' ? 'Di Submit' : 'Di Setujui' }}:
                                    {{ $approval->approved_at }}
                                </p>
                            @endif

                            @if ($approval->catatan)
                                <div
                                    class="mt-2 p-3 rounded-lg
                   bg-neutral-100 dark:bg-neutral-800
                   text-xs text-neutral-700 dark:text-neutral-300">
                                    <strong>Catatan:</strong>
                                    <p class="mt-1">{{ $approval->catatan }}</p>
                                </div>
                            @endif
                        </div>

                    </div>
                @empty
                    <div class="text-center text-sm text-neutral-500 italic py-6">
                        Belum ada proses persetujuan
                    </div>
                @endforelse
            </div>
        </x-card>

    </div>
    <div class="col-span-4">
        <x-card title="Informasi Kontrak Kuliah">
            <div class="p-4 space-y-4 text-sm
               text-neutral-800 dark:text-neutral-200">

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">
                        Mata Kuliah
                    </span>
                    <span class="sm:col-span-2 font-semibold">
                        {{ $detailMk['nama_mk'] ?? '-' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">Kode MK</span>
                    <span class="sm:col-span-2">{{ $detailMk['kode_mk'] ?? '-' }}</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">Bobot SKS</span>
                    <span class="sm:col-span-2">
                        {{ $detailMk['bobot_sks'] ?? '-' }} SKS
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">Program Studi</span>
                    <span class="sm:col-span-2">
                        {{ $detailMk['program_studi'] ?? '-' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">
                        Semester / Kelas
                    </span>
                    <span class="sm:col-span-2">
                        Semester {{ $detailMk['semester'] ?? '-' }} —
                        Kelas {{ $kelas ?? '-' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">
                        Total Jam
                    </span>
                    <span class="sm:col-span-2">
                        {{ $totalJam ?? '-' }} JP
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">
                        Dosen Pengampu
                    </span>
                    <span class="sm:col-span-2">
                        {{ $detailDosen ?? '-' }}
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-y-1 gap-x-4">
                    <span class="font-medium text-neutral-500 dark:text-neutral-400">
                        Tahun Akademik
                    </span>
                    <span class="sm:col-span-2">
                        {{ $tahun_akademik ?? '-' }}
                    </span>
                </div>

            </div>

            <div class="p-4 space-y-8 text-sm text-neutral-800 dark:text-neutral-200">

                @php

                    $materiTable = $this->parseTable($materi_pembelajaran);

                    $sections = [
                        'Deskripsi Mata Kuliah' => $deskripsiMk,
                        'Tujuan Pembelajaran' => $tujuan_pembelajaran,
                        'Capaian Pembelajaran Matakuliah' => $mk_cpmk,
                        'Strategi Perkuliahan' => $strategi_perkuliahan,
                        'Organisasi Materi' => $materi_pembelajaran, // handled khusus
                        'Kriteria dan Standar Penilaian' => $kriteria_penilaian,
                        'Tata Tertib Perkuliahan' => $tata_tertib,
                    ];
                @endphp

                <ol class="list-decimal pl-5 space-y-6">
                    @foreach ($sections as $title => $content)
                        <li class="space-y-2">
                            <h4 class="font-semibold text-neutral-600 dark:text-neutral-300">
                                {{ $title }}
                            </h4>

                            {{-- KHUSUS ORGANISASI MATERI --}}
                            @if ($title === 'Organisasi Materi')
                                @if (count($materiTable))
                                    <div
                                        class="overflow-x-auto rounded-lg
                                       border border-neutral-200 dark:border-neutral-700">

                                        <table class="min-w-full text-sm text-left">
                                            <thead class="bg-neutral-100 dark:bg-neutral-800">
                                                <tr>
                                                    @foreach ($materiTable[0] as $head)
                                                        <th
                                                            class="px-4 py-2 font-semibold
                                                           text-neutral-700 dark:text-neutral-200">
                                                            {{ $head }}
                                                        </th>
                                                    @endforeach
                                                </tr>
                                            </thead>

                                            <tbody
                                                class="divide-y divide-neutral-200
                                               dark:divide-neutral-700">
                                                @foreach (array_slice($materiTable, 1) as $row)
                                                    <tr
                                                        class="hover:bg-neutral-50
                                                       dark:hover:bg-neutral-800/50">
                                                        @foreach ($row as $cell)
                                                            <td
                                                                class="px-4 py-2
                                                               text-neutral-800
                                                               dark:text-neutral-300">
                                                                {{ $cell }}
                                                            </td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div
                                        class="prose max-w-none
                                   prose-neutral dark:prose-invert
                                   text-sm leading-relaxed">
                                        {!! $content ?: '<span class="italic text-neutral-400">Tidak tersedia</span>' !!}
                                    </div>
                                @endif

                                {{-- SECTION BIASA --}}
                            @else
                                <div
                                    class="prose max-w-none
                                   prose-neutral dark:prose-invert
                                   text-sm leading-relaxed">
                                    {!! $content ?: '<span class="italic text-neutral-400">Tidak tersedia</span>' !!}
                                </div>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </div>

            <x-slot name="footer">
                <div class="flex justify-end">
                    <x-link label="Kembali" href="{{ route('perangkat-ajar.kontrak-kuliah.index') }}" secondary
                        wire:navigate />
                </div>
            </x-slot>
        </x-card>
    </div>

    <flux:modal name="rejectedKontrak" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rejected Kontrak Kuliah</flux:heading>
            </div>
            <flux:textarea label="Catatan Penolakan" wire:model="catatan" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" wire:click="saveRejected" variant="primary">Submit</flux:button>
            </div>
        </div>
    </flux:modal>


</div>
