@props([
    // Livewire paginator (Illuminate\Pagination\LengthAwarePaginator)
    'paginator' => null,

    // Manual config (optional)
    'from' => null,
    'to' => null,
    'total' => null,

    // Custom labels
    'prevLabel' => 'Previous',
    'nextLabel' => 'Next',
])

@if ($paginator instanceof \Illuminate\Pagination\LengthAwarePaginator && $paginator->hasPages())
    <nav aria-label="pagination" class="mt-6">
        <ul class="flex shrink-0 items-center justify-between gap-2 text-sm font-medium">

            {{-- PREVIOUS --}}
            <li>
                @if ($paginator && $paginator->onFirstPage())
                    <span class="flex items-center rounded-sm p-1 text-neutral-400 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"
                            class="size-6">
                            <path fill-rule="evenodd"
                                d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $prevLabel }}
                    </span>
                @else
                    <button type="button" @if ($paginator) wire:click="previousPage" @endif
                        class="flex items-center rounded-sm p-1 text-neutral-600 hover:text-black
                               dark:text-neutral-300 dark:hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06
                                   l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z" />
                        </svg>
                        {{ $prevLabel }}
                    </button>
                @endif
            </li>

            {{-- INFO --}}
            <li>
                <span class="dark:text-white">
                    @if ($paginator)
                        Showing {{ $paginator->firstItem() }}
                        to {{ $paginator->lastItem() }}
                        of {{ $paginator->total() }} results
                    @else
                        Showing {{ $from }} to {{ $to }} of {{ $total }} results
                    @endif
                </span>
            </li>

            {{-- NEXT --}}
            <li>
                @if ($paginator && !$paginator->hasMorePages())
                    <span class="flex items-center rounded-sm p-1 text-neutral-400 cursor-not-allowed">
                        {{ $nextLabel }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06
                                   l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28
                                   a.75.75 0 0 1 0-1.06Z" />
                        </svg>
                    </span>
                @else
                    <button type="button" @if ($paginator) wire:click="nextPage" @endif
                        class="flex items-center rounded-sm p-1 text-neutral-600 hover:text-black
                               dark:text-neutral-300 dark:hover:text-white">
                        {{ $nextLabel }}
                        <svg xmlns="http://www.w3.org/2000/svg" class="size-6" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06
                                   l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28
                                   a.75.75 0 0 1 0-1.06Z" />
                        </svg>
                    </button>
                @endif
            </li>

        </ul>
    </nav>
@endif
