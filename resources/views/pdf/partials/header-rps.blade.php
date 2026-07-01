<table width="100%" cellpadding="0" cellspacing="0"
    style="
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
    ">

    <tr>
        {{-- LOGO --}}
        <td rowspan="3" width="10%"
            style="border:1px solid #444; text-align:center; vertical-align:middle; padding:0;">
            <img src="{{ public_path('images/logo-polkam-2.png') }}" style="width:55px; height:auto;">
        </td>

        {{-- TITLE --}}
        <td colspan="3" style="border:1px solid #444; padding:6px; font-weight:bold;">
            POLITEKNIK KAMPAR
        </td>

        {{-- DOSEN --}}
        <td style="border:1px solid #444; padding:6px;">
            Dosen Pengampu
        </td>
        <td style="border:1px solid #444; padding:6px;">
            : {{ $identitas['dosen'] }}
        </td>
    </tr>

    <tr>
        {{-- PRODI --}}
        <td width="12%" style="border:1px solid #444; padding:6px;">
            PRODI
        </td>
        <td colspan="2" width="" style="border:1px solid #444; padding:6px; text-align:left;">
            : {{ $identitas['program_studi'] }}
        </td>

        {{-- TAHUN AKADEMIK --}}
        <td width="15%" style="border:1px solid #444; padding:6px;">
            Tahun Akademik
        </td>
        <td width="" style="border:1px solid #444; padding:6px; text-align:left;">
            : {{ $identitas['tahun_akademik'] }}
        </td>
    </tr>

    <tr>
        {{-- KELAS --}}
        <td style="border:1px solid #444; padding:6px;">
            KELAS
        </td>
        <td colspan="2" style="border:1px solid #444; padding:6px; text-align:left;">
            : {{ $identitas['class'] }}
        </td>

        {{-- REVISI --}}
        <td style="border:1px solid #444; padding:6px;">
            Revisi Ke -
        </td>
        <td style="border:1px solid #444; padding:6px; text-align:left;">
            : {{ $identitas['revision'] }}
        </td>
    </tr>
</table>
