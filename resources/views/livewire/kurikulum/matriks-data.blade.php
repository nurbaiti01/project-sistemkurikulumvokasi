<div class="container mx-auto py-3">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-base font-semibold text-gray-800 dark:text-white">
            Struktur Kurikulum
        </h2>

        <div class="flex gap-2">
            <flux:button variant="primary" color="amber" :href="route('kurikulum.index')" wire:navigate>Kembali
            </flux:button>


        </div>
    </div>


    <div class="my-4 flex justify-between items-center gap-2">
        <flux:dropdown>
            <flux:button icon:trailing="chevron-down">Tampilan Matriks</flux:button>
            <flux:menu>
                <flux:menu.radio.group wire:model.change="matrixMode">
                    <flux:menu.radio checked value="">Semua</flux:menu.radio>
                    <flux:menu.radio value="cpl-pl">CPL → PL</flux:menu.radio>
                    <flux:menu.radio value="cpl-bk">CPL → BK</flux:menu.radio>
                    <flux:menu.radio value="bk-mk">BK → MK</flux:menu.radio>
                    <flux:menu.radio value="cpl-mk">CPL → MK</flux:menu.radio>
                    <flux:menu.radio value="mk-cpmk">MK → CPMK</flux:menu.radio>
                    <flux:menu.radio value="cpmk-subcpmk">CPMK → Sub CPMK</flux:menu.radio>
                    <flux:menu.radio value="cpl-bk-mk">CPL → BK → MK</flux:menu.radio>
                    <flux:menu.radio value="cpl-cpmk-mk">CPL → MK → CPMK</flux:menu.radio>
                </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>
        <span> Matriks View : <flux:badge color="blue">{{ $matrixMode ? $matrixMode : 'Semua' }}</flux:badge> </span>
        <div>
            <flux:button variant="primary" color="zinc" href="{{ route('pdf.preview.kurikulum', ['id' => $kurikulum->id]) }}" target="_blank">Pdf</flux:button>
            <flux:button variant="primary" color="blue" @click="$dispatch('expandAll')">Expand All</flux:button>
            <flux:button variant="primary" @click="$dispatch('collapseAll')">Collapse All</flux:button>
        </div>
    </div>
    <div class="grid grid-cols-5 gap-4">
        <div class="col-span-1">
            <div class="w-full mx-auto">
                <div
                    class="rounded-xl border border-neutral-200 dark:border-neutral-700
               bg-white dark:bg-neutral-900
               shadow-sm hover:shadow-md transition">

                    {{-- Header --}}
                    <div
                        class="flex items-start justify-between px-6 py-4 border-b border-neutral-200 dark:border-neutral-700">
                        <div>
                            <h3 class="text-lg font-semibold text-neutral-900 dark:text-white">
                                {{ $kurikulum->name }}
                            </h3>
                            <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                Program Studi: {{ $kurikulum->programStudis->name }}
                            </p>
                        </div>

                        @php
                            $badgeColor = match ($kurikulum->status) {
                                'draft' => 'yellow',
                                'submitted' => 'blue',
                                'approved_bpm' => 'green',
                                'approved_wadir' => 'green',
                                'approved_direktur' => 'green',
                                'archived' => 'red',
                                'published' => 'lime',
                            };
                        @endphp
                        <flux:badge color="{{ $badgeColor }}">{{ $kurikulum->status }}</flux:badge>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-5 grid grid-cols-2 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-neutral-500 dark:text-neutral-400">Tahun</p>
                            <p class="font-medium text-neutral-900 dark:text-white">{{ $kurikulum->year }}</p>
                        </div>

                        <div>
                            <p class="text-neutral-500 dark:text-neutral-400">Versi</p>
                            <p class="font-medium text-neutral-900 dark:text-white">{{ $kurikulum->version }}</p>
                        </div>

                        <div>
                            <p class="text-neutral-500 dark:text-neutral-400">Tipe</p>
                            <p class="font-medium text-neutral-900 dark:text-white capitalize">
                                {{ $kurikulum->type }}
                            </p>
                        </div>

                        <div>
                            <p class="text-neutral-500 dark:text-neutral-400">Dibuat Oleh</p>
                            <p class="font-medium text-neutral-900 dark:text-white">
                                {{ $kurikulum->creator->name }}
                            </p>
                        </div>
                    </div>
                    <div class="border-t border-neutral-200 dark:border-neutral-700 px-6 py-5">

                        <h4 class="text-sm font-semibold text-neutral-800 dark:text-neutral-200 mb-4">
                            Tracking Approval
                        </h4>

                        <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                            {{-- Wadir --}}
                            <div
                                class="rounded-lg border
                border-neutral-200 dark:border-neutral-700
                bg-neutral-50 dark:bg-neutral-800
                p-4 flex items-start gap-3">

                                <div class="mt-1">
                                    @if (optional($kurikulum->wadirApproval)->status === 'approved')
                                        <span class="text-green-600">✔</span>
                                    @elseif(optional($kurikulum->wadirApproval)->status === 'rejected')
                                        <span class="text-red-600">✖</span>
                                    @else
                                        <span class="text-yellow-500">⏳</span>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="font-medium text-neutral-900 dark:text-white">
                                        Wadir Approval
                                    </p>

                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        Status:
                                        <span class="font-medium capitalize">
                                            {{ optional($kurikulum->wadirApproval)->status ?? 'pending' }}
                                        </span>
                                    </p>

                                    @if ($kurikulum->wadirApproval?->approved_by)
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Oleh: {{ $kurikulum->wadirApproval->approver->name }}
                                            • {{ $kurikulum->wadirApproval->approved_at }}
                                        </p>
                                    @endif

                                    @if ($kurikulum->wadirApproval?->note)
                                        <p class="text-xs text-red-600 mt-1">
                                            Catatan: {{ $kurikulum->wadirApproval->note }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            {{-- Direktur --}}
                            <div
                                class="rounded-lg border
                border-neutral-200 dark:border-neutral-700
                bg-neutral-50 dark:bg-neutral-800
                p-4 flex items-start gap-3">

                                <div class="mt-1">
                                    @if (optional($kurikulum->direkturApproval)->status === 'approved')
                                        <span class="text-green-600">✔</span>
                                    @elseif(optional($kurikulum->direkturApproval)->status === 'rejected')
                                        <span class="text-red-600">✖</span>
                                    @else
                                        <span class="text-yellow-500">⏳</span>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="font-medium text-neutral-900 dark:text-white">
                                        Direktur Approval
                                    </p>

                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        Status:
                                        <span class="font-medium capitalize">
                                            {{ optional($kurikulum->direkturApproval)->status ?? 'pending' }}
                                        </span>
                                    </p>

                                    @if ($kurikulum->direkturApproval?->approved_by)
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Oleh: {{ $kurikulum->direkturApproval->approver->name }}
                                            • {{ $kurikulum->direkturApproval->approved_at }}
                                        </p>
                                    @endif

                                    @if ($kurikulum->direkturApproval?->note)
                                        <p class="text-xs text-red-600 mt-1">
                                            Catatan: {{ $kurikulum->direkturApproval->note }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            {{-- BPM --}}
                            <div
                                class="rounded-lg border
                border-neutral-200 dark:border-neutral-700
                bg-neutral-50 dark:bg-neutral-800
                p-4 flex items-start gap-3">

                                <div class="mt-1">
                                    @if (optional($kurikulum->bpmApproval)->status === 'approved')
                                        <span class="text-green-600">✔</span>
                                    @elseif(optional($kurikulum->bpmApproval)->status === 'rejected')
                                        <span class="text-red-600">✖</span>
                                    @else
                                        <span class="text-yellow-500">⏳</span>
                                    @endif
                                </div>

                                <div class="flex-1">
                                    <p class="font-medium text-neutral-900 dark:text-white">
                                        BPM Approval
                                    </p>

                                    <p class="text-sm text-neutral-500 dark:text-neutral-400">
                                        Status:
                                        <span class="font-medium capitalize">
                                            {{ optional($kurikulum->bpmApproval)->status ?? 'pending' }}
                                        </span>
                                    </p>

                                    @if ($kurikulum->bpmApproval?->approved_by)
                                        <p class="text-xs text-neutral-500 dark:text-neutral-400 mt-1">
                                            Oleh: {{ $kurikulum->bpmApproval->approver->name }}
                                            • {{ $kurikulum->bpmApproval->approved_at }}
                                        </p>
                                    @endif

                                    @if ($kurikulum->bpmApproval?->note)
                                        <p class="text-xs text-red-600 mt-1">
                                        </p>
                                    @endif
                                </div>
                            </div>



                        </div>
                    </div>
                </div>
                <div class="flex py-3 gap-3">
                    @if ($kurikulum->status == 'draft' && Gate::allows('submitted', [$kurikulum, ['Kaprodi']]))
                        <flux:button variant="primary" color="lime" wire:click="submitKurikulum">Submit
                            Kurikulum
                        </flux:button>
                    @endif
                    @if ($kurikulum->status == 'approved_direktur' && Gate::allows('approval', [$kurikulum, ['BPM']]))
                        <flux:button variant="primary" color="lime" icon="check"
                            wire:click="approval({{ $kurikulum->id }},'bpm')">
                            Approved & Publish
                        </flux:button>
                        <flux:button variant="primary" color="red" icon="x-mark" wire:click="rejected({{ $kurikulum->id }},'bpm')">
                            Rejected
                        </flux:button>
                    @endif
                    @if ($kurikulum->status == 'submitted' && Gate::allows('approval', [$kurikulum, ['WADIR 1']]))
                        <flux:button variant="primary" color="lime" icon="check"
                            wire:click="approval({{ $kurikulum->id }},'wadir')">
                            Approved
                        </flux:button>
                        <flux:button variant="primary" color="red" icon="x-mark" wire:click="rejected({{ $kurikulum->id }},'wadir')">
                            Rejected
                        </flux:button>
                    @endif
                    @if ($kurikulum->status == 'approved_wadir' && Gate::allows('approval', [$kurikulum, ['Direktur']]))
                        <flux:button variant="primary" color="lime" icon="check"
                            wire:click="approval({{ $kurikulum->id }},'direktur')">
                            Approved
                        </flux:button>
                        <flux:button variant="primary" color="red" icon="x-mark" wire:click="rejected({{ $kurikulum->id }},'direktur')">
                            Rejected
                        </flux:button>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-span-4 col-start-2">
            <livewire:tree-view :tree="$tree" wire:key="kurikulum-tree" />
        </div>
    </div>

    {{-- Dialog Confirm Approved --}}

    <flux:modal name="approvedDialog" class="min-w-[22rem]" :dismissible="false" :closable="false">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Approved Kurikulum</flux:heading>
                <flux:text class="mt-2">
                    Are you sure you want to approved this kurikulum?
                </flux:text>
            </div>
            <div class="flex gap-2">
                <flux:spacer />
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button type="button" variant="primary" color="lime" wire:click="approvedKurikulum">Approved
                </flux:button>
            </div>
        </div>
    </flux:modal>
    <flux:modal name="rejectedDialog" class="md:w-96" :dismissible="false" :closable="false">
        <form wire:submit.prevent="rejectedKurikulum" class="space-y-6">
            <div>
                <flux:heading size="lg">Rejected Kurikulum</flux:heading>
            </div>
            <flux:textarea label="Alasan Reject" wire:model="approval_note" placeholder="Alasan Di reject"/>
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" variant="primary" color="red">Rejected</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
