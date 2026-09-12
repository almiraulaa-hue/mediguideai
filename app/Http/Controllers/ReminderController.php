<?php

namespace App\Http\Controllers;

use App\Models\MedicineReminder;
use App\Models\ReminderLog;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function index(Request $request)
{
    $user = $request->user();
    $reminders = $user->medicineReminders()->where('is_active', true)->orderBy('time')->get();

    // Hitung kepatuhan 7 hari terakhir
    $reminderIds = $user->medicineReminders()->pluck('id');
    $logs7Days = \App\Models\ReminderLog::whereIn('medicine_reminder_id', $reminderIds)
        ->whereBetween('scheduled_date', [now()->subDays(6)->startOfDay(), now()->endOfDay()])
        ->get();

    $totalLogs = $logs7Days->count();
    $takenLogs = $logs7Days->where('status', 'diminum')->count();
    $compliancePercentage = $totalLogs > 0 ? round(($takenLogs / $totalLogs) * 100) : 0;

    // Riwayat pengingat terbaru (10 terakhir)
    $recentLogs = \App\Models\ReminderLog::whereIn('medicine_reminder_id', $reminderIds)
        ->with('reminder')
        ->latest('scheduled_date')
        ->take(10)
        ->get();

    return view('reminder.index', compact('reminders', 'compliancePercentage', 'recentLogs'));
}

    public function create()
    {
        return view('reminder.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'medicine_name' => 'required|string|max:255',
            'dosage_form' => 'nullable|string|max:100',
            'dosage' => 'nullable|string|max:100',
            'note' => 'nullable|string',
            'time' => 'required',
            'start_date' => 'required|date',
            'frequency' => 'required|in:daily,custom',
            'repeat_days' => 'nullable|array',
        ]);

        $request->user()->medicineReminders()->create($validated);

        return redirect()->route('reminder.index')->with('success', 'Pengingat berhasil ditambahkan!');
    }

    public function destroy(Request $request, MedicineReminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            abort(403);
        }
        $reminder->delete();
        return redirect()->route('reminder.index')->with('success', 'Pengingat dihapus.');
    }

    // Tandai sudah diminum hari ini
    public function markTaken(Request $request, MedicineReminder $reminder)
    {
        if ($reminder->user_id !== $request->user()->id) {
            abort(403);
        }

        ReminderLog::updateOrCreate(
            [
                'medicine_reminder_id' => $reminder->id,
                'scheduled_date' => today(),
            ],
            [
                'status' => 'diminum',
                'taken_at' => now(),
            ]
        );

        return redirect()->route('reminder.index')->with('success', 'Ditandai sudah diminum!');
    }
}