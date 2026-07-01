@extends('pdf.layouts.a4')
{{-- ganti ke letter jika perlu --}}
@push('styles')
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12px;
            text-align: center;
            color: #000;
        }
        .cover-wrapper {
            width: 100%;
            /* height: 100%; */
            /* position: relative; */
        }

        .logo {
            margin-top: 80px;
        }

        .logo img {
            width: 140px;
            height: auto;
        }

        .title {
            margin-top: 50px;
            font-weight: bold;
            font-size: 14px;
        }

        .subtitle {
            margin-top: 40px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .content {
            margin-top: 20px;
            line-height: 1.8;
            font-size: 12px;
        }

        .footer {
            position: absolute;
            bottom: 150px;
            width: 100%;
            font-weight: bold;
            line-height: 1.6;
            font-size: 14px;
        }

        .year {
            margin-top: 15px;
            font-size: 14px;
        }
    </style>
@endpush
@section('title', 'Laporan CPMK')

@section('content')
    <div class="cover-wrapper">

        {{-- LOGO --}}
        <div class="logo">
            <img src="{{ public_path('images/logo-polkam-2.png') }}" alt="Logo Politeknik Kampar">
        </div>

        {{-- JUDUL --}}
        <div class="title">
            RENCANA PEMBELAJARAN SEMESTER (RPS)
        </div>

        <div class="subtitle">
            SEMESTER {{ $cover['semester'] }} TAHUN AKADEMIK {{ $cover['tahun_akademik'] }}
        </div>

        {{-- INFO MATA KULIAH --}}
        <div class="content">
            <div><strong>MATA KULIAH : {{ $cover['nama_mk'] }}</strong> </div>
            <div><strong>KODE MK : {{ $cover['kode_mk'] }}</strong> </div>
        </div>

        {{-- FOOTER --}}
        <div class="footer">
            <div>
                PROGRAM STUDI {{ $cover['program_studi'] }}
            </div>
            <div>
                POLITEKNIK KAMPAR
            </div>

            <div class="year">
                {{ $cover['tahun_akademik'] }}
            </div>
        </div>

    </div>
@endsection
