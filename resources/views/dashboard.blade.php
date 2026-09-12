<x-app-layout>
    <div class="py-2">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Greeting --}}
            <div>
                <h1 class="text-[28px] font-bold text-gray-900 leading-tight">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h1>
                <p class="text-gray-500 text-sm mt-1">Selamat datang kembali di MediGuide AI 💜</p>
            </div>

            {{-- Ringkasan Profil Kesehatan --}}
            <div class="bg-white rounded-3xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 text-base">Ringkasan Profil Kesehatan</h3>
                    <a href="{{ route('health-profile.edit') }}" class="text-xs text-forest-700 font-medium border border-forest-200 px-3 py-1.5 rounded-full flex items-center gap-1 hover:bg-forest-50 transition">
                        Lihat Detail <span>&gt;</span>
                    </a>
                </div>

                @if ($healthProfile)
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center text-green-700 text-xl flex-shrink-0 relative">
                            👤
                            <span class="absolute -bottom-0.5 -right-0.5 w-4 h-4 bg-forest-700 rounded-full flex items-center justify-center text-white text-[9px]">♥</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">{{ Auth::user()->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $healthProfile->age ?? '-' }} thn &nbsp;·&nbsp; {{ $healthProfile->gender ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                        <div>
                            <div class="w-11 h-11 rounded-full bg-green-50 flex items-center justify-center mx-auto mb-1.5 text-lg">📅</div>
                            <p class="text-sm font-bold text-gray-800">{{ $healthProfile->age ?? '-' }} thn</p>
                            <p class="text-xs text-gray-400">Usia</p>
                        </div>
                        <div>
                            <div class="w-11 h-11 rounded-full bg-red-50 flex items-center justify-center mx-auto mb-1.5 text-lg">⚠️</div>
                            <p class="text-sm font-bold text-gray-800 leading-tight">{{ $healthProfile->allergies ? implode(', ', $healthProfile->allergies) : '-' }}</p>
                            <p class="text-xs text-gray-400">Alergi</p>
                        </div>
                        <div>
                            <div class="w-11 h-11 rounded-full bg-blue-50 flex items-center justify-center mx-auto mb-1.5 text-lg">🫁</div>
                            <p class="text-sm font-bold text-gray-800 leading-tight">{{ $healthProfile->chronic_diseases ? implode(', ', $healthProfile->chronic_diseases) : '-' }}</p>
                            <p class="text-xs text-gray-400">Penyakit Bawaan</p>
                        </div>
                        <div>
                            <div class="w-11 h-11 rounded-full bg-purple-50 flex items-center justify-center mx-auto mb-1.5 text-lg">💊</div>
                            <p class="text-sm font-bold text-gray-800 leading-tight">{{ $healthProfile->routine_medicines ? implode(', ', $healthProfile->routine_medicines) : '-' }}</p>
                            <p class="text-xs text-gray-400">Obat Rutin</p>
                        </div>
                    </div>
                @else
                    <p class="text-gray-500 text-sm mb-4">Lengkapi profilmu supaya rekomendasi AI lebih akurat dan personal.</p>
                    <a href="{{ route('health-profile.create') }}" class="inline-block bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                        Lengkapi Sekarang
                    </a>
                @endif
            </div>

            {{-- Fitur Utama --}}
            <div>
                <h3 class="font-bold text-gray-900 mb-3 text-base">Fitur Utama</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                    <div class="bg-green-50 rounded-3xl p-5 flex flex-col border border-green-100">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-4 text-2xl shadow-sm">🤖</div>
                        <h4 class="font-bold text-gray-900 mb-1.5">Konsultasi AI</h4>
                        <p class="text-sm text-gray-500 mb-5 flex-1">Tanyakan keluhanmu dan dapatkan informasi serta rekomendasi yang sesuai dengan profilmu.</p>
                        <a href="{{ route('consultation.index') }}" class="bg-forest-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl text-center hover:bg-forest-800 transition flex items-center justify-center gap-1">
                            Mulai Konsultasi <span>&gt;</span>
                        </a>
                    </div>

                    <div class="bg-blue-50 rounded-3xl p-5 flex flex-col border border-blue-100">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-4 text-2xl shadow-sm">📷</div>
                        <h4 class="font-bold text-gray-900 mb-1.5">Scan Obat</h4>
                        <p class="text-sm text-gray-500 mb-5 flex-1">Pindai kemasan obat untuk melihat informasi lengkap seperti kandungan, indikasi, dosis, dan efek samping.</p>
                        <a href="{{ route('medicine-scan.create') }}" class="bg-blue-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl text-center hover:bg-blue-700 transition flex items-center justify-center gap-1">
                            Scan Sekarang <span>&gt;</span>
                        </a>
                    </div>

                    <div class="bg-purple-50 rounded-3xl p-5 flex flex-col border border-purple-100">
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-4 text-2xl shadow-sm">⏰</div>
                        <h4 class="font-bold text-gray-900 mb-1.5">Pengingat Obat</h4>
                        <p class="text-sm text-gray-500 mb-5 flex-1">Atur jadwal minum obat dan dapatkan notifikasi agar tidak lupa minum obat.</p>
                        <a href="{{ route('reminder.index') }}" class="bg-indigo-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl text-center hover:bg-indigo-700 transition flex items-center justify-center gap-1">
                            Atur Pengingat <span>&gt;</span>
                        </a>
                    </div>

                </div>
            </div>

            {{-- Pengingat Obat Hari Ini --}}
            <div class="bg-white rounded-3xl shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-gray-900 text-base">Pengingat Obat Hari Ini</h3>
                    <a href="{{ route('reminder.index') }}" class="text-xs text-forest-700 font-medium border border-forest-200 px-3 py-1.5 rounded-full hover:bg-forest-50 transition">Lihat Semua</a>
                </div>

                @forelse ($reminders as $r)
                    @php
                        $hour = \Carbon\Carbon::parse($r->time)->hour;
                        $periodLabel = $hour < 11 ? 'Pagi' : ($hour < 15 ? 'Siang' : ($hour < 18 ? 'Sore' : 'Malam'));
                        $periodIcon = $hour < 15 ? '☀️' : ($hour < 18 ? '🌤️' : '🌙');
                        $timeColor = $hour < 11 ? 'text-green-600' : ($hour < 18 ? 'text-blue-600' : 'text-indigo-600');
                    @endphp
                    <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="text-center w-14 flex-shrink-0">
                                <p class="text-lg font-bold leading-none {{ $timeColor }}">{{ \Carbon\Carbon::parse($r->time)->format('H:i') }}</p>
                                <p class="text-[11px] text-gray-400 mt-0.5">{{ $periodLabel }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-cream-100 flex items-center justify-center text-lg flex-shrink-0">💊</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-800">{{ $r->medicine_name }}</p>
                                <p class="text-xs text-gray-400">{{ $r->dosage ?? '-' }} {{ $r->note ? '· '.$r->note : '' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            @if ($r->today_status === 'diminum')
                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1.5 rounded-full font-medium">Diminum</span>
                            @else
                                <span class="text-xs bg-green-50 text-green-600 px-3 py-1.5 rounded-full font-medium">Belum diminum</span>
                            @endif
                            <button class="w-8 h-8 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 flex-shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            </button>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">Belum ada pengingat obat.</p>
                    <a href="{{ route('reminder.create') }}" class="inline-block mt-2 text-forest-700 text-sm font-medium hover:underline">+ Tambah pengingat</a>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>