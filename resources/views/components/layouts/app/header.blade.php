<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <x-dialog />
    <x-notifications />
    <x-layouts.topbar />

    <x-layouts.navbar />


    {{ $slot }}
    <wireui:scripts />
    @fluxScripts
    @livewireScripts

</body>

</html>
