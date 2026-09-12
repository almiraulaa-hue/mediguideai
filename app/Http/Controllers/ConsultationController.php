<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Services\GeminiAIService;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    public function index(Request $request)
    {
        $consultations = $request->user()->consultations()->latest()->get();
        return view('consultation.index', compact('consultations'));
    }

    public function create(Request $request)
    {
        $consultation = $request->user()->consultations()->create(['status' => 'ongoing']);

        $consultation->messages()->create([
            'sender' => 'ai',
            'message' => "Halo, {$request->user()->name}! 👋 Saya MediGuide AI, asisten kesehatan pribadimu.\n\nApa keluhan yang kamu rasakan hari ini?",
            'quick_replies' => ['Demam', 'Batuk & Pilek', 'Sakit Kepala', 'Sakit Perut'],
        ]);

        return redirect()->route('consultation.show', $consultation);
    }

    public function show(Request $request, Consultation $consultation)
    {
        if ($consultation->user_id !== $request->user()->id) {
            abort(403);
        }

        $messages = $consultation->messages()->orderBy('created_at')->get();

        return view('consultation.show', compact('consultation', 'messages'));
    }

    public function sendMessage(Request $request, Consultation $consultation, GeminiAIService $gemini)
    {
        if ($consultation->user_id !== $request->user()->id) {
            abort(403);
        }

        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        if (!$consultation->main_complaint) {
            $consultation->update(['main_complaint' => $request->message]);
        }

        $consultation->messages()->create([
            'sender' => 'user',
            'message' => $request->message,
        ]);

        $healthProfile = $request->user()->healthProfile;

        $context = "Kamu adalah MediGuide AI, asisten farmasi digital yang ramah dan empatik, mengobrol layaknya apoteker sungguhan — BUKAN seperti formulir atau chatbot kaku.\n\n";
        $context .= "ATURAN PERCAKAPAN:\n";
        $context .= "1. Ajukan HANYA SATU pertanyaan setiap balasan. Jangan menumpuk banyak pertanyaan sekaligus.\n";
        $context .= "2. Sesuaikan pertanyaan lanjutan dengan jawaban sebelumnya. Jangan tanya hal yang sudah dijawab.\n";
        $context .= "3. Boleh sertakan 2-4 pilihan singkat (quick_replies) yang relevan untuk memudahkan user, tapi user tetap bisa mengetik bebas.\n";
        $context .= "4. Jangan paksa menanyakan semua hal (durasi, keparahan, gejala lain, riwayat obat) jika keluhan sudah cukup jelas/sederhana untuk dikasih rekomendasi. AI yang menentukan kapan info sudah cukup.\n";
        $context .= "5. Gunakan bahasa Indonesia yang hangat dan mudah dipahami, gunakan kalimat pendek.\n\n";

        $context .= "ATURAN REKOMENDASI OBAT (saat is_final = true):\n";
        $context .= "1. SELALU sebutkan minimal 1-3 nama obat OTC spesifik (obat bebas/bebas terbatas legal di apotek Indonesia) yang relevan, beserta alasan singkat.\n";
        $context .= "2. Sertakan estimasi dosis umum, dan ingatkan membaca aturan pakai di kemasan.\n";
        $context .= "3. JANGAN merekomendasikan obat keras/resep/antibiotik.\n";
        $context .= "4. WAJIB cek silang dengan alergi & riwayat penyakit pasien — jika berpotensi kontraindikasi, JANGAN rekomendasikan, beri alternatif aman.\n";
        $context .= "5. Jika gejala berat/berbahaya, PRIORITASKAN saran ke dokter/IGD di atas rekomendasi obat.\n";
        $context .= "6. Sertakan saran non-obat sebagai pelengkap (istirahat, cairan, dll).\n";
        $context .= "7. Tutup dengan pengingat ini bukan pengganti diagnosis dokter.\n\n";

        if ($healthProfile) {
            $context .= "Profil pasien:\n";
            $context .= "- Usia: " . ($healthProfile->age ?? '-') . " tahun, " . ($healthProfile->gender ?? '-') . "\n";
            if ($healthProfile->allergies) $context .= "- Alergi (WAJIB DIHINDARI): " . implode(', ', $healthProfile->allergies) . "\n";
            if ($healthProfile->chronic_diseases) $context .= "- Riwayat Penyakit: " . implode(', ', $healthProfile->chronic_diseases) . "\n";
            if ($healthProfile->routine_medicines) $context .= "- Obat Rutin (cek interaksi): " . implode(', ', $healthProfile->routine_medicines) . "\n";
            $context .= "\n";
        }

        $context .= "FORMAT OUTPUT WAJIB — HANYA JSON valid, TANPA markdown, TANPA teks lain di luar JSON:\n";
        $context .= '{
  "message": "isi balasan kamu ke user dalam Bahasa Indonesia",
  "quick_replies": ["opsi1", "opsi2"],
  "is_final": false,
  "summary": null
}' . "\n\n";
        $context .= "Keterangan field:\n";
        $context .= "- quick_replies: array string pilihan cepat (2-4 opsi) yang relevan dengan pertanyaanmu. Kosongkan [] jika pertanyaan butuh jawaban bebas/tidak cocok dengan pilihan.\n";
        $context .= "- is_final: true HANYA jika message ini sudah berisi rekomendasi akhir/kesimpulan konsultasi (bukan pertanyaan lanjutan lagi).\n";
        $context .= "- summary: jika is_final true, isi objek {\"duration\": \"...\", \"severity\": \"...\", \"additional_symptoms\": \"...\", \"has_taken_medicine\": \"...\"} berdasarkan info yang terkumpul dari percakapan. Jika is_final false, biarkan null.\n";

        $history = $consultation->messages()->orderBy('created_at')->get()->map(function ($msg) {
            return ['role' => $msg->sender, 'text' => $msg->message];
        })->toArray();

        $result = $gemini->chatStructured($history, $context);

        $aiMessage = $result['message'] ?? 'Maaf, saya mengalami kendala. Bisa diulangi?';
        $quickReplies = $result['quick_replies'] ?? [];
        $isFinal = $result['is_final'] ?? false;

        $consultation->messages()->create([
            'sender' => 'ai',
            'message' => $aiMessage,
            'quick_replies' => $quickReplies,
        ]);

        if ($isFinal && $consultation->status === 'ongoing') {
            $summary = $result['summary'] ?? [];
            $consultation->update([
                'status' => 'completed',
                'title' => \Str::limit($consultation->main_complaint, 40),
                'duration' => $summary['duration'] ?? null,
                'severity' => $summary['severity'] ?? null,
                'additional_symptoms' => $summary['additional_symptoms'] ?? null,
                'has_taken_medicine' => $summary['has_taken_medicine'] ?? null,
                'ai_recommendation' => ['text' => $aiMessage],
            ]);
        }

        return redirect()->route('consultation.show', $consultation);
    }
}