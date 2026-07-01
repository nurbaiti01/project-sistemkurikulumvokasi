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
            padding: 2px 3px;
            vertical-align: top;
        }

        .info-label {
            width: 20%;
            font-weight: bold;
        }

        .info-separator {
            width: 1%;
            text-align: center;
        }

        .info-value {
            width: 73%;
        }

        .section {
            margin-top: 12px;
            font-size: 12px;
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
@section('title', 'Kontrak Perkuliahan')

@section('header')
    @include('pdf.partials.header')
@endsection

@section('content')
    <h1 style="text-align: center;font-size:14px;margin-top:0%;text-transform:uppercase">Kontrak Perkuliahan</h1>
    <div style="padding:55px">
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Mata Kuliah</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->matakuliah->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Kode Mata Kuliah</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->matakuliah->code }}</td>
            </tr>
            <tr>
                <td class="info-label">Bobot SKS</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->matakuliah->sks }}</td>
            </tr>
            <tr>
                <td class="info-label">Program Studi</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->programStudis->name }}</td>
            </tr>
            <tr>
                <td class="info-label">Semester / Kelas</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->matakuliah->semester }} / {{ $kontrak->kelas }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Jam Pelajaran</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->total_jam }}</td>
            </tr>
            <tr>
                <td class="info-label">Dosen</td>
                {{-- <td class="info-separator">:</td> --}}
                <td class="info-value">: {{ $kontrak->dosen->name }}</td>
            </tr>
        </table>

        <div class="section">
            <div class="section-title">1. Deskripsi Mata Kuliah</div>
            <div class="section-content">
                {{ $kontrak->matakuliah->description }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">2. Tujuan Pembelajaran</div>
            <div class="section-content">
                {{ strip_tags($kontrak->tujuan_pembelajaran) }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">3. Capaian Pembelajaran Mata Kuliah</div>
            <ol class="section-list" type="a">
                @foreach ($kontrak->cpmk as $cpmk)
                    <li>
                        <strong>{{ $cpmk['code'] }}</strong> — {{ $cpmk['label'] }}
                    </li>
                @endforeach
            </ol>
        </div>

        <div class="section">
            <div class="section-title">4. Strategi Perkuliahan</div>
            <div class="section-content">
                {{ strip_tags($kontrak->strategi_perkuliahan) }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">5. Organisasi Materi</div>
            <div class="section-content">
                {{ strip_tags($kontrak->materi_pembelajaran) }}
            </div>
        </div>
        <div class="section">
            <div class="section-title">6. Kriteria dan Standar Penilaian</div>
            <div class="section-content">
                {{ strip_tags($kontrak->kriteria_penilaian) }}
            </div>
        </div>

        <div class="section">
            <div class="section-title">7. Tata Tertib Perkuliahan</div>
            <div class="section-content">
                {{ strip_tags($kontrak->tata_tertib) }}
            </div>
        </div>

        <div class="section">
            <div class="section-content">
                Kontrak perkuliahan ini dilaksanakan mulai dari disampaikan kesepakatan ini
            </div>
        </div>
    </div>

    <table width="100%" cellpadding="0" cellspacing="0"
        style="margin-top:0px; font-family: Arial, Helvetica, sans-serif; font-size: 12px;">

        {{-- BARIS JUDUL PIHAK --}}
        <tr>
            <td width="50%" align="center" style="font-weight:bold;">
                Pihak I
            </td>
            <td width="50%" align="center" style="font-weight:bold;">
                Pihak II
            </td>
        </tr>

        {{-- BARIS SUB JUDUL --}}
        <tr>
            <td align="center" style="padding-top:4px;">
                Dosen Pengampu
            </td>
            <td align="center" style="padding-top:4px;">
                an. Mahasiswa
            </td>
        </tr>

        {{-- SPASI TTD --}}
        <tr>
            <td colspan="2" style="height:80px;"></td>
        </tr>

        {{-- NAMA PIHAK --}}
        <tr>
            <td align="center" style="font-weight:bold;">
                ({{ $kontrak->dosen->name }})
            </td>
            <td align="center" style="font-weight:bold;">
                ( ____________________ )
            </td>
        </tr>

        {{-- SPASI KE MENGETAHUI --}}
        <tr>
            <td colspan="2" style="height:40px;"></td>
        </tr>

        {{-- MENGETAHUI --}}
        <tr>
            <td colspan="2" align="center" style="font-weight:bold;">
                Mengetahui
            </td>
        </tr>

        <tr>
            <td colspan="2" align="center" style="padding-top:4px;">
                Ketua Program Studi Teknik Informatika
            </td>
        </tr>

        {{-- SPASI TTD KAPRODI --}}
        <tr>
            <td colspan="2" style="height:80px;"></td>
        </tr>

        {{-- NAMA KAPRODI --}}
        <tr>
            <td colspan="2" align="center" style="font-weight:bold;">
                ( ____________________ )
            </td>
        </tr>
    </table>
@endsection
