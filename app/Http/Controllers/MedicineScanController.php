<?php

namespace App\Http\Controllers;

use App\Models\MedicineScan;
use App\Services\GeminiAIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicineScanController extends Controller
{
    public function index(Request $request)
    {
        $scans = $request->user()->medicineScans()->latest()->get();
        return view('medicine-scan.index', compact('scans'));
    }

    public function create()
    {
        return view('medicine-scan.create');
    }

    public function store(Request $request, GeminiAIService $gemini)
    {
        $request->validate([
            'photo' => 'required|image|max:5120', // max 5MB
        ]);

        $file = $request->file('photo');
        $base64Image = base64_encode(file_get_contents($file->getRealPath()));
        $mimeType = $file->getMimeType();

        // Analisa pakai Gemini vision
        $result = $gemini->analyzeMedicineImage($base64Image, $mimeType);

        // Simpan foto ke storage
        $path = $file->store('medicine-scans', 'public');

        $scan = $request->user()->medicineScans()->create([
    'medicine_name' => $result['medicine_name'] ?? 'Tidak dikenali',
    'category' => $result['category'] ?? null,
    'dosage_form' => $result['dosage_form'] ?? null,
    'packaging' => $result['packaging'] ?? null,
    'manufacturer' => $result['manufacturer'] ?? null,
    'price_estimate' => $result['price_estimate'] ?? null,
    'composition' => $result['composition'] ?? null,
    'indication' => $result['indication'] ?? null,
    'side_effects' => $result['side_effects'] ?? null,
    'contraindication' => $result['contraindication'] ?? null,
    'image_path' => $path,
]);

        return redirect()->route('medicine-scan.show', $scan);
    }

    public function show(Request $request, MedicineScan $scan)
    {
        if ($scan->user_id !== $request->user()->id) {
            abort(403);
        }
        return view('medicine-scan.show', compact('scan'));
    }

    public function destroy(Request $request, MedicineScan $scan)
    {
        if ($scan->user_id !== $request->user()->id) {
            abort(403);
        }
        if ($scan->image_path) {
            Storage::disk('public')->delete($scan->image_path);
        }
        $scan->delete();
        return redirect()->route('medicine-scan.index')->with('success', 'Riwayat scan dihapus.');
    }
}