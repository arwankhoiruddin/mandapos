<?php

namespace App\Http\Controllers;

use App\Models\DukunganHarian;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OwnerDailySupportController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless($user && $user->isOwner(), 403);

        $data = $request->validate([
            'nominal' => ['required', 'integer', 'min:1', 'max:1000000000'],
        ]);

        DukunganHarian::updateOrCreate(
            [
                'user_id' => $user->id,
                'tanggal' => now()->toDateString(),
            ],
            [
                'nominal' => $data['nominal'],
            ]
        );

        return redirect()
            ->route('dashboard')
            ->with('status', 'Terima kasih atas dukungan Anda hari ini.');
    }
}
