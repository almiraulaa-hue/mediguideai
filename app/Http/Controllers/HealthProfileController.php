<?php

namespace App\Http\Controllers;

use App\Models\HealthProfile;
use Illuminate\Http\Request;

class HealthProfileController extends Controller
{
    public function create()
    {
        return view('health-profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'blood_type' => 'nullable|string|max:3',
            'height' => 'nullable|integer',
            'allergies' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'routine_medicines' => 'nullable|string',
            'other_conditions' => 'nullable|string',
        ]);

        // Ubah input text (dipisah koma) jadi array untuk kolom JSON
        $validated['allergies'] = $this->toArray($request->allergies);
        $validated['chronic_diseases'] = $this->toArray($request->chronic_diseases);
        $validated['routine_medicines'] = $this->toArray($request->routine_medicines);

        $request->user()->healthProfile()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Profil kesehatan berhasil disimpan!');
    }

    public function edit(Request $request)
{
    $healthProfile = $request->user()->healthProfile;

    $stats = [
        'consultations' => $request->user()->consultations()->count(),
        'scans' => $request->user()->medicineScans()->count(),
        'reminders' => $request->user()->medicineReminders()->where('is_active', true)->count(),
    ];

    return view('health-profile.edit', compact('healthProfile', 'stats'));
}

    public function update(Request $request)
    {
        $validated = $request->validate([
            'birth_date' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'blood_type' => 'nullable|string|max:3',
            'height' => 'nullable|integer',
            'allergies' => 'nullable|string',
            'chronic_diseases' => 'nullable|string',
            'routine_medicines' => 'nullable|string',
            'other_conditions' => 'nullable|string',
        ]);

        $validated['allergies'] = $this->toArray($request->allergies);
        $validated['chronic_diseases'] = $this->toArray($request->chronic_diseases);
        $validated['routine_medicines'] = $this->toArray($request->routine_medicines);

        $request->user()->healthProfile()->update($validated);

        return redirect()->route('dashboard')->with('success', 'Profil kesehatan berhasil diperbarui!');
    }

    private function toArray($text)
    {
        if (!$text) return [];
        return array_map('trim', explode(',', $text));
    }
}