<div class="w-full">

    <ul class="space-y-2 text-sm">
        @foreach ($tree as $node)
            @continue(!isset($node['type'], $node['id']))

            @php
                $key = $node['type'] . '-' . $node['id'];
                $isExpanded = $expanded[$key] ?? false;
                $hasChildren = !empty($node['children'] ?? []);
                $isSelected = isset($selected[$node['type']][$node['id']]);

                $colorMap = [
                    'cpl' => 'border-blue-500 bg-blue-50 text-blue-800',
                    'pl' => 'border-indigo-500 bg-indigo-50 text-indigo-800',
                    'bk' => 'border-emerald-500 bg-emerald-50 text-emerald-800',
                    'mk' => 'border-amber-500 bg-amber-50 text-amber-800',
                    'cpmk' => 'border-violet-500 bg-violet-50 text-violet-800',
                    'subcpmk' => 'border-rose-500 bg-rose-50 text-rose-800',
                ];

                $colorClass = $colorMap[$node['type']] ?? 'border-gray-300 bg-white';
            @endphp

            <li>
                {{-- CARD NODE --}}
                <div
                    class="group relative rounded-lg border-l-4 shadow-sm transition-all duration-200
                        {{ $colorClass }}
                        {{ $isSelected ? 'ring-2 ring-offset-1 ring-blue-400' : 'hover:shadow-md' }}">
                    <div class="flex items-center gap-3 px-4 py-3">
                        {{-- TOGGLE --}}
                        <div class="w-5 flex justify-center">
                            @if ($hasChildren)
                                <button type="button" wire:click="toggle('{{ $key }}')"
                                    class="text-gray-500 hover:text-gray-800 transition">
                                    @if ($expanded[$key] ?? false)
                                        <x-feathericon-minus-square />
                                    @else
                                        <x-feathericon-plus-square />
                                    @endif
                                </button>
                            @endif
                        </div>

                        {{-- LABEL --}}
                        <div class="flex-1">
                            <div class="font-semibold tracking-tight">
                                {{ $node['label'] }}
                            </div>

                            <div class="text-xs uppercase opacity-70 mt-0.5">
                                {{ strtoupper($node['desc']) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CHILDREN --}}
                @if ($hasChildren && $isExpanded)
                    <div class="ml-6 mt-2 pl-4 border-l border-dashed border-gray-300">
                        <livewire:tree-view :tree="$node['children']" :expanded="$expanded" :selected="$selected" :key="$key" />
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
</div>
