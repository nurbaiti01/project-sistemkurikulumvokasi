@props([
    'wireSearch' => null,
    'searchPlaceholder' => 'Search...',
    'showSearch' => true,
    'searchSize' => 'md',
])

<div class="flex flex-1 flex-col">
    {{-- TOOLBAR --}}
    @if ($showSearch || isset($action))
        <div class="flex flex-col gap-3 mb-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- SEARCH --}}
            @if ($showSearch)
                <div class="relative flex w-full max-w-{{ $searchSize }} flex-col gap-1 text-neutral-600 dark:text-neutral-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor"
                        class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2
                               text-neutral-600/50 dark:text-neutral-300/50">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>

                    <input type="search"
                        @if ($wireSearch) wire:model.live.debounce.300ms="{{ $wireSearch }}" @endif
                        placeholder="{{ $searchPlaceholder }}"
                        class="w-{{ $searchSize }} rounded-sm border border-neutral-300 bg-neutral-50 py-2 pl-10 pr-2 text-sm
                               focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black
                               disabled:cursor-not-allowed disabled:opacity-75
                               dark:border-neutral-700 dark:bg-neutral-900/50
                               dark:focus-visible:outline-white" />
                </div>
            @endif

            @isset($filter)
                <div>
                    {{ $filter }}
                </div>
            @endisset

            {{-- ACTION SLOT --}}
            @isset($action)
                <div class="shrink-0">
                    {{ $action }}
                </div>
            @endisset

        </div>
    @endif

    {{-- CONTENT --}}
    <div>
        {{ $slot }}
    </div>

</div>
