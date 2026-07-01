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
                {{ number_format($totalMenitSemester) }} menit
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
            {{ number_format($totalMenitSemester) }} menit ≈ {{ $totalJamSemester }} jam /
            semester
        </span>
    </div>

</x-card>
