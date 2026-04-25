<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Operasional') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    @if (session('status'))
                        <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($showDailySupportForm)
                        <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 space-y-3">
                            <p class="text-sm text-amber-900">
                                Kamu tetap bisa menggunakan POS ini tanpa biaya. Tapi dengan membayar semampunya, kamu akan mendukung kami untuk bisa menjaga dan mengembangkan sistem ini lebih baik. Mau dukung kami berapa hari ini? (rekomendasi: Rp. 20.000)
                            </p>

                            <form method="POST" action="{{ route('dukungan-harian.store') }}" class="space-y-3 sm:space-y-0 sm:flex sm:items-end sm:gap-3">
                                @csrf
                                <div class="sm:w-72">
                                    <x-input-label for="nominal" :value="__('Nominal Dukungan (Rp)')" />
                                    <x-text-input
                                        id="nominal"
                                        class="block mt-1 w-full"
                                        type="number"
                                        name="nominal"
                                        :value="old('nominal', 20000)"
                                        min="1"
                                        step="1"
                                        required
                                    />
                                    <x-input-error :messages="$errors->get('nominal')" class="mt-2" />
                                </div>

                                <x-primary-button>
                                    {{ __('Kirim Dukungan Hari Ini') }}
                                </x-primary-button>
                            </form>
                        </div>
                    @endif

                    <div>
                        <p class="text-sm text-gray-500">Selamat datang,</p>
                        <h3 class="text-xl font-semibold">{{ $user->name }}</h3>
                        <p class="text-gray-600">{{ $user->email }}</p>
                    </div>

                    <div>
                        <h4 class="font-semibold">Restoran yang Anda kelola</h4>
                        <ul class="mt-2 list-disc list-inside text-gray-700 space-y-1">
                            @forelse ($restorans as $restoran)
                                <li>
                                    {{ $restoran->nama }}
                                    <span class="text-gray-500">({{ $restoran->pivot->role }})</span>
                                </li>
                            @empty
                                <li>Belum ada restoran terhubung.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
