<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-600">Selamat datang kembali</p>
    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Login ke akun MandaPOS</h1>
    <p class="mt-2 text-sm leading-6 text-gray-600">
        Lanjutkan operasional restoran Anda dengan lebih rapi. Pantau penjualan, tim, dan performa cabang dalam satu tempat.
    </p>

    <x-auth-session-status class="mt-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-gray-600">
            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500" name="remember">
            <span>{{ __('Ingat saya di perangkat ini') }}</span>
        </label>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            {{ __('Login') }}
        </button>

        <div class="flex items-center justify-between gap-2 text-sm">
            @if (Route::has('password.request'))
                <a class="font-medium text-orange-700 underline-offset-4 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif

            <a class="font-medium text-gray-700 underline-offset-4 hover:underline" href="{{ route('register') }}">
                {{ __('Belum punya akun? Daftar') }}
            </a>
        </div>
    </form>
</x-guest-layout>
