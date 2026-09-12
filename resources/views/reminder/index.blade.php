<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <h1 class="font-bold text-gray-900 text-lg">Pengingat Obat</h1>
                </div>
                <a href="{{ route('reminder.create') }}" class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center hover:bg-indigo-700 transition flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                </a>
            </div>

            {{-- Pengingat Aktif --}}
            <a href="#" class="block bg-white rounded-2xl shadow-sm p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-indigo-50 flex items-center justify-center text-lg flex-shrink-0">🔔</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">Pengingat Aktif</p>
                            <p class="text-xs text-gray-400">Jadwal hari ini</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold text-gray-700">{{ $reminders->count() }}</span>
                        <svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </a>

            {{-- Tabs --}}
            <div x-data="{ tab: 'hari-ini' }">
                <div class="flex gap-1 bg-white rounded-xl p-1 shadow-sm text-sm">
                    <button @click="tab = 'hari-ini'" :class="tab === 'hari-ini' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-400'" class="flex-1 py-2 rounded-lg font-medium transition">Hari Ini</button>
                    <button @click="tab = 'semua'" :class="tab === 'semua' ? 'bg-indigo-100 text-indigo-700' : 'text-gray-400'" class="flex-1 py-2 rounded-lg font-medium transition">Semua Jadwal</button>
                </div>

                {{-- Hari Ini --}}
                <div x-show="tab === 'hari-ini'" class="space-y-3 mt-3">
                    @forelse ($reminders as $r)
                        @php
                            $hour = \Carbon\Carbon::parse($r->time)->hour;
                            $periodLabel = $hour < 11 ? 'Pagi' : ($hour < 15 ? 'Siang' : ($hour < 18 ? 'Sore' : 'Malam'));
                            $periodIcon = $hour < 15 ? '☀️' : ($hour < 18 ? '🌤️' : '🌙');
                        @endphp
                        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="text-center flex-shrink-0 w-16">
                                    <p class="text-xl font-bold text-gray-800 leading-none">{{ \Carbon\Carbon::parse($r->time)->format('H:i') }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $periodLabel }}</p>
                                    <p class="text-xs mt-0.5">{{ $periodIcon }}</p>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $r->medicine_name }}</h4>
                                    <p class="text-xs text-gray-400 mb-1.5">{{ $r->dosage ?? '-' }}</p>
                                    @if ($r->today_status === 'diminum')
                                        <span class="text-xs bg-green-100 text-green-700 px-2.5 py-1 rounded-full font-medium">Diminum</span>
                                    @else
                                        <form method="POST" action="{{ route('reminder.taken', $r) }}">
                                            @csrf
                                            <button type="submit" class="text-xs bg-cream-100 text-gray-600 px-2.5 py-1 rounded-full font-medium hover:bg-indigo-600 hover:text-white transition">
                                                Belum diminum
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <button class="text-gray-300 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                            </button>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400 text-sm">
                            Belum ada pengingat obat hari ini.
                        </div>
                    @endforelse
                </div>

                {{-- Semua Jadwal --}}
                <div x-show="tab === 'semua'" class="space-y-3 mt-3">
                    @forelse ($reminders as $r)
                        <div class="bg-white rounded-2xl shadow-sm p-4 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 flex flex-col items-center justify-center text-indigo-700 flex-shrink-0">
                                    <span class="text-sm font-bold leading-none">{{ \Carbon\Carbon::parse($r->time)->format('H:i') }}</span>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $r->medicine_name }}</h4>
                                    <p class="text-xs text-gray-400">{{ $r->frequency === 'daily' ? 'Setiap hari' : implode(', ', $r->repeat_days ?? []) }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('reminder.destroy', $r) }}" onsubmit="return confirm('Hapus pengingat ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-300 hover:text-red-500 p-1">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400 text-sm">
                            Belum ada pengingat obat.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Tips --}}
            <div class="bg-indigo-50 rounded-2xl p-4 flex items-center gap-3">
                <div class="text-3xl flex-shrink-0">💊</div>
                <div>
                    <p class="text-sm font-semibold text-indigo-900">Tips Minum Obat</p>
                    <p class="text-xs text-indigo-700 mt-0.5">Minum obat secara teratur sesuai jadwal untuk hasil yang optimal.</p>
                </div>
            </div>

            {{-- Riwayat Kepatuhan --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-gray-800 text-sm">Riwayat Kepatuhan</h3>
                    <span class="text-xs text-gray-400">7 Hari Terakhir</span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="relative w-20 h-20 flex-shrink-0">
                        <svg class="w-20 h-20 -rotate-90">
                            <circle cx="40" cy="40" r="34" stroke="#E5E7EB" stroke-width="8" fill="none" />
                            <circle cx="40" cy="40" r="34" stroke="#4F46E5" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 2 * 3.14159 * 34 }}"
                                stroke-dashoffset="{{ 2 * 3.14159 * 34 * (1 - $compliancePercentage / 100) }}"
                                stroke-linecap="round" />
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-lg font-bold text-gray-800">{{ $compliancePercentage }}%</span>
                        </div>
                    </div>
                    <div>
                        @if ($compliancePercentage >= 80)
                            <p class="text-sm font-semibold text-gray-800">Kepatuhan Baik</p>
                            <p class="text-xs text-gray-400">Kamu sangat konsisten! Pertahankan ya 💚</p>
                        @elseif ($compliancePercentage >= 50)
                            <p class="text-sm font-semibold text-gray-800">Kepatuhan Cukup</p>
                            <p class="text-xs text-gray-400">Yuk lebih rutin minum obat sesuai jadwal.</p>
                        @else
                            <p class="text-sm font-semibold text-gray-800">Perlu Ditingkatkan</p>
                            <p class="text-xs text-gray-400">Jangan lupa minum obat sesuai jadwal ya!</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Riwayat Pengingat --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-800 text-sm">Riwayat Pengingat</h3>
                    <span class="text-xs text-indigo-600 font-medium">Lihat Semua</span>
                </div>

                <div class="space-y-1">
                    @forelse ($recentLogs as $log)
                        <div class="flex items-center justify-between py-2 {{ !$loop->last ? 'border-b border-gray-50' : '' }}">
                            <div class="flex items-center gap-2.5">
                                <span class="text-lg">{{ $log->status === 'diminum' ? '💊' : ($log->status === 'terlewat' ? '⏰' : '⏳') }}</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">{{ $log->reminder->medicine_name ?? '-' }}</p>
                                    <p class="text-xs text-gray-400">{{ $log->scheduled_date->translatedFormat('d M') }} · {{ \Carbon\Carbon::parse($log->reminder->time)->format('H:i') }}</p>
                                </div>
                            </div>
                            <span class="text-xs px-2.5 py-1 rounded-full font-medium
                                {{ $log->status === 'diminum' ? 'bg-green-100 text-green-700' : ($log->status === 'terlewat' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-400">Belum ada riwayat pengingat.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('reminder.create') }}" class="block text-center border-2 border-indigo-600 text-indigo-600 px-6 py-3 rounded-xl hover:bg-indigo-600 hover:text-white transition font-medium">
                + Tambah Pengingat Baru
            </a>

        </div>
    </div>
</x-app-layout>