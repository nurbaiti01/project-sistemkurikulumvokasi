<section
    class="sticky top-0 z-40 w-full border-b border-gray-200 dark:border-gray-800
           bg-[#FFD41D] dark:bg-gray-900">
    <div class="container mx-auto max-w-7xl px-4">

        <div class="flex h-16 items-center justify-between">

            <!-- BRAND -->
            <a href="#"
               class="flex items-center gap-3 font-semibold text-gray-800 dark:text-gray-100">
                <img src="{{ asset('images/logo-polkam.png') }}"
                     class="w-9 h-9 rounded-md object-cover"
                     alt="Politeknik Kampar">
                <span class="hidden sm:block text-lg tracking-tight">
                    Politeknik Kampar
                </span>
            </a>

            <!-- USER MENU -->
            <div x-data="{ open: false }" class="relative">
                <button
                    @click="open = !open"
                    class="flex items-center gap-3 rounded-lg px-2 py-1.5
                           hover:bg-gray-100 dark:hover:bg-gray-800
                           focus:outline-none focus:ring-2 focus:ring-indigo-500">

                    <!-- Avatar -->
                    <div class="relative">
                        <img src="{{ asset('images/logo-polkam.png') }}"
                             class="w-9 h-9 rounded-full object-cover
                                    border border-gray-200 dark:border-gray-700"
                             alt="Profile">

                        <!-- Online indicator -->
                        <span
                            class="absolute bottom-0 right-0 h-2.5 w-2.5
                                   rounded-full bg-green-500
                                   border-2 border-white dark:border-gray-900">
                        </span>
                    </div>

                    <!-- User Info -->
                    <div class="hidden md:flex flex-col text-left leading-tight">
                        <span class="text-sm font-medium text-gray-800 dark:text-gray-100">
                            {{ auth()->user()->name }}
                        </span>
                        <span class="text-[11px] uppercase tracking-wide
                                     text-gray-500 dark:text-gray-400">
                            {{ session('active_role', 'No Role') }}
                        </span>
                    </div>

                    <!-- Arrow -->
                    <svg class="hidden md:block w-4 h-4 text-gray-400 transition-transform"
                         :class="open ? 'rotate-180' : ''"
                         xmlns="http://www.w3.org/2000/svg"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- DROPDOWN -->
                <div
                    x-show="open"
                    @click.away="open = false"
                    x-cloak
                    x-transition
                    class="absolute right-0 mt-2 w-64 rounded-xl
                           bg-white dark:bg-gray-800
                           border border-gray-200 dark:border-gray-700
                           shadow-lg">

                    <!-- Header -->
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700">
                        <p class="text-xs font-semibold uppercase tracking-widest
                                  text-gray-400">
                            Account
                        </p>
                    </div>

                    <!-- Role Switch -->
                    <div class="px-2 py-2 space-y-1">
                        <livewire:actions.switch-roles />
                    </div>

                    <div class="h-px bg-gray-100 dark:bg-gray-700 mx-4"></div>

                    <!-- Actions -->
                    <div class="px-2 py-2 space-y-1">

                        <a href="{{ route('profile.edit') }}" wire:navigate
                           class="flex items-center gap-3 rounded-lg px-3 py-2
                                  text-sm text-gray-600 dark:text-gray-300
                                  hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-4 h-4 text-gray-400"
                                 fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M16 7a4 4 0 11-8 0
                                         4 4 0 018 0ZM12 14
                                         a7 7 0 00-7 7h14
                                         a7 7 0 00-7-7z" />
                            </svg>
                            Manage Profile
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                class="flex w-full items-center gap-3 rounded-lg
                                       px-3 py-2 text-sm
                                       text-red-600 dark:text-red-400
                                       hover:bg-red-50 dark:hover:bg-red-900/30">
                                <svg class="w-4 h-4"
                                     fill="none" stroke="currentColor"
                                     viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M17 16l4-4m0 0l-4-4
                                             m4 4H7m6 4v1
                                             a3 3 0 01-3 3H6
                                             a3 3 0 01-3-3V7
                                             a3 3 0 013-3h4
                                             a3 3 0 013 3v1" />
                                </svg>
                                Sign Out
                            </button>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
