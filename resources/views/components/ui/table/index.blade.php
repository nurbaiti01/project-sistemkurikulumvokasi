@props([
    // Table config
    'striped' => false,
    'columns' => [],
    // Action default
    'showAction' => true,
    'editAction' => null,
    'deleteAction' => null,
])

<div class="relative w-full overflow-visible rounded-sm border border-neutral-300 dark:border-neutral-700">
    <table class="w-full overflow-visible text-left text-sm text-neutral-600 dark:text-neutral-300">

        {{-- THEAD --}}
        <thead
            class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900
                   dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
            <tr>
                @if ($columns)
                    @foreach ($columns as $c)
                        <th class="p-4">{{ $c }}</th>
                    @endforeach
                @else
                    {{ $thead }}
                @endif
                @if ($showAction)
                    <th scope="col" class="p-4 w-[120px]">Action</th>
                @endif
            </tr>
        </thead>

        {{-- TBODY --}}
        <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
            {{ $slot }}
        </tbody>

    </table>
</div>
