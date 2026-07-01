<div class="p-8">
    <ol class="flex flex-col sm:flex-row sm:items-start gap-8">
        @foreach ($masterData->approvals as $approval)
            @php
                $isApproved = $approval->status === 'approved';
                $isRejected = $approval->status === 'rejected';
                $isPending = $approval->status === 'pending';

                $dotColor = $isApproved ? 'bg-green-500' : ($isRejected ? 'bg-red-500' : 'bg-gray-400');

                $ringColor = $isApproved
                    ? 'ring-green-200 dark:ring-green-900'
                    : ($isRejected
                        ? 'ring-red-200 dark:ring-red-900'
                        : 'ring-gray-200 dark:ring-gray-800');
            @endphp

            <li class="relative flex-1">
                <!-- Dot & Line -->
                <div class="flex items-center">
                    <div
                        class="z-10 flex items-center justify-center w-6 h-6 rounded-full
                    {{ $dotColor }}
                    ring-8 {{ $ringColor }}">
                        @if ($isApproved)
                            ✓
                        @elseif ($isRejected)
                            ✕
                        @else
                            …
                        @endif
                    </div>

                    <div class="hidden sm:block w-full h-px bg-gray-300 dark:bg-gray-700"></div>
                </div>

                <!-- Content -->
                <div class="mt-4 pr-4">
                    <span
                        class="text-xs font-semibold uppercase tracking-wide
                    {{ $isApproved
                        ? 'text-green-600 dark:text-green-400'
                        : ($isRejected
                            ? 'text-red-600 dark:text-red-400'
                            : 'text-gray-500 dark:text-gray-400') }}">
                        {{ ucfirst($approval->role_proses) }}
                    </span>

                    <h4 class="mt-1 text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ $approval->dosen?->name ?? '—' }}
                    </h4>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Status:
                        <span class="font-medium capitalize">
                            {{ $approval->status }}
                        </span>
                    </p>

                    @if ($approval->catatan)
                        <div class="mt-2 text-sm italic text-gray-500 dark:text-gray-400">
                            “{{ $approval->catatan }}”
                        </div>
                    @endif

                    @if ($approval->approved_at)
                        <time class="mt-2 block text-xs text-gray-400 dark:text-gray-500">
                            {{ $approval->approved_at->format('d M Y') }}
                        </time>
                    @endif
                    @php
                        $role = $approval->role_proses;
                        $status = $approval->status;
                    @endphp

                    {{-- ACTION --}}
                    @if (
                        $role === 'perumusan' &&
                            $status === 'pending' &&
                            $masterData->status === 'draft' &&
                            session('active_role') == 'Dosen')
                        <div class="mt-4">
                            <button wire:click="openDialog({{ $approval->id }})" wire:loading.attr="disabled"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium
                   rounded-md bg-indigo-600 text-white
                   hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                                Submit Perumusan
                            </button>
                        </div>
                    @endif

                    @if (
                        $role === 'pemeriksaan' &&
                            $status === 'pending' &&
                            session('active_role') == 'Kaprodi' &&
                            $masterData->status === 'submitted')
                        <div class="mt-4 flex gap-2">
                            <button wire:click="openDialog({{ $approval->id }},false)" wire:loading.attr="disabled"
                                class="px-4 py-2 text-sm font-medium rounded-md
                   bg-green-600 text-white hover:bg-green-700
                   focus:ring-2 focus:ring-green-500">
                                Approve
                            </button>

                            <button wire:click="openRejectDialog({{ $approval->id }})"
                                class="px-4 py-2 text-sm font-medium rounded-md
                   bg-red-600 text-white hover:bg-red-700
                   focus:ring-2 focus:ring-red-500">
                                Reject
                            </button>
                        </div>
                    @endif
                </div>
            </li>
        @endforeach


        <!-- Duplikasi li untuk item berikutnya -->
    </ol>
    <flux:modal name="rejectedRealisasi" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Rejected Realisasi Pembelajaran</flux:heading>
            </div>
            <flux:textarea label="Catatan Penolakan" wire:model="catatan" />
            <div class="flex">
                <flux:spacer />
                <flux:button type="submit" wire:click="saveRejected" variant="primary">Submit</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
