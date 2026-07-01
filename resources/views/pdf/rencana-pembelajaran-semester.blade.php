@extends('pdf.layouts.a4')
{{-- ganti ke letter jika perlu --}}
@push('styles')
    <style>
        @page {
            margin: 30px 30px 60px 30px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-label {
            width: 25%;
            font-weight: bold;
        }

        .info-separator {
            width: 2%;
            text-align: center;
        }

        .info-value {
            width: 73%;
        }

        .section {
            margin-top: 12px;
            font-size: 11px;
            line-height: 1.6;
        }

        .section-title {
            font-weight: bold;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .section-content {
            text-align: justify;
            margin-bottom: 8px;
            padding-left: 12px
        }

        .section-list {
            margin: 4px 0 8px 23px;
            padding-left: 0;
        }

        .section-list li {
            margin-bottom: 4px;
            text-align: justify;
        }
    </style>
@endpush
@section('title', 'Laporan CPMK')


@section('content')
    <div style="padding-left: 25px;padding-right:25px">
        @include('pdf.partials.header-rps')
    </div>
    <h1 style="text-align: center;font-size:14px;margin-top:10px;text-transform:uppercase">RENCANA PEMBELAJARAN SEMESTER
        (RPS)
    </h1>
    <div style="padding-left: 25px;padding-right:25px">

        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
        color: #000;
    ">

            {{-- ================= IDENTITAS ================= --}}
            <tr>
                <td colspan="5" style="border:0.6px solid #555; padding:6px; font-weight:bold;">
                    IDENTITAS MATA KULIAH
                </td>
            </tr>

            <tr>
                <td width="18%" style="border:0.6px solid #555; padding:5px;">Mata Kuliah</td>
                <td width="2%" style="border:0.6px solid #555; padding:5px; text-align:center;">:</td>
                <td width="30%" style="border:0.6px solid #555; padding:5px;">
                    {{ $matakuliah['nama_mk'] }}
                </td>
                <td width="18%" style="border:0.6px solid #555; padding:5px;">Kode Mata Kuliah</td>
                <td width="32%" style="border:0.6px solid #555; padding:5px;">
                    : {{ $matakuliah['kode_mk'] }}
                </td>
            </tr>

            <tr>
                <td style="border:0.6px solid #555; padding:5px;">Jumlah SKS</td>
                <td style="border:0.6px solid #555; padding:5px; text-align:center;">:</td>
                <td style="border:0.6px solid #555; padding:5px;">{{ $matakuliah['jumlah_sks'] }}</td>
                <td style="border:0.6px solid #555; padding:5px;">Semester</td>
                <td style="border:0.6px solid #555; padding:5px;">: {{ $matakuliah['semester'] }}</td>
            </tr>

            <tr>
                <td style="border:0.6px solid #555; padding:5px;">Deskripsi Mata Kuliah</td>
                <td style="border:0.6px solid #555; padding:5px; text-align:center;">:</td>
                <td colspan="3" style="border:0.6px solid #555; padding:6px; text-align:justify;">
                    {{ $matakuliah['description'] }}
                </td>
            </tr>

            {{-- ================= CAPAIAN ================= --}}
            <tr>
                <td colspan="5" style="border:0.6px solid #555; padding:6px; font-weight:bold;">
                    CAPAIAN PEMBELAJARAN LULUSAN
                </td>
            </tr>

            <tr>
                <td colspan="5" style="border:0.6px solid #555; padding:5px; font-weight:bold;">
                    A.&nbsp;&nbsp;CPL Prodi yang Dibebankan pada MK
                </td>
            </tr>
            @foreach ($cpl_cpmk_matrix['cpl'] as $cpl)
                <tr>
                    <td width="18%" style="border:0.6px solid #555; padding:5px;">{{ $cpl['code'] }}</td>
                    <td width="2%" style="border:0.6px solid #555; padding:5px; text-align:center;">:</td>
                    <td colspan="3" style="border:0.6px solid #555; padding:5px; text-align:justify;">
                        {{ $cpl['label'] }}
                    </td>
                </tr>
            @endforeach
        </table>
        {{-- ================= TABEL CPMK ================= --}}
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
    ">

            <tr>
                <td colspan="2" style="border:0.6px solid #555; padding:6px; font-weight:bold;">
                    B.&nbsp;&nbsp;Capaian Pembelajaran
                </td>
                <td colspan="" style="border:0.6px solid #555; padding:6px; font-weight:bold;">
                    bobot
                </td>
            </tr>
            @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                <tr>
                    <td width="18%" style="border:0.6px solid #555; padding:6px;">
                        {{ $cpmk['code'] }}
                    </td>
                    <td width="72%" style="border:0.6px solid #555; padding:6px; text-align:justify;">
                        {{ $cpmk['label'] }}
                    </td>
                    <td width="10%" style="border:0.6px solid #555; padding:6px; text-align:center;">
                        {{ $identitas['cpmk_bobot'][$cpmk['id']]['bobot'] }}%
                    </td>
                </tr>
            @endforeach
        </table>

        <br>
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
    ">
            <tr>
                <td style="border:0.6px solid #555; padding:6px; font-weight:bold; text-align:center;">
                    Korelasi CPL terhadap CPMK
                </td>
            </tr>
        </table>
        {{-- ================= KORELASI CPL vs CPMK ================= --}}
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
    ">
            <tr>
                <td width="25%" style="border:0.6px solid #555; padding:6px; font-weight:bold; text-align:center;">
                    CPMK
                </td>
                @foreach ($cpl_cpmk_matrix['cpl'] as $cpl)
                    <td width="25%" style="border:0.6px solid #555; padding:6px; text-align:center;">
                        {{ $cpl['code'] }}
                    </td>
                @endforeach

            </tr>
            @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                <tr>
                    <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                        {{ $cpmk['code'] }}
                    </td>
                    @foreach ($cpl_cpmk_matrix['cpl'] as $cpl)
                        <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                            @if ($cpl_cpmk_matrix['matrix'][$cpmk['id']][$cpl['id']])
                                <img src="{{ public_path('images/check-2.png') }}" alt="Signature"
                                    style="width:14px; height:auto;">
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </table>

        <br>
        <br>
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 10px;
        line-height: 1.25;
        color: #000;
    ">

            {{-- ================= JUDUL ================= --}}
            <tr>
                <td colspan="8" style="border:0.6px solid #555; padding:6px; font-weight:bold; text-align:center;">
                    Kaitan CPMK dengan Materi dan Bentuk Pembelajaran, serta Alokasi Waktu
                </td>
            </tr>

            {{-- ================= HEADER ================= --}}
            <tr>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Pertemuan
                </td>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Materi Ajar
                </td>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    CPMK
                </td>
                <td colspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Bobot CPMK (%)
                </td>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Indikator Penilaian
                </td>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Bentuk Pembelajaran
                </td>
                <td rowspan="2" style="border:0.6px solid #555; padding:5px; text-align:center;">
                    Alokasi Waktu
                </td>
            </tr>

            <tr>
                @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                    <td style="border:0.6px solid #555; padding:5px; text-align:center;">
                        {{ $cpmk['code'] }}
                    </td>
                @endforeach
            </tr>

            {{-- ================= BODY (SAMPLING) ================= --}}
            @foreach ($pertemuans as $i => $pertemuan)
                <tr>
                    <td style="border:0.6px solid #555; padding:5px; text-align:center;">
                        {{ $pertemuan['pertemuan_ke'] }}
                    </td>

                    <td width="25%" style="border:0.6px solid #555; padding:5px; text-align:justify;">
                        {{ $pertemuan['materi_ajar'] }}
                    </td>

                    <td style="border:0.6px solid #555; padding:5px; text-align:center;">
                        {{ $pertemuan['cpmk']['code'] }}
                    </td>
                    @php
                        $totalBobot = 0;
                    @endphp
                    @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                        <td style="border:0.6px solid #555; padding:5px; text-align:center;">
                            @if ($pertemuan['cpmk']['id'] == $cpmk['id'])
                                @foreach ($pertemuan['bobot_cpmk'] as $bobot)
                                    @php
                                        $nilai = (int) $bobot['bobot'];
                                        $totalBobot += $nilai;
                                    @endphp

                                    <div>
                                        {{ $bobot['jenis'] }}: {{ $nilai }}
                                    </div>
                                @endforeach

                                <div>
                                    Total: {{ $totalBobot }}
                                </div>
                            @else
                                0
                            @endif
                        </td>
                    @endforeach

                    <td style="border:0.6px solid #555; padding:5px; text-align:justify;">
                        {{ $pertemuan['indikator_penilaian'] }}
                    </td>

                    <td style="border:0.6px solid #555; padding:5px;">
                        {{ $pertemuan['bentuk_pembelajaran'] }}
                    </td>

                    <td style="border:0.6px solid #555; padding:5px;">
                        @foreach ($pertemuan['alokasi_waktu'] as $alokasi)
                            <div>
                                {{ $alokasi['tipe'] }}: 1 x {{ $alokasi['jumlah'] }} x {{ $alokasi['menit'] }} `
                            </div>
                        @endforeach
                    </td>
                </tr>
            @endforeach

            {{-- ================= TOTAL ================= --}}
            <tr>
                <td colspan="3" style="border:0.6px solid #555; padding:6px; font-weight:bold; text-align:center;">
                    TOTAL
                </td>
                @php
                    $grandTotalBobot = 0;
                @endphp
                @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                    @php
                        $totalCpmk = 0;
                    @endphp

                    <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                        @if ($pertemuan['cpmk']['id'] == $cpmk['id'])
                            @foreach ($pertemuan['bobot_cpmk'] as $bobot)
                                @php
                                    $nilai = (int) $bobot['bobot'];
                                    $totalCpmk += $nilai;
                                @endphp
                            @endforeach

                            @php
                                $grandTotalBobot += $totalCpmk;
                            @endphp

                            {{ $totalCpmk }}
                        @else
                            0
                        @endif
                    </td>
                @endforeach

                {{-- GRAND TOTAL --}}
                <td colspan="3" style="border:0.6px solid #555; padding:6px; font-weight:bold; text-align:center;">
                </td>
            </tr>

        </table>
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
        color: #000;
    ">
            @php
                $totalMenit = 0;

                foreach ($pertemuans as $pertemuan) {
                    foreach ($pertemuan['alokasi_waktu'] as $alokasi) {
                        $totalMenit += (int) $alokasi['jumlah'] * (int) $alokasi['menit'];
                    }
                }

                $totalJam = round($totalMenit / 60, 2);
            @endphp
            {{-- ================= BEBAN BELAJAR ================= --}}
            <tr>
                <td width="40%" style="border:0.6px solid #555; padding:6px;">
                    Beban belajar mahasiswa selama satu semester
                </td>
                <td width="60%" style="border:0.6px solid #555; padding:6px;">
                    (5 blok × 6 jam pertemuan/blok × 170 menit) +
                    (2 kali asesmen × 2 × 170 menit)
                    = {{ number_format($totalMenit, 0, ',', '.') }} menit
                    ({{ $totalJam }} jam / semester)
                </td>
            </tr>

            {{-- KETERANGAN --}}
            <tr>
                <td colspan="2" style="border:none; padding:6px 0;">
                    <strong>PB</strong> : Proses Belajar<br>
                    <strong>PT</strong> : Penugasan Terstruktur<br>
                    <strong>BM</strong> : Belajar Mandiri
                </td>
            </tr>
        </table>
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
        border-collapse: collapse;
        font-family: 'Times New Roman', Times, serif;
        font-size: 11px;
        line-height: 1.3;
        color: #000;
    ">
            {{-- ================= METODE PEMBELAJARAN ================= --}}
            <tr>
                <td style="border:0.6px solid #555; padding:6px;">
                    Metode Pembelajaran
                </td>
                <td style="border:0.6px solid #555; padding:6px; text-align:justify;">
                    {{ $identitas['learning_method'] }}
                </td>
            </tr>

            {{-- ================= PENGALAMAN BELAJAR ================= --}}
            <tr>
                <td style="border:0.6px solid #555; padding:6px;">
                    Pengalaman Belajar Mahasiswa
                </td>
                <td style="border:0.6px solid #555; padding:6px; text-align:justify;">
                    {{ $identitas['learning_experience'] }}
                </td>
            </tr>

            {{-- ================= REFERENSI ================= --}}
            <tr>
                <td style="border:0.6px solid #555; padding:6px;">
                    Daftar Referensi
                </td>
                <td style="border:0.6px solid #555; padding:6px;">
                    @foreach ($referensis as $referensi)
                        <strong>{{ $referensi['jenis'] }}</strong> : {{ $referensi['deskripsi'] }}<br>
                    @endforeach
                </td>
            </tr>

            {{-- ================= METODE PENILAIAN ================= --}}
            <tr>
                <td rowspan="" style="border:0.6px solid #555; padding:6px; vertical-align:top;">
                    Metode Penilaian dan Keselarasan dengan CPMK
                </td>
                <td style="border:0.6px solid #555; padding:0;">
                    <table width="100%" cellpadding="0" cellspacing="0"
                        style="border-collapse:collapse; font-size:11px;">
                        <tr>
                            <td style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                Teknik Penilaian
                            </td>
                            <td style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                Persentase (%)
                            </td>
                            @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                                <td style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                    {{ $cpmk['code'] }}
                                </td>
                            @endforeach
                        </tr>

                        @foreach ($penilaians->where('kelompok', 'default') as $row)
                            <tr>
                                <td style="border:0.6px solid #555; padding:6px;">
                                    {{ ucwords(str_replace('_', ' ', $row['jenis'])) }}
                                </td>

                                <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                                    {{ $row['persentase'] }}
                                </td>

                                @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                                    <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                                        {{ $row['cpmk'][$cpmk['id']] ?? 0 }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                        <tr>
                            <td colspan="{{ 2 + count($cpl_cpmk_matrix['cpmk']) }}"
                                style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                Kognitif
                            </td>
                        </tr>

                        @foreach ($penilaians->where('kelompok', 'kognitif') as $row)
                            <tr>
                                <td style="border:0.6px solid #555; padding:6px;">
                                    {{ strtoupper($row['jenis']) }}
                                </td>

                                <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                                    {{ $row['persentase'] }}
                                </td>

                                @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                                    <td style="border:0.6px solid #555; padding:6px; text-align:center;">
                                        {{ $row['cpmk'][$cpmk['id']] ?? 0 }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach


                        @php
                            $totalPersen = $penilaians->sum('persentase');
                        @endphp

                        <tr>
                            <td style="border:0.6px solid #555; padding:6px; font-weight:bold;">Total</td>
                            <td style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                {{ $totalPersen }}
                            </td>

                            @foreach ($cpl_cpmk_matrix['cpmk'] as $cpmk)
                                <td style="border:0.6px solid #555; padding:6px; text-align:center; font-weight:bold;">
                                    {{ $penilaians->sum(fn($p) => $p['cpmk'][$cpmk['id']] ?? 0) }}
                                </td>
                            @endforeach
                        </tr>

                    </table>
                </td>
            </tr>

        </table>

    </div>

@endsection
