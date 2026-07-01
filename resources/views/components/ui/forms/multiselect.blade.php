@props(['name', 'options' => [], 'optionLabel' => 'label', 'optionValue' => 'value', 'placeholder' => 'Pilih data'])

<div x-data="{
    open: false,
    search: '',
    selected: @entangle($attributes->wire('model')).defer ?? [],
    toggle() { this.open = !this.open },
    close() { this.open = false },
    isSelected(value) {
        return this.selected.includes(value)
    },
    select(value) {
        if (this.isSelected(value)) {
            this.selected = this.selected.filter(v => v !== value)
        } else {
            this.selected.push(value)
        }
    },
    filteredOptions() {
        return [...this.$refs.options.children].filter(el =>
            el.dataset.label.toLowerCase().includes(this.search.toLowerCase())
        )
    }
}" class="relative" @click.outside="close">
    <!-- Hidden Input (non Livewire) -->
    <template x-if="!$wire">
        <input type="hidden" :name="'{{ $name }}[]'" wire:model="{{ $name }}" x-for="item in selected" :value="item">
    </template>

    <!-- Control -->
    <div @click="toggle"
        class="min-h-[42px] flex flex-wrap items-center gap-1 px-3 py-2 border rounded-lg cursor-pointer
               bg-white shadow-sm focus-within:ring-2 focus-within:ring-blue-500">
        <template x-if="selected.length === 0">
            <span class="text-gray-400 text-sm">{{ $placeholder }}</span>
        </template>

        <template x-for="value in selected" :key="value">
            <span
                class="flex items-center gap-1 px-2 py-1 text-sm rounded-md
                       bg-blue-100 text-blue-700">
                <span
                    x-text="
                        [...$refs.options.children]
                        .find(o => o.dataset.value == value)?.dataset.label
                    "></span>
                <button type="button" @click.stop="select(value)" class="hover:text-red-600">
                    ✕
                </button>
            </span>
        </template>
    </div>

    <!-- Dropdown -->
    <div x-show="open" x-transition class="absolute z-20 mt-2 w-full bg-white border rounded-lg shadow-lg">
        <!-- Search -->
        <div class="p-2 border-b">
            <input type="text" x-model="search" placeholder="Cari..."
                class="w-full px-2 py-1 text-sm border rounded focus:ring focus:ring-blue-500">
        </div>

        <!-- Options -->
        <ul x-ref="options" class="max-h-60 overflow-y-auto text-sm">
            @foreach ($options as $option)
                <li data-value="{{ data_get($option, $optionValue) }}"
                    data-label="{{ data_get($option, $optionLabel) }}"
                    @click="select({{ data_get($option, $optionValue) }})"
                    class="px-3 py-2 cursor-pointer flex justify-between
                           hover:bg-blue-50"
                    :class="{
                        'bg-blue-100 text-blue-700': isSelected({{ data_get($option, $optionValue) }})
                    }">
                    <span>{{ data_get($option, $optionLabel) }}</span>
                    <span x-show="isSelected({{ data_get($option, $optionValue) }})">✔</span>
                </li>
            @endforeach
        </ul>
    </div>
</div>
