<div>
    <x-card>
        <div class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                <livewire:perangkat-ajar.realisasi-ajar.section.header :isEdit="true" :selectedId="$realisasiPengajaranId"
                    wire:key="header-{{ $realisasiPengajaranId }}" />
                <livewire:perangkat-ajar.realisasi-ajar.section.pertemuan-table :isEdit="true" :selectedId="$realisasiPengajaranId"
                    wire:key="pertemuan-{{ $realisasiPengajaranId }}" />
                <livewire:perangkat-ajar.realisasi-ajar.section.footer :isEdit="true" :selectedId="$realisasiPengajaranId"
                    wire:key="footer-{{ $realisasiPengajaranId }}" />
            </table>
        </div>
        <div class="flex justify-end gap-4 my-3">
            <flux:button variant="primary" color="zinc" wire:click="save(true)">Save As Draft</flux:button>
            <flux:button variant="primary" color="green" wire:click="save(false)">Submit</flux:button>
        </div>
    </x-card>
</div>
