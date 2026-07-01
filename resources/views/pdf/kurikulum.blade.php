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
            margin-top: 20px;
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

        .obe-layout {
            width: 100%;
            border-collapse: collapse;
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
        }

        .obe-layout td {
            padding: 8px;
            border: 1px solid #666;
            vertical-align: top;
        }

        /* LEVEL WARNA */
        .cpl td {
            background: #8c8c8c;
            color: #000;
            /* font-weight: bold; */
        }

        .pl td {
            background: #f4a3a8;
        }

        .bk td {
            background: #c7d7e7;
            font-weight: bold;
        }

        .mk td {
            background: #5f86b3;
            color: #000;
            font-weight: bold;
        }

        .cpmk td {
            background: #7fbf6a;
            font-weight: bold;
        }

        .subcpmk td {
            background: #d9f07c;
        }

        /* SPACER (indent visual) */
        .spacer {
            width: 20px;
            background: transparent;
            border: none;
        }
    </style>
@endpush
@section('title', 'Kurikulum')

@section('content')
    @php
        function countLeaf($node)
        {
            if (!isset($node['children']) || count($node['children']) === 0) {
                return 1;
            }

            $count = 0;
            foreach ($node['children'] as $child) {
                $count += countLeaf($child);
            }
            return $count;
        }
    @endphp
    <div class="cover-wrapper">

        <div class="logo">
            <img src="{{ public_path('images/logo-polkam-2.png') }}" alt="Logo Politeknik Kampar">
        </div>

        <div class="title">
            KURIKULUM
        </div>
        <div class="subtitle">
            {{ $kurikulum->name }}
        </div>
        <div class="content">
            <div><strong>Dibuat Oleh : {{ $kurikulum->creator->name }}</strong> </div>
            <div><strong>Versi : {{ $kurikulum->version }}</strong> </div>
            <div><strong>Tahun : {{ $kurikulum->year }}</strong> </div>
        </div>
        <div class="footer">
            <div style="text-transform: uppercase">
                PROGRAM STUDI {{ $kurikulum->programStudis->name }}
            </div>
            <div>
                POLITEKNIK KAMPAR
            </div>

            <div class="year">
                -
            </div>
        </div>
    </div>
    <div class="page-break"></div>
    <div style="padding-left: 25px;padding-right:25px">
        @foreach ($tree as $cpl)
            <table class="obe-layout">
                <tr class="cpl">
                    <td colspan="12">
                        <label style="font-weight: bold">{{ $cpl['label'] }}</label><br>
                        {{ $cpl['desc'] }}
                    </td>
                </tr>
                @foreach ($cpl['children'] as $child)
                    <tr>
                        <td colspan="" style="background:#8c8c8c">

                        </td>
                        <td colspan="11" style="background:#e0dddd">
                            <label style="font-weight: bold">{{ $child['label'] }}</label><br>
                            {{ $child['desc'] }}
                        </td>
                    </tr>
                    @if (isset($child['children']) && $child['type'] == 'bk')
                        @foreach ($child['children'] as $subchild)
                            <tr>
                                <td colspan="" style="background:#8c8c8c">

                                </td>
                                <td colspan="" style="background:#e0dddd">

                                </td>
                                <td colspan="10" style="background:#c4c4c4">
                                    {{ $subchild['label'] }} <br>
                                    {{ $subchild['desc'] }}
                                </td>
                            </tr>
                            @if (isset($subchild['children']) && $subchild['type'] == 'mk')
                                @foreach ($subchild['children'] as $subsubchild)
                                    <tr>
                                        <td colspan="" style="background:#8c8c8c">

                                        </td>
                                        <td colspan="" style="background:#e0dddd">

                                        </td>
                                        <td colspan="" style="background:#c4c4c4">

                                        </td>
                                        <td colspan="9" style="background:#c4c4c4">
                                            {{ $subsubchild['label'] }} <br>{{ $subsubchild['desc'] }}
                                        </td>
                                    </tr>
                                    @if (isset($subsubchild['children']) && $subsubchild['type'] == 'cpmk')
                                        @foreach ($subsubchild['children'] as $subsubsubchild)
                                            <tr>
                                                <td colspan="" style="background:#8c8c8c">

                                                </td>
                                                <td colspan="">

                                                </td>
                                                <td colspan="">

                                                </td>
                                                <td colspan="">

                                                </td>
                                                <td colspan="8">
                                                    {{ $subsubsubchild['label'] }} <br>{{ $subsubsubchild['desc'] }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                    @if (isset($child['children']) && $child['type'] == 'mk')
                        @foreach ($child['children'] as $subchild)
                            <tr>
                                <td colspan="" style="background:#8c8c8c">

                                </td>
                                <td style="background:#e0dddd">
                                    --
                                </td>
                                <td colspan="10" style="background:#c4c4c4">
                                    {{ $subchild['label'] }}<br>{{ $subchild['desc'] }}
                                </td>
                            </tr>
                            @if (isset($subchild['children']) && $subchild['type'] == 'cpmk')
                                @foreach ($subchild['children'] as $subsubchild)
                                    <tr>
                                        <td colspan="" style="background:#8c8c8c">

                                        </td>
                                        <td colspan="" style="background:#e0dddd">

                                        </td>
                                        <td colspan="" style="background:#c4c4c4">

                                        </td>
                                        <td colspan="9" style="background:#fceded">
                                            {{ $subsubchild['label'] }} <br>{{ $subsubchild['desc'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    @endif
                @endforeach

            </table>
            <div class="page-break"></div>
        @endforeach
    </div>
@endsection
