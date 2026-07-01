<div>
    <x-card>
        <livewire:perangkat-ajar.realisasi-ajar.section.timeline :isView="true" :selectedId="$realisasiPengajaranId"
            wire:key="header-{{ $realisasiPengajaranId }}" />
    </x-card>
    <x-card>
        <div class="overflow-hidden w-full overflow-x-auto rounded-sm border border-neutral-300 dark:border-neutral-700">
            <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300">
                <livewire:perangkat-ajar.realisasi-ajar.section.header :isView="true" :selectedId="$realisasiPengajaranId"
                    wire:key="header-{{ $realisasiPengajaranId }}" />
                <livewire:perangkat-ajar.realisasi-ajar.section.pertemuan-table :isView="true" :selectedId="$realisasiPengajaranId"
                    wire:key="pertemuan-{{ $realisasiPengajaranId }}" />
                <livewire:perangkat-ajar.realisasi-ajar.section.footer :isView="true" :selectedId="$realisasiPengajaranId"
                    wire:key="footer-{{ $realisasiPengajaranId }}" />
            </table>
        </div>
    </x-card>
</div>
