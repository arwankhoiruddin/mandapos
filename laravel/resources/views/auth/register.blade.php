<x-guest-layout>
    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-orange-600">Mulai gratis</p>
    <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">Buat akun MandaPOS</h1>
    <p class="mt-2 text-sm leading-6 text-gray-600">
        Cocok untuk tim kecil sampai banyak cabang. Satu akun bisa membantu Anda mengelola operasional harian dengan lebih teratur.
    </p>

    <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
        Pendaftaran hanya untuk akun owner restoran.
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" />
            <x-text-input id="name" class="mt-1 block w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="mt-1 block w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="nama_restoran" :value="__('Nama Restoran Pertama')" />
            <x-text-input id="nama_restoran" class="mt-1 block w-full" type="text" name="nama_restoran" :value="old('nama_restoran')" required autocomplete="organization" />
            <x-input-error :messages="$errors->get('nama_restoran')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Kata Sandi')" />
            <x-text-input id="password" class="mt-1 block w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
            <x-text-input id="password_confirmation" class="mt-1 block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button
            type="submit"
            class="inline-flex w-full items-center justify-center rounded-md border border-transparent bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2"
        >
            {{ __('Daftar') }}
        </button>

        <p class="text-center text-sm text-gray-600">
            {{ __('Sudah punya akun?') }}
            <a class="font-medium text-orange-700 underline-offset-4 hover:underline" href="{{ route('login') }}">
                {{ __('Login di sini') }}
            </a>
        </p>
    </form>
</x-guest-layout>
