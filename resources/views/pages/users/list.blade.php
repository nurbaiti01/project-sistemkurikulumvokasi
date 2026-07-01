   <div>
       <div class="flex h-full container mx-auto flex-1 flex-col gap-4 rounded-xl p-10">
           <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Data Pengguna</h3>
           <div class="flex justify-between mb-3">
               <div class="relative flex w-full max-w-4xl flex-col gap-1 text-neutral-600 dark:text-neutral-300">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                       stroke="currentColor" aria-hidden="true"
                       class="absolute left-2.5 top-1/2 size-5 -translate-y-1/2 text-neutral-600/50 dark:text-neutral-300/50">
                       <path stroke-linecap="round" stroke-linejoin="round"
                           d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                   </svg>
                   <input type="search" wire:model.live.debounce.300ms="search"
                       class="w-full rounded-sm border border-neutral-300 bg-neutral-50 py-2 pl-10 pr-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-black disabled:cursor-not-allowed disabled:opacity-75 dark:border-neutral-700 dark:bg-neutral-900/50 dark:focus-visible:outline-white"
                       name="search" placeholder="Search" aria-label="search" />
               </div>
               <flux:dropdown>
                   <flux:button icon:trailing="chevron-down">
                       {{ match ($filter['status']) {
                           'trashed' => 'User Terhapus',
                           'withTrashed' => 'Semua + Terhapus',
                           default => 'Semua User',
                       } }}
                   </flux:button>

                   <flux:menu>
                       <flux:menu.radio.group wire:model.live="filter.status">
                           <flux:menu.radio value="all">
                               Semua User
                           </flux:menu.radio>

                           <flux:menu.radio value="trashed">
                               User Terhapus
                           </flux:menu.radio>

                           <flux:menu.radio value="withTrashed">
                               Semua + Terhapus
                           </flux:menu.radio>
                       </flux:menu.radio.group>
                   </flux:menu>
               </flux:dropdown>

               <button type="button" wire:click="openModalCreate()"
                   class="inline-flex justify-center items-center gap-2 whitespace-nowrap rounded-sm bg-sky-500 border border-sky-500 dark:border-sky-500 px-4 py-2 text-sm font-medium tracking-wide text-white transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-500 active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-sky-500 dark:text-white dark:focus-visible:outline-sky-500">
                   <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                       class="size-5 fill-white dark:fill-white" fill="currentColor">
                       <path fill-rule="evenodd"
                           d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                           clip-rule="evenodd" />
                   </svg>
                   Create
               </button>
           </div>
           <div class="relative overflow-visible w-full rounded-sm border border-neutral-300 dark:border-neutral-700">
               <table class="w-full text-left text-sm text-neutral-600 dark:text-neutral-300 overflow-visible"
                   id="table-list">
                   <thead
                       class="border-b border-neutral-300 bg-neutral-50 text-sm text-neutral-900 dark:border-neutral-700 dark:bg-neutral-900 dark:text-white">
                       <tr>
                           <th scope="col" class="p-4">No</th>
                           <th scope="col" class="p-4">Nama Pengguna</th>
                           <th scope="col" class="p-4">Email</th>
                           <th scope="col" class="p-4">Role</th>
                           <th scope="col" class="p-4">Action</th>
                       </tr>
                   </thead>
                   <tbody class="divide-y divide-neutral-300 dark:divide-neutral-700">
                       @forelse ($data as $no=>$list)
                           <tr>
                               <td class="p-4">{{ $no + 1 }}</td>
                               <td class="p-4">{{ $list->name }}</td>
                               <td class="p-4">{{ $list->email }}</td>
                               <td class="p-4">{{ $list->roles->pluck('name')->implode(', ') }}</td>
                               <td class="p-4">
                                   <div class="flex items-center gap-2">

                                       {{-- ================= MODE: USER AKTIF ================= --}}
                                       @if ($filter['status'] !== 'trashed')
                                           {{-- EDIT --}}
                                           <button type="button" wire:click="openModalEdit({{ $list->id }})"
                                               class="inline-flex justify-center items-center rounded bg-black p-2 text-white hover:opacity-75 dark:bg-white dark:text-black"
                                               aria-label="Edit User">
                                               {{-- icon edit --}}
                                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                   viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                   class="size-4">
                                                   <path stroke-linecap="round" stroke-linejoin="round"
                                                       d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                               </svg>
                                           </button>

                                           {{-- DELETE (SOFT) --}}
                                           <button type="button" wire:click="openModalDelete({{ $list->id }})"
                                               class="inline-flex justify-center items-center rounded bg-red-500 p-2 text-white hover:opacity-75"
                                               aria-label="Delete User">
                                               {{-- icon trash --}}
                                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                   viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                   class="size-4">
                                                   <path stroke-linecap="round" stroke-linejoin="round"
                                                       d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                               </svg>
                                           </button>
                                       @endif

                                       {{-- ================= MODE: USER TERHAPUS ================= --}}
                                       @if ($filter['status'] === 'trashed')
                                           {{-- RESTORE --}}
                                           <button type="button" wire:click="restore({{ $list->id }})"
                                               class="inline-flex justify-center items-center rounded bg-emerald-600 p-2 text-white hover:opacity-75"
                                               aria-label="Restore User">
                                               {{-- icon restore --}}
                                               <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                   viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                   class="size-4">
                                                   <path stroke-linecap="round" stroke-linejoin="round"
                                                       d="M4.5 12a7.5 7.5 0 1 0 7.5-7.5M4.5 12H9m-4.5 0V7.5" />
                                               </svg>
                                           </button>
                                       @endif

                                   </div>
                               </td>


                           </tr>
                       @empty
                           <tr>
                               <td class="p-4" colspan="5">Data Kosong</td>
                           </tr>
                       @endforelse

                   </tbody>
               </table>

           </div>
           <div class="my-5">
               {{ $data->onEachSide(0)->links(data: ['scrollTo' => '#table-list']) }}
           </div>
       </div>


       <flux:modal name="create-modal" class="md:w-full">
           <flux:heading size="lg">Form Tambah Pengguna</flux:heading>
           <livewire:users.create wire:key="create" />
       </flux:modal>
       @if ($showModalUpdate)
           <flux:modal name="update-modal" class="md:w-full">
               <livewire:users.update wire:key="update-form-{{ $selectedId }}" :userId="$selectedId" />
           </flux:modal>
       @endif
       @if ($showModalDelete)
           <flux:modal name="delete-modal" class="min-w-[22rem]">
               <div class="space-y-6">
                   <div>
                       <flux:heading size="lg">Delete data?</flux:heading>
                       <flux:text class="mt-2">
                           This action cannot be reversed.
                       </flux:text>
                   </div>
                   <div class="flex gap-2">
                       <flux:spacer />
                       <flux:modal.close>
                           <flux:button variant="ghost">Cancel</flux:button>
                       </flux:modal.close>
                       <flux:button type="submit" wire:click="delete()" variant="danger">Delete project
                       </flux:button>
                   </div>
               </div>
           </flux:modal>
       @endif

   </div>
