<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 90px;
        }

        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 11.5px;
            line-height: 1.5;
            color: #000;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 18px;
        }

        .item-title {
            font-weight: bold;
            margin-top: 12px;
        }

        ul {
            margin: 6px 0 10px 18px;
            padding: 0;
        }

        li {
            margin-bottom: 4px;
        }

        .bold {
            font-weight: bold;
        }

        .item-title {
            font-weight: bold;
            margin-top: 12px;
        }

        ul {
            margin: 6px 0 10px 18px;
            padding: 0;
        }

        li {
            margin-bottom: 4px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="title">
        Rancangan Penilaian
    </div>

    @foreach ($pertemuans as $pertemuan)
        @php
            $penilaian = $pertemuan['rancangan_penilaian'];
        @endphp
        <div class="item-title">
            {{ $penilaian['jenis'] }}
            (Pertemuan {{ $pertemuan['pertemuan_ke'] }})
        </div>

        <ul>
            <li>
                <span class="bold">Bentuk:</span>
                {{ $penilaian['bentuk'] }}
            </li>

            <li>
                <span class="bold">Topik:</span>
                {{ $penilaian['topik'] }}
            </li>

            <li>
                <span class="bold">Bobot:</span>
                {{ $penilaian['bobot'] }}%
            </li>
        </ul>
    @endforeach


</body>

</html>
