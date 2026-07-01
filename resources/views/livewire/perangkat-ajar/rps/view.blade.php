<div class="flex flex-col gap-2">
    @php
        $cpls = $matriksCplCpmk['cpl'];
        $cpmks = $matriksCplCpmk['cpmk'];
    @endphp
    <div>
        <flux:button wire:click="download">Cetak PDF</flux:button>
    </div>
    <x-card title="Approvals">
        <div class="relative overflow-hidden rounded-xl border border-outline dark:border-outline-dark">
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm text-left text-on-surface dark:text-on-surface-dark">
                    <thead
                        class="sticky top-0 z-10 bg-surface-alt text-xs uppercase tracking-wide
                           border-b border-outline dark:bg-surface-dark-alt dark:border-outline-dark
                           text-on-surface-strong dark:text-on-surface-dark-strong">
                        <tr>
                            <th class="px-4 py-3">Proses</th>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Jabatan</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Approved</th>
                            <th class="px-4 py-3">Tanggal</th>
                            <th class="px-4 py-3">Catatan</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-outline dark:divide-outline-dark">
                        @foreach ($rps->rpsApprovals as $row)
                            <tr class="hover:bg-surface-alt/60 dark:hover:bg-surface-dark-alt/60 transition">
                                <td class="px-4 py-3 font-mono text-xs text-muted-foreground">
                                    {{ $row->role_proses }}
                                </td>

                                <td class="px-4 py-3 font-medium">
                                    {{ $row->dosen?->name }}
                                </td>

                                <td class="px-4 py-3 text-muted-foreground">
                                    @php
                                        $jabatan = match ($row->role_proses) {
                                            'perumusan' => 'Dosen Pengampu',
                                            'pemeriksaan' => 'Kaprodi',
                                            'persetujuan' => 'Wadir 1',
                                            'penetapan' => 'Direktur',
                                            'pengendalian' => 'BPM',
                                        };
                                    @endphp
                                    {{ $jabatan }}
                                </td>

                                {{-- STATUS --}}
                                <td class="px-4 py-3 text-center">
                                    @php
                                        $status = match ($row->status) {
                                            'pending' => 'yellow',
                                            'rejected' => 'red',
                                            'approved' => 'green',
                                        };
                                    @endphp
                                    <flux:badge color="{{ $status }}" size="sm">
                                        {{ $row->status }}
                                    </flux:badge>
                                </td>

                                {{-- APPROVED --}}
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold">
                                        @php
                                            $approved = match ($row->approved) {
                                                true => 'Ya',
                                                false => 'Tidak',
                                            };
                                        @endphp
                                        {{ $approved }}
                                    </span>
                                </td>

                                {{-- TANGGAL --}}
                                <td class="px-4 py-3 font-mono text-xs text-muted-foreground">
                                    @if ($row->approved_at == null)
                                        -
                                    @else
                                        {{ \Carbon\Carbon::parse($row->approved_at)->format('d M Y') }}
                                    @endif
                                </td>

                                {{-- CATATAN --}}
                                <td class="px-4 py-3">
                                    @if ($row->catatan)
                                        <span class="line-clamp-1 text-muted-foreground">
                                            {{ $row->catatan }}
                                        </span>
                                    @else
                                        <span class="italic text-muted-foreground/60">
                                            —
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-center gap-2">

                                        {{-- SUBMIT (DOSEN) --}}
                                        @if ($this->canSubmit($row))
                                            <flux:button size="xs" variant="primary"
                                                wire:click="submit({{ $row->id }})">
                                                Submit
                                            </flux:button>
                                        @endif

                                        {{-- APPROVE / REJECT --}}
                                        @if ($this->canApprove($row))
                                            <flux:button size="xs" color="green" variant="primary"
                                                wire:click="submit({{ $row->id }})">
                                                Approve
                                            </flux:button>

                                            <flux:button size="xs" color="red" variant="ghost"
                                                wire:click="rejected({{ $row->id }})">
                                                Reject
                                            </flux:button>
                                        @endif

                                        {{-- READONLY --}}
                                        @if ($row->status !== 'pending')
                                            <span class="text-xs italic text-muted-foreground">
                                                —
                                            </span>
                                        @endif

                                    </div>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-card>
    <flux:modal name="rejectedRps" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rejected RPS</flux:heading>
            </div>
            <flux:textarea label="Catatan Penolakan" wire:model="catatan" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" wire:click="saveRejected" variant="primary">Submit</flux:button>
            </div>
        </div>
    </flux:modal>
    <x-card title="Identitas RPS">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-4 text-sm">

            {{-- KOLOM KIRI --}}
            <div class="space-y-3">
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Mata Kuliah</span>
                    <span class="font-medium text-gray-800 dark:text-gray-100">
                        {{ $indentitasMk->name }}
                    </span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Kode</span>
                    <span class="font-medium">{{ $indentitasMk->code }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">SKS</span>
                    <span class="font-medium">{{ $indentitasMk->sks }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Semester</span>
                    <span class="font-medium">{{ $indentitasMk->semester }}</span>
                </div>
            </div>

            {{-- KOLOM KANAN --}}
            <div class="space-y-3">
                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Program Studi</span>
                    <span class="font-medium">{{ $activeProdiName }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Dosen</span>
                    <span class="font-medium">{{ $activeDosenName }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Tahun Akademik</span>
                    <span class="font-medium">{{ $form['tahun_akademik'] }}</span>
                </div>

                <div class="flex justify-between gap-4">
                    <span class="text-gray-500 dark:text-gray-400">Revisi</span>
                    <span class="font-medium">{{ $form['revisi'] }}</span>
                </div>
            </div>

            {{-- DESKRIPSI --}}
            <div class="md:col-span-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300 leading-relaxed text-justify">
                    {{ $indentitasMk->description }}
                </p>
            </div>

        </div>
    </x-card>

    <x-card title="CPL Prodi yang Dibebankan">
        <ul class="space-y-4 text-sm">
            @foreach ($cpls as $cpl)
                <li class="flex items-start gap-4">
                    <span
                        class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold
                           bg-gray-200 text-gray-700
                           dark:bg-gray-700 dark:text-gray-100">
                        {{ $cpl->cpl->code }}
                    </span>

                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $cpl->cpl->description }}
                    </p>
                </li>
            @endforeach
        </ul>
    </x-card>

    <x-card title="Capaian Pembelajaran Mata Kuliah (CPMK)">
        <ul class="space-y-4 text-sm ">
            @foreach ($cpmks as $cpmk)
                <li class="flex items-start gap-4 border-b border-gray-200 dark:border-gray-700 py-2">
                    <span
                        class="shrink-0 px-2 py-0.5 rounded-md text-xs font-semibold
                           bg-blue-100 text-blue-700
                           dark:bg-blue-900 dark:text-blue-200">
                        {{ $cpmk->cpmk->code }}
                    </span>

                    <p class="w-11/12 text-gray-700 dark:text-gray-300 leading-relaxed">
                        {{ $cpmk->cpmk->description }}
                    </p>
                    <span
                        class="shrink-0 items-self-end px-2 py-0.5 rounded-md text-xs font-semibold
                           bg-blue-100 text-blue-700
                           dark:bg-blue-900 dark:text-blue-200">
                        Bobot : {{ $form['cpmks'][$cpmk->cpmk_id]['bobot'] }} %
                    </span>
                </li>
            @endforeach
        </ul>
    </x-card>
    <x-card title="Korelasi CPL Terhadap CPMK">

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
                                {{ $cpl->cpl->code }}
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($cpmks as $pivotCpmk)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                            <td
                                class="border border-gray-200 dark:border-gray-700 px-3 py-2 font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                                {{ $pivotCpmk->cpmk->code }}
                            </td>

                            @foreach ($cpls as $cpl)
                                <td class="border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center justify-center py-2">
                                        @if ($matriksCplCpmk['matrix'][$pivotCpmk->cpmk_id][$cpl->cpl_id])
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                                class="size-6 text-green-500">
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
    </x-card>
    <x-card title="Rencana Pembelajaran">
        <div class="space-y-6">
            <div
                class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
                <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                    <thead
                        class="border-b text-center border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                        <tr>
                            <th class="p-2 border">Pertemuan</th>
                            <th class="p-2 border">Materi Ajar</th>
                            <th class="p-2 border">CPMK Terkait</th>
                            @foreach ($cpmks as $cpmk)
                                <th class="p-2 border">Bobot {{ $cpmk->cpmk->code }}</th>
                            @endforeach
                            <th class="p-2 border">Indikator Penilaian</th>
                            <th class="p-2 border">Bentuk Pembelajaran</th>
                            <th class="p-2 border">Alokasi Waktu</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                        @foreach ($pertemuans as $pIndex => $pertemuan)
                            <tr>
                                <td class="p-4 border">{{ $pertemuan['pertemuan_ke'] }}</td>
                                <td class="p-4 border">{{ $pertemuan['materi_ajar'] }}</td>
                                <td class="p-4 border">
                                    {{ optional($cpmks->firstWhere('cpmk_id', $pertemuan['cpmk_id']))?->cpmk?->code ?? '-' }}
                                    {{-- {{ $pertemuan[$pIndex] }} --}}
                                </td>
                                @php
                                    $totalBobot = 0;
                                @endphp
                                @foreach ($cpmks as $cpmk)
                                    <td class="p-4 border">
                                        @if ($cpmk->cpmk_id == $pertemuan['cpmk_id'])
                                            @foreach ($pertemuan['bobots'] as $bobot)
                                                @php
                                                    $nilai = (int) $bobot['bobot'];
                                                    $totalBobot += $nilai;
                                                @endphp

                                                <div>
                                                    {{ $bobot['jenis'] }}: {{ $nilai }}
                                                </div>
                                            @endforeach

                                            <div class="font-semibold">
                                                Total: {{ $totalBobot }}
                                            </div>
                                        @else
                                            0
                                        @endif
                                    </td>
                                @endforeach

                                <td class="p-4 border">
                                    {{ $pertemuan['indikator'] }}
                                </td>
                                <td class="p-4 border">
                                    {{ $pertemuan['bentuk_pembelajaran'] }}
                                </td>
                                <td class="p-4 border">
                                    <div class="mb-3">
                                        @foreach ($pertemuan['alokasi'] as $a)
                                            <div>
                                                {{ $a['tipe'] }} : 1×{{ $a['jumlah'] }}×{{ $a['menit'] }}’
                                            </div>
                                        @endforeach
                                    </div>
                                    <span class="text-gray-700 dark:text-white mt-4">
                                        {{ $pertemuan['pemberian_tugas'] ? 'Ada Pemberian Tugas' : '' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot
                        class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                        <tr>
                            <th class="p-2 border" colspan="3">Total</th>
                            @php
                                $totalBobot = $this->totalBobotPerCpmk();
                            @endphp

                            @foreach ($cpmks as $cpmk)
                                <th class="p-2 border text-center">
                                    {{ $totalBobot[$cpmk->cpmk_id] ?? 0 }}
                                </th>
                            @endforeach
                            <th class="p-2 border"></th>
                            <th class="p-2 border"></th>
                            <th class="p-2 border"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </x-card>
    <x-card>
        {{-- HEADER --}}
        <div class="flex justify-between items-start">
            <div>
                <h3 class="text-lg font-semibold">
                    Beban Belajar Mahasiswa
                </h3>
                <p class="text-sm text-gray-500">
                    Perhitungan otomatis berdasarkan alokasi PB, PT, BM, dan asesmen
                </p>
            </div>

            <div class="text-right">
                <div class="text-xs text-gray-500">Total Waktu</div>
                <div class="text-2xl font-bold text-primary-600">
                    {{ number_format($this->totalMenitSemester()) }} menit
                </div>
            </div>
        </div>

        {{-- RUMUS RPS --}}
        <div class="mt-5 p-4 rounded-lg bg-gray-50 border text-sm font-mono text-gray-700">
            (
            {{ $rpsSummary['blok'] }} blok ×
            {{ $rpsSummary['jam_per_blok'] }} jam/blok ×
            {{ $rpsSummary['menit_per_jam'] }} menit
            )
            +
            (
            {{ $rpsSummary['asesmen'] }} kali asesmen ×
            {{ $rpsSummary['jam_asesmen'] }} ×
            {{ $rpsSummary['menit_per_jam'] }} menit
            )
            =
            <span class="font-semibold">
                {{ number_format($this->totalMenitSemester()) }} menit ≈ {{ $this->totalJamSemester() }} jam /
                semester
            </span>
        </div>

    </x-card>

    <x-card>
        <table class="w-full border border-gray-700 text-sm">
            <tr>
                <td class="w-1/4 border border-gray-700 p-2 font-semibold align-top">
                    Metode Pembelajaran
                </td>
                <td class="w-4 border border-gray-700 p-2 align-top">
                    :
                </td>
                <td class="border border-gray-700 p-2 text-justify leading-relaxed">
                    {{ $form['metode_pembelajaran'] }}
                </td>
            </tr>
            <tr>
                <td class="w-1/4 border border-gray-700 p-2 font-semibold align-top">
                    Daftar Referensi
                </td>
                <td class="w-4 border border-gray-700 p-2 align-top">
                    :
                </td>
                <td class="border border-gray-700 p-0">
                    <table class="w-full text-sm">
                        <tr>
                            <td class="w-1/2 border-r border-gray-700 p-2 align-top">
                                <div class="font-semibold mb-1">Utama</div>
                                <ol class="list-decimal list-inside space-y-1">
                                    @foreach ($referensi['utama'] as $ref)
                                        <li>{{ $ref }}</li>
                                    @endforeach
                                </ol>
                            </td>
                            <td class="p-2 align-top">
                                <div class="font-semibold mb-1">Pendukung</div>
                                <ol class="list-decimal list-inside space-y-1">
                                    @foreach ($referensi['pendukung'] as $ref)
                                        <li>{{ $ref }}</li>
                                    @endforeach
                                </ol>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="w-1/4 border border-gray-700 p-2 font-semibold align-middle">
                    Metode Penilaian dan Keselarasan dengan CPMK
                </td>

                <td colspan="2" class="border border-gray-700 p-2 text-justify leading-relaxed">
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-100 dark:bg-gray-800">
                            <tr>
                                <th class="p-2 border text-left">Teknik Penilaian</th>
                                <th class="p-2 border text-center w-20">%</th>
                                @foreach ($matriksCplCpmk['cpmk'] as $cpmk)
                                    <th class="p-2 border text-center">
                                        {{ $cpmk->cpmk->code }}
                                    </th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @foreach (['default' => 'Kelompok Default', 'kognitif' => 'Kelompok Kognitif'] as $group => $label)
                                {{-- TAMPILKAN HEADER HANYA JIKA BUKAN DEFAULT --}}
                                @if ($group !== 'default')
                                    <tr>
                                        <td colspan="{{ 2 + count($matriksCplCpmk['cpmk']) }}"
                                            class="px-3 py-2 text-center border bg-gray-50 dark:bg-gray-900 font-semibold text-gray-700 dark:text-gray-200">
                                            {{ $label }}
                                        </td>
                                    </tr>
                                @endif

                                @foreach ($kelompokPenilaian[$group] as $key)
                                    @php
                                        $totalCpmk = collect($penilaian[$key]['cpmk'] ?? [])->sum();
                                        $sisa = $penilaian[$key]['persentase'] - $totalCpmk;
                                    @endphp

                                    <tr>
                                        {{-- NAMA TEKNIK --}}
                                        <td class="p-2 border capitalize">
                                            {{ str_replace('_', ' ', $key) }}
                                            <div class="text-xs text-gray-500">
                                                Sisa: {{ $sisa }}%
                                            </div>
                                        </td>

                                        {{-- TOTAL PERSENTASE --}}
                                        <td class="p-2 border text-center font-medium">
                                            {{ $penilaian[$key]['persentase'] }}%
                                        </td>

                                        {{-- CPMK --}}
                                        @foreach ($matriksCplCpmk['cpmk'] as $cpmkId => $cpmk)
                                            <td class="p-2 border text-center">
                                                {{ $penilaian[$key]['cpmk'][$cpmkId] ?? 0 }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>
                        @php
                            $totalCpmk = [];

                            foreach ($matriksCplCpmk['cpmk'] as $cpmkId => $cpmk) {
                                $totalCpmk[$cpmkId] = collect($penilaian)->sum(
                                    fn($item) => $item['cpmk'][$cpmkId] ?? 0,
                                );
                            }
                        @endphp
                        {{-- FOOTER TOTAL --}}
                        <tfoot class="bg-gray-50 dark:bg-gray-800 font-semibold">
                            <tr>
                                <td class="p-2 border">Total</td>
                                <td class="p-2 border text-center">
                                    {{ collect($penilaian)->sum('persentase') }} %
                                </td>
                                @foreach ($matriksCplCpmk['cpmk'] as $cpmkId => $cpmk)
                                    <td class="p-2 border text-center">
                                        {{ $totalCpmk[$cpmkId] }} %
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
        </table>
    </x-card>
</div>
