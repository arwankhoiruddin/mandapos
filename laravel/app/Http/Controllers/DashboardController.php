<?php

namespace App\Http\Controllers;

use App\Models\DukunganHarian;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('restorans');
        $showDailySupportForm = $user->isOwner()
            && ! DukunganHarian::where('user_id', $user->id)
                ->whereDate('tanggal', today())
                ->exists();

        return view('dashboard', [
            'user' => $user,
            'restorans' => $user->restorans,
            'showDailySupportForm' => $showDailySupportForm,
        ]);
    }
}
