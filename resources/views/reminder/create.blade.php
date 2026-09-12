<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="flex items-center gap-3">
                <a href="{{ route('reminder.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-bold text-gray-900 text-lg">Tambah Pengingat</h1>
            </div>

            @if ($errors->any())
                <div class="text-red-600 text-sm bg-red-50 rounded-xl p-3">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reminder.store') }}" class="space-y-5">
                @csrf

                {{-- Informasi Obat --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
                    <h3 class="font-bold text-gray-900">Informasi Obat</h3>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Nama Obat</label>
                        <input type="text" name="medicine_name" value="{{ old('medicine_name') }}" placeholder="Contoh: Paracetamol 500 mg" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Bentuk Sediaan</label>
                        <select name="dosage_form" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            <option value="">Pilih bentuk sediaan</option>
                            <option value="Tablet" {{ old('dosage_form') == 'Tablet' ? 'selected' : '' }}>Tablet</option>
                            <option value="Kapsul" {{ old('dosage_form') == 'Kapsul' ? 'selected' : '' }}>Kapsul</option>
                            <option value="Sirup" {{ old('dosage_form') == 'Sirup' ? 'selected' : '' }}>Sirup</option>
                            <option value="Salep" {{ old('dosage_form') == 'Salep' ? 'selected' : '' }}>Salep</option>
                            <option value="Inhaler" {{ old('dosage_form') == 'Inhaler' ? 'selected' : '' }}>Inhaler</option>
                            <option value="Suntik" {{ old('dosage_form') == 'Suntik' ? 'selected' : '' }}>Suntik</option>
                            <option value="Lainnya" {{ old('dosage_form') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                    <div x-data="{ dosageOption: '{{ old('dosage_select', '') }}' }">
                        <label class="block text-sm text-gray-600 mb-1.5">Dosis</label>
                        <select x-model="dosageOption" name="dosage_select" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            <option value="">Pilih dosis</option>
                            <option value="1 Tablet">1 Tablet</option>
                            <option value="2 Tablet">2 Tablet</option>
                            <option value="1 Kapsul">1 Kapsul</option>
                            <option value="2 Kapsul">2 Kapsul</option>
                            <option value="1 Sendok Takar (5 ml)">1 Sendok Takar (5 ml)</option>
                            <option value="2 Sendok Takar (10 ml)">2 Sendok Takar (10 ml)</option>
                            <option value="1 Semprotan">1 Semprotan</option>
                            <option value="2 Semprotan">2 Semprotan</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>

                        <input x-show="dosageOption === 'Lainnya'" x-cloak type="text" name="dosage_custom" value="{{ old('dosage_custom') }}" placeholder="Tulis dosis lain..." class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 mt-2 focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Catatan (Opsional)</label>
                        <input type="text" name="note" value="{{ old('note') }}" placeholder="Contoh: Setelah makan" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                    </div>
                </div>

                {{-- Jadwal Pengingat --}}
                <div class="bg-white rounded-2xl shadow-sm p-5 space-y-4">
                    <h3 class="font-bold text-gray-900">Jadwal Pengingat</h3>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Waktu Minum Obat</label>
                        <input type="time" name="time" value="{{ old('time') }}" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Tanggal Mulai</label>
                        <input type="date" name="start_date" value="{{ old('start_date', date('Y-m-d')) }}" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm" required>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-2">Pilih Hari</label>
                        <div class="flex gap-2 flex-wrap">
                            @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $day)
                                <label class="cursor-pointer">
                                    <input type="checkbox" name="repeat_days[]" value="{{ $day }}" class="hidden peer">
                                    <span class="block px-3.5 py-2 rounded-full text-sm bg-gray-50 text-gray-500 peer-checked:bg-indigo-600 peer-checked:text-white transition">{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1.5">Pengulangan</label>
                        <select name="frequency" class="block w-full rounded-xl border border-gray-200 py-2.5 px-4 focus:border-indigo-600 focus:ring-indigo-600 text-sm">
                            <option value="daily" {{ old('frequency') == 'daily' ? 'selected' : '' }}>Setiap hari</option>
                            <option value="custom" {{ old('frequency') == 'custom' ? 'selected' : '' }}>Hari tertentu</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="w-full bg-indigo-600 text-white px-6 py-3.5 rounded-xl hover:bg-indigo-700 transition font-medium">
                    Simpan Pengingat
                </button>
            </form>

        </div>
    </div>
</x-app-layout>