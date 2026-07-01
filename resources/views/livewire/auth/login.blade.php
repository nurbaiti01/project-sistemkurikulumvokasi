<x-layouts.auth>
    {{-- <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@example.com"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Password')"
                    viewable
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full" data-test="login-button">
                    {{ __('Log in') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600 dark:text-zinc-400">
                <span>{{ __('Don\'t have an account?') }}</span>
                <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
            </div>
        @endif
    </div> --}}
    <div
        class="w-full max-w-5xl bg-white dark:bg-gray-800 rounded-2xl shadow-lg overflow-hidden grid grid-cols-1 md:grid-cols-2">

        <!-- Left Section -->
        <div
            class="hidden md:flex flex-col justify-between bg-gradient-to-br from-orange-400 to-orange-500 p-10 text-white">
            <div>
                <h2 class="text-2xl font-bold leading-snug">
                    Kelola kurikulum <br> secara digital dan terintegrasi.
                </h2>
                <p class="mt-4 text-sm text-orange-100">
                    Sistem e-Kurikulum terpusat untuk mengelola capaian pembelajaran,
                    struktur mata kuliah, penilaian, dan dokumen akademik secara efisien.
                </p>
            </div>


            <div class="flex items-end justify-center">
                <img src="{{ asset('images/logo-polkam.png') }}" alt="Illustration" class="max-w-xs">
            </div>
        </div>

        <!-- Right Section -->
        <div class="p-8 md:p-12">
            <div class="mb-8">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-orange-500 rounded-lg flex items-center justify-center text-white font-bold">
                        E
                    </div>
                    <span class="font-semibold text-gray-800 dark:text-gray-100">
                        E Kurikulum
                    </span>
                </div>

                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">
                    Welcome Back
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Please login to your account
                </p>
            </div>

            <form class="space-y-5" method="POST" action="{{ route('login.store') }}">
                @csrf
                <div>
                    <flux:input name="email" :label="__('Email address')" :value="old('email')" type="email"
                        required autofocus autocomplete="email" placeholder="email@example.com" />
                </div>

                <div>
                    <flux:input name="password" :label="__('Password')" type="password" required
                        autocomplete="current-password" :placeholder="__('Password')" viewable />
                </div>

                <div class="flex justify-between">
                    <flux:checkbox name="remember" :label="__('Remember me')" :checked="old('remember')" />
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-sm text-orange-500 hover:underline">
                        Forgot password?
                    </a>
                </div>

                <button type="submit"
                    class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 rounded-lg font-semibold transition">
                    Login
                </button>

            </form>
        </div>
    </div>
</x-layouts.auth>
