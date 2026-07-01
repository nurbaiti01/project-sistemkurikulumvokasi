@extends('pdf.layouts.a4')
{{-- ganti ke letter jika perlu --}}
@push('styles')
    <style>
        @page {
            margin: 90px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11px;
            color: #000;
        }

        .title {
            text-align: center;
            font-weight: bold;
            line-height: 1.6;
            margin-bottom: 25px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            vertical-align: top;
        }

        th {
            background-color: #d9d9d9;
            text-align: center;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .signature {
            height: 10px;
            vertical-align: middle;
            text-align: center;
        }

        .note {
            margin-top: 20px;
            font-size: 10px;
        }
    </style>
@endpush
@section('title', 'Laporan CPMK')

@section('content')
    <div class="title">
        LEMBAR PENGESAHAN<br>
        RENCANA PEMBELAJARAN SEMESTER (RPS)<br>
        MATA KULIAH {{ $cover['nama_mk'] }} ({{ $cover['kode_mk'] }})<br>
        SEMESTER {{ $cover['semester'] }} TAHUN AKADEMIK {{ $cover['tahun_akademik'] }}
    </div>

    {{-- TABEL PENGESAHAN --}}
    <table>
        <thead>
            <tr>
                <th width="12%">Proses</th>
                <th colspan="3">Penanggung Jawab</th>
                <th width="15%">Tanggal</th>
            </tr>
            <tr>
                <th></th>
                <th width="25%">Nama</th>
                <th width="28%">Jabatan</th>
                <th width="20%">Tanda Tangan</th>
                <th></th>
            </tr>
        </thead>

        <tbody>
            @foreach ($approvals as $item)
                <tr>
                    <td class="center" style="text-transform: capitalize;">{{ $item->role_proses }}</td>
                    <td>{{ $item->dosen?->name }}</td>
                    <td>
                        @php
                            $jabatan = match ($item->role_proses) {
                                'perumusan' => 'Dosen Pengampu',
                                'pemeriksaan' => 'Kaprodi '.$cover['program_studi'],
                                'persetujuan' => 'Wadir 1',
                                'penetapan' => 'Direktur',
                                'pengendalian' => 'BPM',
                            };
                        @endphp
                        {{ $jabatan }}
                    </td>
                    <td class="signature">
                        @if ($item->approved)
                            <img src="{{ public_path('images/check.png') }}"
                                alt="Signature" style="width:24px; height:auto;">
                        @endif
                    </td>
                    <td class="center">{{ \Carbon\Carbon::parse($item->approved_at)->isoFormat('DD MMMM YYYY') ?? $item->approved_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection
