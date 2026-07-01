@props([
    'title' => 'Sample Data',
    'id' => 'sampleModal',
    'align' => 'center',
    'width' => 'sm',
    'min' => '1',
    'max' => '5',
    'step' => '1',
    'model' => 'form.jumlah',
])

<x-modal-card title="{{ $title }}" name="{{ $id }}" width="{{ $width }}" blur
    align="{{ $align }}" persistent>
    <x-alert title="Information" info padding="none">
        <x-slot name="slot">
            Form akan menghasilkan data sampel dengan jumlah minimal :
        {{ $min }} maksimal : {{ $max }}
        </x-slot>
    </x-alert>
    <div class="py-3 grid grid-cols-1 gap-4 sm:grid-cols-1">
        <x-number min="{{ $min }}" max="{{ $max }}" step="{{ $step }}"
            label="Jumlah Sample Data" value="1" wire:model.defer="{{ $model }}" />
    </div>

    @isset($field)
        {{ $field }}
    @endisset

    <x-slot name="footer" class="flex justify-end gap-x-4">
        <div class="flex gap-x-4">
            <x-button flat label="Cancel" x-on:click="close" />

            <x-button primary label="Generate" wire:click="generateSample" />
        </div>
    </x-slot>
</x-modal-card>
