<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user()->load('restorans');

        return view('dashboard', [
            'user' => $user,
            'restorans' => $user->restorans,
        ]);
    }
}
