<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $healthProfile = $user->healthProfile;

        $reminders = $user->medicineReminders()
            ->where('is_active', true)
            ->orderBy('time')
            ->get();

        return view('dashboard', compact('healthProfile', 'reminders'));
    }
}