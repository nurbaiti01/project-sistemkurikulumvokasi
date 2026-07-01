<div>
    @foreach (auth()->user()->roles as $role)
        <button wire:click="switchRole({{ $role->id }})"
            class="flex w-full items-center justify-between px-3 py-2 text-sm rounded-lg transition-colors {{ session('active_role_id') == $role->id ? 'bg-blue-50 text-blue-700' : 'text-neutral-600 hover:bg-neutral-50' }}">
            <span class="flex items-center">
                <svg class="w-4 h-4 mr-2 opacity-70" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>

               
                {{ $role->name }}
            </span>
            @if (session('active_role_id') == $role->id)
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" />
                </svg>
            @endif
        </button>
    @endforeach
</div>
