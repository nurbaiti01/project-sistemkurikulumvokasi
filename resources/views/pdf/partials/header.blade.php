<table width="100%" cellpadding="0" cellspacing="0"
    style="
        border-collapse: collapse;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11px;
        line-height: 1.1;
    ">

    {{-- ROW 1 & 2 --}}
    <tr style="height:18px;">
        {{-- LOGO --}}
        <td rowspan="4" width="15%" align="center" valign="middle" style="border:1px solid #0000006e;">
            <img src="{{ public_path('images/logo-polkam-2.png') }}" alt="Logo Politeknik Kampar"
                style="height:55px; width:auto;" />
        </td>

        {{-- TITLE ATAS --}}
        <td rowspan="2" width="55%" align="center" valign="middle" style="border:1px solid #0000006e;">
            <div style="font-size:18px; font-weight:bold;">
                POLITEKNIK KAMPAR
            </div>
        </td>

        {{-- META --}}
        <td width="10%" style="border-top:1px solid #0000006e; padding:2px;">Nomor</td>
        <td width="" style="border-top:1px solid #0000006e;border-right:1px solid #0000006e; padding:2px;">: Fm-Adm-011</td>
    </tr>

    <tr style="height:20px;">
        <td style="border-top:1px solid #0000006e; padding:2px;">Tanggal</td>
        <td style="border-top:1px solid #0000006e;border-right:1px solid #0000006e; padding:2px;">
            : {{ now()->format('d F Y') }}
        </td>
    </tr>

    {{-- ROW 3 & 4 --}}
    <tr style="height:24px;">
        {{-- TITLE BAWAH --}}
        <td rowspan="2" align="center" valign="middle" style="border:1px solid #0000006e;">
            <div style="font-size:18px; font-weight:bold;">
                FORM MUTU
            </div>
        </td>

        <td style="border-top:1px solid #0000006e; padding:2px;">Revisi</td>
        <td style="border-top:1px solid #0000006e;border-right:1px solid #0000006e; padding:2px;">: 0</td>
    </tr>

    <tr style="height:24px;">
        <td style="border-top:1px solid #0000006e; border-bottom:1px solid #0000006e; padding:2px;">Halaman</td>
        <td style="border-top:1px solid #0000006e;border-bottom:1px solid #0000006e;border-right:1px solid #0000006e; padding:2px;">
            :
            <script type="text/php">
                if (isset($pdf)) {
                    echo $PAGE_NUM . ' dari ' . $PAGE_COUNT;
                }
            </script>
        </td>
    </tr>
</table>
