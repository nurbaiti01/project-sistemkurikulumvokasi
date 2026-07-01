@props([
    'striped' => false,
])

<tr @class([
    'hover:bg-neutral-50 dark:hover:bg-neutral-800 transition',
    'bg-neutral-50/50 dark:bg-neutral-800/50' => $striped,
])>
    {{ $slot }}
</tr>
