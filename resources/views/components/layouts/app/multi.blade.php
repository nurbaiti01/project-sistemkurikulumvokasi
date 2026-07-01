<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ config('app.dark_mode') ? 'dark' : '' }}">

<head>
    @include('partials.head')


    @livewireStyles
    <wireui:styles />
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote-lite.min.js"></script>
</head>

<body class="w-full max-w-full xl:max-w-[2560px] min-h-screen bg-white dark:bg-zinc-900">
    {{-- Dialog global --}}
    <x-dialog z-index="z-50" align="center" width="w-md" />

    {{-- Notifications global --}}
    <x-notifications />

    {{-- Topbar --}}
    <x-layouts.topbar />

    {{-- Navbar --}}
    <x-layouts.navbar />

    {{-- Main content --}}
    <main class="flex-1 bg-white dark:bg-zinc-900">
        
        {{ $slot }}
    </main>

    {{-- Scripts --}}
    <wireui:scripts />
    @fluxScripts
    @livewireScripts
</body>

</html>
