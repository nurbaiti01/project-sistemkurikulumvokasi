@props([
    'title' => 'Total User',
    'value' => 0,
    'data' => [],
    'icon' => '',
    'show' => true,
])

@if ($show)
    <div
        class="flex flex-col justify-between rounded-xl bg-white dark:bg-gray-800
           border border-gray-100 dark:border-gray-700 p-5 shadow-sm
           hover:shadow-md transition w-1/2">

        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <div>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $title }}
                </p>
                <p class="text-3xl font-semibold text-gray-900 dark:text-white mt-1">
                    {{ $value }}
                </p>
            </div>
            <div
                class="flex h-14 w-14 items-center justify-center rounded-lg
                   bg-indigo-50 text-indigo-600
                   dark:bg-indigo-900/40 dark:text-indigo-400">
                <!-- Icon Kurikulum -->
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.966 8.966 0 0 0 3 3.75v14.25A8.966 8.966 0 0 1 12 20.25
                         a8.966 8.966 0 0 1 9-2.25V3.75
                         A8.966 8.966 0 0 0 12 6.042Z" />
                </svg>
            </div>
        </div>
        @if ($data)
            <!-- Status Breakdown -->
            <div class="border-t border-gray-100 dark:border-gray-700 pt-4 space-y-2 text-sm">
                @foreach ($data as $key => $value)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600 dark:text-gray-300 capitalize">
                            {{ $key }}
                        </span>
                        <span class="font-medium text-green-600 dark:text-green-400">
                            {{ $value }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endif
