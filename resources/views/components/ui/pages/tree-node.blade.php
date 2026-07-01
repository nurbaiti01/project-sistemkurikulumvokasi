@php
    $key = $node['type'] . '-' . $node['id'];
    $hasChildren = !empty($node['children'] ?? []);
@endphp

<li>
    <div class="flex items-center gap-2 py-1">
        {{-- Toggle --}}
        @if ($hasChildren)
            <button wire:click="$parent.toggle('{{ $key }}')" class="w-4 text-gray-500 hover:text-black">
                {{ $expanded[$key] ?? false ? '▼' : '▶' }}
            </button>
        @else
            <span class="w-4"></span>
        @endif

        {{-- Label --}}
        <span class="font-medium text-gray-800 dark:text-white">
            {{ $node['label'] }}
        </span>
    </div>

    {{-- Children --}}
    @if ($hasChildren && ($expanded[$key] ?? false))
        <ul class="ml-6 border-l pl-3 space-y-1">
            @foreach ($node['children'] as $child)
                @include('components.ui.pages.tree-node', [
                    'node' => $child,
                    'expanded' => $expanded,
                ])
            @endforeach
        </ul>
    @endif
</li>
