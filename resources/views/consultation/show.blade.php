<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <a href="{{ route('consultation.index') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <h1 class="font-semibold text-gray-800">Konsultasi AI</h1>
                </div>
            </div>

            @if ($healthProfile = auth()->user()->healthProfile)
                <div class="bg-cream-50 border border-cream-200 rounded-xl p-3 mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Ringkasan Profil Kesehatan</span>
                        <a href="{{ route('health-profile.edit') }}" class="text-xs text-forest-700">Edit</a>
                    </div>
                    <div class="text-xs text-gray-600 space-y-1">
                        <p>📅 {{ $healthProfile->age ?? '-' }} thn &nbsp;·&nbsp; {{ $healthProfile->gender ?? '-' }}</p>
                        @if ($healthProfile->allergies)
                            <p>⚠️ Alergi: {{ implode(', ', $healthProfile->allergies) }}</p>
                        @endif
                        @if ($healthProfile->routine_medicines)
                            <p>💊 Obat Rutin: {{ implode(', ', $healthProfile->routine_medicines) }}</p>
                        @endif
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm p-4 mb-4 min-h-[420px] flex flex-col gap-4" id="chatWindow">

                @foreach ($messages as $msg)
                    @if ($msg->sender === 'user')
                        <div class="flex justify-end">
                            <div class="bg-forest-700 text-white rounded-2xl rounded-br-sm px-4 py-2.5 max-w-[80%] text-sm">
                                {{ $msg->message }}
                            </div>
                        </div>
                    @else
                        <div class="flex items-start gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-forest-800 flex items-center justify-center text-white text-sm flex-shrink-0">🤖</div>
                            <div class="bg-cream-100 text-gray-800 rounded-2xl rounded-tl-sm px-4 py-3 max-w-[80%] text-sm whitespace-pre-line">
                                {{ $msg->message }}
                            </div>
                        </div>
                    @endif
                @endforeach

                @php $lastMessage = $messages->last(); @endphp
                @if ($lastMessage && $lastMessage->sender === 'ai' && !empty($lastMessage->quick_replies))
                    <div class="flex flex-wrap gap-2 ml-10">
                        @foreach ($lastMessage->quick_replies as $reply)
                            <button type="button" onclick="sendQuickReply('{{ addslashes($reply) }}')"
                                class="text-xs border border-forest-700 text-forest-700 px-3 py-1.5 rounded-full hover:bg-forest-700 hover:text-white transition">
                                {{ $reply }}
                            </button>
                        @endforeach
                    </div>
                @endif

            </div>

            <form method="POST" action="{{ route('consultation.message', $consultation) }}" class="flex gap-2" id="chatForm">
                @csrf
                <input id="chatInput" type="text" name="message" placeholder="Ketik jawabanmu..." class="flex-1 rounded-xl border-gray-300 focus:border-forest-700 focus:ring-forest-700 text-sm" required autofocus>
                <button type="submit" class="bg-forest-700 text-white w-11 h-11 rounded-xl hover:bg-forest-800 transition flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                </button>
            </form>

            <p class="text-xs text-gray-400 mt-3 text-center">
                ⚠️ Informasi ini tidak menggantikan konsultasi medis. Konsultasikan dengan tenaga medis profesional.
            </p>

        </div>
    </div>

    <script>
        function sendQuickReply(text) {
            document.getElementById('chatInput').value = text;
            document.getElementById('chatForm').submit();
        }

        // Auto-scroll ke bawah chat window
        const chatWindow = document.getElementById('chatWindow');
        if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;
    </script>
</x-app-layout>