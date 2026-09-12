<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiAIService
{
    protected string $apiKey;
    protected string $model = 'google/gemini-2.5-flash';

    public function __construct()
    {
        $this->apiKey = config('services.openrouter.key');
    }

    public function chat(array $conversationHistory, string $systemContext = ''): string
    {
        $messages = [];

        if ($systemContext) {
            $messages[] = [
                'role' => 'system',
                'content' => $systemContext,
            ];
        }

        foreach ($conversationHistory as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['text'],
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => 1024,
        ]);

        if ($response->failed()) {
            \Log::error('OpenRouter API Error', ['response' => $response->json(), 'status' => $response->status()]);
            return 'ERROR: ' . $response->status() . ' - ' . json_encode($response->json());
        }

        $data = $response->json();

        return $data['choices'][0]['message']['content']
            ?? 'Maaf, saya tidak bisa memproses permintaan itu saat ini.';
    }

    /**
     * Chat dengan output terstruktur JSON (untuk konsultasi natural bertahap).
     */
    public function chatStructured(array $conversationHistory, string $systemContext): array
    {
        $messages = [];

        if ($systemContext) {
            $messages[] = ['role' => 'system', 'content' => $systemContext];
        }

        foreach ($conversationHistory as $msg) {
            $messages[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'assistant',
                'content' => $msg['text'],
            ];
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->model,
            'messages' => $messages,
            'max_tokens' => 1024,
        ]);

        if ($response->failed()) {
            \Log::error('Gemini Structured Chat Error', ['response' => $response->json()]);
            return ['message' => 'Maaf, terjadi kesalahan. Bisa diulangi?', 'quick_replies' => [], 'is_final' => false];
        }

        $text = $response->json()['choices'][0]['message']['content'] ?? '{}';
        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);

        if (!is_array($data) || !isset($data['message'])) {
            return ['message' => $text ?: 'Maaf, saya tidak bisa memproses itu.', 'quick_replies' => [], 'is_final' => false];
        }

        return $data;
    }

    public function analyzeMedicineImage(string $base64Image, string $mimeType): array
    {
        $prompt = <<<PROMPT
Kamu adalah sistem analisa kemasan obat yang berperan seperti database referensi obat Indonesia (mengacu pada jenis informasi yang biasa tersedia di Farmaplus Kemenkes dan direktori obat seperti KlikDokter). Lihat gambar kemasan obat berikut.

Baca informasi yang TERLIHAT LANGSUNG di kemasan (nama obat, dosis, bentuk sediaan, kemasan, produsen). Untuk informasi yang TIDAK terlihat di foto (komposisi lengkap, indikasi, efek samping, kontraindikasi, estimasi harga), lengkapi menggunakan pengetahuan umum farmasi yang kamu miliki tentang obat tersebut, SEPERTI yang biasanya tercantum di database resmi obat Indonesia.

Berikan output HANYA dalam format JSON persis seperti ini (tanpa markdown, tanpa penjelasan tambahan):

{
  "medicine_name": "nama obat lengkap dengan dosis, contoh: Paracetamol 500 mg",
  "category": "kategori obat, contoh: Analgesik - Antipiretik",
  "dosage_form": "bentuk sediaan, contoh: Tablet",
  "packaging": "kemasan, contoh: Dus, 10 Tablet",
  "manufacturer": "nama produsen/pabrik jika terlihat di kemasan, jika tidak terlihat isi 'Tidak tersedia'",
  "price_estimate": "estimasi kisaran harga eceran di Indonesia, contoh: Rp 1.000 - 2.000/tablet, jika tidak yakin isi 'Estimasi tidak tersedia'",
  "composition": "kandungan/komposisi zat aktif obat",
  "indication": "indikasi umum / kegunaan obat",
  "side_effects": "efek samping yang mungkin terjadi",
  "contraindication": "kontraindikasi / peringatan penggunaan / siapa yang tidak boleh minum obat ini"
}

Jika gambar tidak terlihat seperti kemasan obat, isi medicine_name dengan "Tidak dapat dikenali" dan field lain dengan "Tidak tersedia".
PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        ['type' => 'text', 'text' => $prompt],
                        ['type' => 'image_url', 'image_url' => [
                            'url' => "data:{$mimeType};base64,{$base64Image}",
                        ]],
                    ],
                ],
            ],
            'max_tokens' => 1024,
        ]);

        if ($response->failed()) {
            \Log::error('Gemini Vision Error', ['response' => $response->json()]);
            return ['medicine_name' => 'Gagal menganalisa gambar', 'error' => true];
        }

        $text = $response->json()['choices'][0]['message']['content'] ?? '{}';

        $text = preg_replace('/```json|```/', '', $text);
        $text = trim($text);

        $data = json_decode($text, true);

        return $data ?? ['medicine_name' => 'Gagal memproses hasil AI', 'error' => true];
    }
}