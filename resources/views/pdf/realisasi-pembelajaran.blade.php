@extends('pdf.layouts.a4')
{{-- ganti ke letter jika perlu --}}
@push('styles')
    <style>
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
@section('title', 'Laporan Realisasi Pembelajaran')

@section('header')
    @include('pdf.partials.header')
@endsection

@section('content')

    <h1 style="text-align: center;font-size:14px;margin-top:0;text-transform:uppercase">Realisasi Pengajaran Matakuliah</h1>
    <div style="padding:0;">
        <table width="100%" cellpadding="0" cellspacing="0"
            style="
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            line-height: 1.2;
            color: #000;
        ">

            {{-- HEADER INFO --}}
            <tr>
                <td colspan="2" style="border:1px solid #444; padding:6px; vertical-align:top;">
                    <strong>Program Studi</strong><br>
                    {{ $realisasi['program_studi'] }}
                </td>

                <td style="border:1px solid #444; padding:6px; vertical-align:top;" width="40%">
                    <strong>Kode / Nama Mata Kuliah</strong><br>
                    {{ $realisasi['kode_mk'] }} / {{ $realisasi['nama_mk'] }}
                </td>

                <td style="border:1px solid #444; padding:6px; vertical-align:top; text-align:center;">
                    <strong>SKS</strong><br>
                    {{ $realisasi['jumlah_sks'] }}
                </td>

                <td style="border:1px solid #444; padding:6px; vertical-align:top; text-align:center;">
                    <strong>Semester</strong><br>
                    {{ $realisasi['semester'] }}
                </td>

                <td style="border:1px solid #444; padding:6px; vertical-align:top; text-align:center;">
                    <strong>Tahun Ajaran</strong><br>
                    {{ $realisasi['tahun_akademik'] }}
                </td>
            </tr>

            {{-- SUB HEADER --}}
            <tr style="">
                <td colspan="2" style="border:1px solid #444; padding:6px; vertical-align:top;height: 40px">
                    <strong>Tujuan Instruksional Umum (TIU)</strong><br>
                </td>

                <td colspan="4" style="border:1px solid #444; padding:6px; vertical-align:top;">
                    {{ $realisasi['tujuan_instruksional_umum'] }}

                </td>
            </tr>

            {{-- TABLE HEAD --}}
            <tr style="background-color:#f2f2f2;">
                <td style="border:1px solid #444; padding:6px; text-align:center; font-weight:bold;">
                    Pertemuan
                </td>
                <td style="border:1px solid #444; padding:6px; text-align:center; font-weight:bold;">
                    Tanggal
                </td>
                <td style="border:1px solid #444; padding:6px; font-weight:bold;">
                    Pokok Bahasan
                </td>
                <td colspan="2" style="border:1px solid #444; padding:6px; text-align:center; font-weight:bold;">
                    Jam
                </td>
                <td style="border:1px solid #444; padding:6px; text-align:center; font-weight:bold;">
                    Paraf
                </td>
            </tr>

            {{-- TABLE BODY --}}
            @foreach ($pertemuans as $a => $b)
                <tr>
                    <td style="border:1px solid #444; padding:5px; text-align:center;">
                        {{ $b['pertemuan_ke'] }}
                    </td>

                    <td style="border:1px solid #444; padding:5px; text-align:center;">
                        {{ $b['tanggal'] }}
                    </td>

                    <td style="border:1px solid #444; padding:5px;">
                        {{ $b['pokok_bahasan'] }}
                    </td>

                    <td colspan="2" style="border:1px solid #444; padding:5px; text-align:center;">
                        {{ $b['jam'] }}
                    </td>

                    <td style="border:1px solid #444; padding:5px; text-align:center;">
                        @if ($b['paraf'])
                            <img src="{{ public_path('images/check.png') }}" alt="Paraf Dosen"
                                style="height:24px; width:24px;" />
                        @else
                            __________
                        @endif
                    </td>
                </tr>
            @endforeach
            <tfoot>
                {{-- METODE PEMBELAJARAN --}}
                <tr>
                    <td colspan="2" style="border:1px solid #444; padding:6px; vertical-align:top; font-weight:bold;">
                        Metode Pembelajaran
                    </td>

                    <td colspan="4" style="border:1px solid #444; padding:6px; vertical-align:top;">
                        <table width="100%" cellpadding="0" cellspacing="0"
                            style="border-collapse:collapse; font-size:10.5px;">
                            <tr>
                                @foreach ($metodes as $metode)
                                    <td style="padding:4px;">{{ $metode->jenis }} : {{ $metode->jam }} Jam</td>
                                @endforeach

                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- METODE EVALUASI --}}
                <tr>
                    <td colspan="2" style="border:1px solid #444; padding:6px; vertical-align:top; font-weight:bold;">
                        Metode Evaluasi
                    </td>

                    <td colspan="4" style="border:1px solid #444; padding:6px; vertical-align:top;">
                        <table width="100%" cellpadding="0" cellspacing="0"
                            style="border-collapse:collapse; font-size:10.5px;">
                            <tr>
                                <td style="padding:4px;">Tugas-tugas : {{ intval($evaluasi->tugas_persen) }} %</td>
                                <td style="padding:4px;">Tes Singkat (Quiz) : {{ intval($evaluasi->kuis_persen) }} %</td>
                                <td style="padding:4px;">Ujian : {{ intval($evaluasi->ujian_persen) }} %</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                {{-- REFERENSI --}}
                <tr>
                    <td colspan="2" style="border:1px solid #444; padding:6px; vertical-align:top; font-weight:bold;">
                        Diklat / Modul / Buku Referensi
                    </td>

                    <td colspan="4" style="border:1px solid #444; padding:6px; vertical-align:top;">
                        <table width="100%" cellpadding="0" cellspacing="0"
                            style="border-collapse:collapse; font-size:10.5px;">
                            @foreach ($referensis as $index => $item)
                                <tr>
                                    {{-- <td style="padding:4px;">{{ $index + 1 }}.</td> --}}
                                    <td style="padding:4px;">
                                        {{ $index + 1 }}. Judul : {{ $item->judul }}
                                    </td>
                                    <td style="padding:4px;">
                                        Penerbit : {{ $item->penerbit }}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </td>
                </tr>
            </tfoot>

        </table>
    </div>



@endsection
