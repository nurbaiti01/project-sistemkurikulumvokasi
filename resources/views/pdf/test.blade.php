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
            padding: 2px 6px;
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
            width: 95%;
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
@section('title', 'Laporan CPMK')

@section('header')
    @include('pdf.partials.header')
@endsection

@section('content')
    <h1 style="text-align: center;font-size:14px;margin-top:5%;text-transform:uppercase">Kontrak Perkuliahan</h1>
    <div style="padding:25px">
        <table class="info-table">
            <tr>
                <td class="info-label">Nama Mata Kuliah</td>
                <td class="info-separator">:</td>
                <td class="info-value">Pemrograman Berorientasi Objek</td>
            </tr>
            <tr>
                <td class="info-label">Kode Mata Kuliah</td>
                <td class="info-separator">:</td>
                <td class="info-value">IF204</td>
            </tr>
            <tr>
                <td class="info-label">Bobot SKS</td>
                <td class="info-separator">:</td>
                <td class="info-value">1 SKS</td>
            </tr>
            <tr>
                <td class="info-label">Program Studi</td>
                <td class="info-separator">:</td>
                <td class="info-value">Teknik Informatika</td>
            </tr>
            <tr>
                <td class="info-label">Semester / Kelas</td>
                <td class="info-separator">:</td>
                <td class="info-value">3 / 2A</td>
            </tr>
            <tr>
                <td class="info-label">Total Jam Pelajaran</td>
                <td class="info-separator">:</td>
                <td class="info-value">15</td>
            </tr>
            <tr>
                <td class="info-label">Dosen</td>
                <td class="info-separator">:</td>
                <td class="info-value">Fenty Kurnia Oktorina</td>
            </tr>
        </table>

        <div class="section">
            <div class="section-title">1. Deskripsi Mata Kuliah</div>
            <div class="section-content">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et
                dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex
                ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat
                nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit
                anim id est laborum.
            </div>
        </div>

        <div class="section">
            <div class="section-title">2. Tujuan Pembelajaran</div>
            <div class="section-content">
                Setelah mengikuti mata kuliah ini, mahasiswa diharapkan mampu menerapkan konsep-konsep
                dasar dalam pemrograman berbasis objek ke dalam bahasa pemrograman PHP secara sederhana.
            </div>
        </div>

        <div class="section">
            <div class="section-title">3. Capaian Pembelajaran Mata Kuliah</div>
            <ol class="section-list" type="a">
                <li>
                    Mahasiswa mampu menjelaskan konsep-konsep dasar dalam pemrograman berorientasi
                    objek seperti kelas, objek, metode, serta hubungan antara kelas dan objek.
                </li>
                <li>
                    Mahasiswa mampu mengimplementasikan konsep-konsep dasar pemrograman berorientasi
                    objek ke dalam bahasa pemrograman PHP secara sederhana.
                </li>
            </ol>
        </div>

        <div class="section">
            <div class="section-title">4. Strategi Perkuliahan</div>
            <div class="section-content">
                Kombinasi <i>Student Centered Learning</i>, pendekatan Konstruktivistik, dan metode Ceramah.
            </div>
        </div>

    </div>


@endsection
