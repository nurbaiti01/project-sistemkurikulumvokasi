@props([
    'row' => null, // model instance
    'edit' => null, // wire:click action
    'delete' => null, // wire:click action
    'mode' => 'buttons', // buttons | dropdown
    'items' => [], // custom dropdown items
    'abilities' => [
        // Gate mapping
        'update' => 'update',
        'delete' => 'delete',
    ],
    'allow' => [], // role khusus yg boleh
    'block' => [], // role yg diblokir
])

@php
    $canUpdate = Gate::allows($abilities['update'], [$row, empty($block) ? $allow : $block ]);
    $canDelete = Gate::allows($abilities['delete'], [$row, empty($block) ? $allow : $block ]);
    $anyAllowed = $canUpdate || $canDelete;
@endphp

@if ($anyAllowed)
    <td class="p-3 whitespace-nowrap">

        {{-- ========================= BUTTON MODE ========================= --}}
        @if ($mode === 'buttons')
            <div class="flex items-center gap-2">
                @if ($edit && $canUpdate)
                    <flux:button variant="primary" icon="pencil" wire:click="{{ $edit }}" size="sm" />
                @endif

                @if ($delete && $canDelete)
                    <flux:button variant="danger" icon="trash" wire:click="{{ $delete }}" size="sm" />
                @endif
                {{ $slot }}
            </div>
        @endif


        {{-- ========================= DROPDOWN MODE ========================= --}}
        @if ($mode === 'dropdown')
            <flux:dropdown>
                <flux:button icon:trailing="chevron-down">
                    Action
                </flux:button>

                <flux:menu>
                    {{-- core actions --}}
                    @if ($edit && $canUpdate)
                        <flux:menu.item icon="pencil" wire:click="{{ $edit }}">
                            Edit
                        </flux:menu.item>
                    @endif

                    {{-- dynamic items --}}
                    @foreach ($items as $item)
                        @php
                            $show = $item['show'] ?? true;
                            $visible = is_callable($show) ? $show($row) : $show;
                        @endphp

                        @if ($visible)
                            @if (!empty($item['href']))
                                <flux:menu.item icon="{{ $item['icon'] }}" :href="$item['href']" wire:navigate>
                                    {{ $item['label'] }}
                                </flux:menu.item>
                            @else
                                <flux:menu.item icon="{{ $item['icon'] }}" wire:click="{{ $item['action'] }}">
                                    {{ $item['label'] }}
                                </flux:menu.item>
                            @endif
                        @endif
                    @endforeach

                    {{-- delete --}}
                    @if ($delete && $canDelete)
                        <flux:menu.item variant="danger" icon="trash" wire:click="{{ $delete }}">
                            Delete
                        </flux:menu.item>
                    @endif
                </flux:menu>
            </flux:dropdown>
        @endif
    </td>
@endif
