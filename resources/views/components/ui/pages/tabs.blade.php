@props([
    'tabs' => [],
])

@php
    $wireModel = $attributes->get('wire:model');
@endphp

<div x-data="{
    active: @entangle($wireModel).defer
}" class="w-full">
    {{-- TAB HEADER --}}
    <div class="flex flex-wrap border-b border-neutral-300 dark:border-neutral-700">
        @foreach ($tabs as $key => $label)
            <button type="button" @click="active = {{ $key }}"
                class="px-4 py-2 text-sm font-medium transition
                    border-b-2 -mb-px"
                :class="active === {{ $key }} ?
                    'border-black text-black dark:border-white dark:text-white' :
                    'border-transparent text-neutral-500 hover:text-neutral-800 dark:text-neutral-400 dark:hover:text-neutral-200'">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- TAB CONTENT --}}
    <div class="pt-4">
        <h3 x-text="active"></h3>
        @foreach ($tabs as $key => $label)
            <div x-transition.opacity x-cloak>
                {{ $slot->{'tab.' . $key} ?? '' }}
            </div>
        @endforeach
    </div>
</div>
