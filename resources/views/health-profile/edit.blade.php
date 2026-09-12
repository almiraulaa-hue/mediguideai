<x-app-layout>
    <div class="py-6" x-data="{ editing: false }">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </a>
                    <h1 class="font-bold text-gray-900 text-lg">Profil Kesehatan</h1>
                </div>
                <button @click="editing = !editing" type="button" class="text-sm text-forest-700 border border-forest-700 px-4 py-1.5 rounded-full font-medium flex items-center gap-1.5 hover:bg-forest-700 hover:text-white transition">
                    <span x-text="editing ? 'Batal' : 'Edit Profil'"></span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                </button>
            </div>

            {{-- ============ VIEW MODE ============ --}}
            <div x-show="!editing" x-cloak class="space-y-5">

                {{-- Card Utama --}}
                <div class="bg-white rounded-3xl shadow-sm p-5">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-full bg-forest-100 flex items-center justify-center text-forest-800 text-2xl font-bold flex-shrink-0" style="background:#DCEADF">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-1.5">
                                    <h2 class="font-bold text-gray-900 text-lg">{{ Auth::user()->name }}</h2>
                                    @if ($healthProfile?->is_verified)
                                        <svg class="w-4 h-4 text-forest-700" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $healthProfile->age ?? '-' }} tahun · {{ $healthProfile->gender ?? '-' }}</p>
                                @if ($healthProfile?->birth_date)
                                    <p class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                        📅 {{ $healthProfile->birth_date->translatedFormat('d F Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        @if ($healthProfile?->is_verified)
                            <div class="text-center flex-shrink-0">
                                <div class="w-11 h-11 rounded-full bg-forest-100 flex items-center justify-center mx-auto" style="background:#DCEADF">
                                    <span class="text-forest-700 text-lg">🛡️</span>
                                </div>
                                <p class="text-[10px] text-forest-700 font-medium mt-1 whitespace-nowrap">Profil Terverifikasi</p>
                            </div>
                        @endif
                    </div>

                    @if (!$healthProfile || !$healthProfile->is_verified)
                        <div class="bg-cream-100 rounded-xl p-3 mt-4 flex items-start gap-2 text-sm text-gray-700">
                            <span>✨</span>
                            <span>Lengkapi informasi profil kesehatanmu untuk mendapatkan konsultasi AI yang lebih personal dan akurat.</span>
                        </div>
                    @endif
                </div>

                @if ($healthProfile)
                    {{-- Informasi Dasar --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Informasi Dasar</h3>
                        <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-50">
                            <div class="grid grid-cols-2">
                                <div class="flex items-center gap-3 p-4">
                                    <div class="w-9 h-9 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">📅</div>
                                    <div>
                                        <p class="text-xs text-gray-400">Usia</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $healthProfile->age ?? '-' }} tahun</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 border-l border-gray-50">
                                    <div class="w-9 h-9 rounded-full bg-pink-50 flex items-center justify-center flex-shrink-0">♀️</div>
                                    <div>
                                        <p class="text-xs text-gray-400">Jenis Kelamin</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $healthProfile->gender ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="grid grid-cols-2">
                                <div class="flex items-center gap-3 p-4">
                                    <div class="w-9 h-9 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0">🩸</div>
                                    <div>
                                        <p class="text-xs text-gray-400">Golongan Darah</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $healthProfile->blood_type ?? '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-4 border-l border-gray-50">
                                    <div class="w-9 h-9 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">📏</div>
                                    <div>
                                        <p class="text-xs text-gray-400">Tinggi Badan</p>
                                        <p class="text-sm font-semibold text-gray-800">{{ $healthProfile->height ? $healthProfile->height.' cm' : '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ringkasan Kesehatan --}}
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Ringkasan Kesehatan</h3>
                        <div class="bg-white rounded-2xl shadow-sm divide-y divide-gray-50">

                            <div class="flex items-center gap-3 p-4">
                                <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center flex-shrink-0 text-red-500">⚠️</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">Alergi</p>
                                    <p class="text-xs text-gray-400">{{ $healthProfile->allergies ? implode(', ', $healthProfile->allergies) : 'Tidak ada' }}</p>
                                </div>
                                @if ($healthProfile->allergies)
                                    <span class="text-xs bg-red-50 text-red-600 px-2.5 py-1 rounded-full font-medium">{{ count($healthProfile->allergies) }} Alergi</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 p-4">
                                <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center flex-shrink-0">🫁</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">Penyakit Bawaan / Riwayat Penyakit</p>
                                    <p class="text-xs text-gray-400">{{ $healthProfile->chronic_diseases ? implode(', ', $healthProfile->chronic_diseases) : 'Tidak ada' }}</p>
                                </div>
                                @if ($healthProfile->chronic_diseases)
                                    <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium">{{ count($healthProfile->chronic_diseases) }} Riwayat</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 p-4">
                                <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center flex-shrink-0">💊</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">Obat Rutin</p>
                                    <p class="text-xs text-gray-400">{{ $healthProfile->routine_medicines ? implode(', ', $healthProfile->routine_medicines) : 'Tidak ada' }}</p>
                                </div>
                                @if ($healthProfile->routine_medicines)
                                    <span class="text-xs bg-purple-50 text-purple-600 px-2.5 py-1 rounded-full font-medium">{{ count($healthProfile->routine_medicines) }} Obat</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-3 p-4">
                                <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center flex-shrink-0">📋</div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-800">Kondisi Kesehatan Lain</p>
                                    <p class="text-xs text-gray-400">{{ $healthProfile->other_conditions ?: 'Tidak ada keluhan khusus' }}</p>
                                </div>
                                <span class="text-xs bg-green-50 text-green-600 px-2.5 py-1 rounded-full font-medium">✓ Baik</span>
                            </div>

                        </div>
                    </div>
                @endif

                {{-- Statistik Kesehatan --}}
                <div>
                    <h3 class="font-bold text-gray-900 mb-3">Statistik Kesehatan</h3>
                    <div class="bg-white rounded-2xl shadow-sm p-4 grid grid-cols-3 divide-x divide-gray-50">
                        <div class="flex flex-col items-center text-center px-2">
                            <div class="w-10 h-10 rounded-full bg-green-50 flex items-center justify-center mb-2">🤖</div>
                            <p class="text-xl font-bold text-gray-900">{{ $stats['consultations'] }}</p>
                            <p class="text-xs text-gray-400">Konsultasi</p>
                            <p class="text-[10px] text-gray-300">30 hari terakhir</p>
                        </div>
                        <div class="flex flex-col items-center text-center px-2">
                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center mb-2">📷</div>
                            <p class="text-xl font-bold text-gray-900">{{ $stats['scans'] }}</p>
                            <p class="text-xs text-gray-400">Scan Obat</p>
                            <p class="text-[10px] text-gray-300">30 hari terakhir</p>
                        </div>
                        <div class="flex flex-col items-center text-center px-2">
                            <div class="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center mb-2">⏰</div>
                            <p class="text-xl font-bold text-gray-900">{{ $stats['reminders'] }}</p>
                            <p class="text-xs text-gray-400">Pengingat</p>
                            <p class="text-[10px] text-gray-300">Aktif saat ini</p>
                        </div>
                    </div>
                </div>

                {{-- Banner motivasi --}}
                <div class="bg-purple-50 rounded-2xl p-4 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center flex-shrink-0 text-lg">💜</div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800">Jaga kesehatanmu selalu, {{ explode(' ', Auth::user()->name)[0] }}!</p>
                        <p class="text-xs text-gray-500 mt-0.5">Semakin lengkap profilmu, semakin akurat rekomendasi dan panduan yang diberikan AI.</p>
                    </div>
                </div>

                @if (!$healthProfile)
                    <a href="{{ route('health-profile.create') }}" class="block text-center bg-forest-700 text-white px-6 py-3 rounded-xl hover:bg-forest-800 transition font-medium">
                        Lengkapi Profil Kesehatan
                    </a>
                @endif

            </div>

            {{-- ============ EDIT MODE ============ --}}
            <div x-show="editing" x-cloak class="bg-white rounded-2xl shadow-sm p-6">
                <h3 class="font-semibold text-gray-800 text-sm mb-4">Edit Profil Kesehatan</h3>

                @if ($errors->any())
                    <div class="mb-4 text-red-600 text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($healthProfile)
                    <form method="POST" action="{{ route('health-profile.update') }}" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="{{ old('birth_date', $healthProfile->birth_date?->format('Y-m-d')) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" required>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Jenis Kelamin</label>
                            <select name="gender" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" {{ old('gender', $healthProfile->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('gender', $healthProfile->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Golongan Darah</label>
                            <input type="text" name="blood_type" value="{{ old('blood_type', $healthProfile->blood_type) }}" placeholder="Contoh: O, A, B, AB" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Tinggi Badan (cm)</label>
                            <input type="number" name="height" value="{{ old('height', $healthProfile->height) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Alergi</label>
                            <input type="text" name="allergies" value="{{ old('allergies', $healthProfile->allergies ? implode(', ', $healthProfile->allergies) : '') }}" placeholder="Pisahkan dengan koma" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Penyakit Bawaan / Riwayat Penyakit</label>
                            <input type="text" name="chronic_diseases" value="{{ old('chronic_diseases', $healthProfile->chronic_diseases ? implode(', ', $healthProfile->chronic_diseases) : '') }}" placeholder="Pisahkan dengan koma" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Obat Rutin</label>
                            <input type="text" name="routine_medicines" value="{{ old('routine_medicines', $healthProfile->routine_medicines ? implode(', ', $healthProfile->routine_medicines) : '') }}" placeholder="Pisahkan dengan koma" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                        </div>

                        <div>
                            <label class="block font-medium text-sm text-gray-700">Kondisi Kesehatan Lain</label>
                            <textarea name="other_conditions" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">{{ old('other_conditions', $healthProfile->other_conditions) }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-forest-700 text-white px-6 py-3 rounded-xl hover:bg-forest-800 transition font-medium">
                            Simpan Perubahan
                        </button>
                    </form>
                @else
                    <p class="text-gray-500 text-sm mb-3">Kamu belum melengkapi profil kesehatan.</p>
                    <a href="{{ route('health-profile.create') }}" class="inline-block bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                        Lengkapi Sekarang
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>