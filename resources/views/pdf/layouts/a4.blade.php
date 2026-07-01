<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>

    <!-- Normalize or reset CSS with your favorite library -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css"> --}}

    <!-- Load paper.css for happy printing -->
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css"> --}}

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        /* @page {
            size: A4
        } */
        @page {
            margin: 120px 30px 60px 30px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        /* td,
        th {
            border: 1px solid #ddd;
            padding: 4px;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #04AA6D;
            color: white;
        } */
        .pdf-header {
            position: fixed;
            top: -100px;
            left: 0;
            right: 0;
            height: 90px;
        }

        /* CONTENT */
        .pdf-content {
            margin-top: 10px;
        }

        /* PAGE BREAK */
        .page-break {
            page-break-before: always;
        }
    </style>
    @stack('styles')
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
    @if (View::hasSection('header'))
        <div class="pdf-header">
            @yield('header')
        </div>
    @endif
    <div class="pdf-content">
        @yield('content')
        {{-- <div class="page-break"></div> --}}
    </div>

</body>

</html>
