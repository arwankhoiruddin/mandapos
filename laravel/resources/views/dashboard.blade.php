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
