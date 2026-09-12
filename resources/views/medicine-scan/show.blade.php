<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">

            <div class="flex items-center gap-3">
                <a href="{{ route('medicine-scan.index') }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <h1 class="font-semibold text-gray-800">Hasil Scan</h1>
            </div>

            @if ($scan->image_path)
                <img src="{{ Storage::url($scan->image_path) }}" class="w-full h-48 object-cover rounded-2xl">
            @endif

            {{-- Ringkasan Obat --}}
            <div class="bg-white rounded-2xl shadow-sm p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">{{ $scan->medicine_name }}</h2>
                        <p class="text-sm text-gray-500">{{ $scan->dosage_form ?? '-' }}</p>
                        @if ($scan->manufacturer)
                            <p class="text-xs text-gray-400 mt-1">Produsen: {{ $scan->manufacturer }}</p>
                        @endif
                    </div>
                    <a href="#" class="text-xs text-forest-700 font-medium">Edit</a>
                </div>
            </div>

            {{-- Tabs --}}
            <div x-data="{ tab: 'informasi' }">
                <div class="flex gap-1 bg-white rounded-xl p-1 shadow-sm text-sm">
                    <button @click="tab = 'informasi'" :class="tab === 'informasi' ? 'bg-forest-700 text-white' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-medium transition">Informasi</button>
                    <button @click="tab = 'kandungan'" :class="tab === 'kandungan' ? 'bg-forest-700 text-white' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-medium transition">Kandungan</button>
                    <button @click="tab = 'indikasi'" :class="tab === 'indikasi' ? 'bg-forest-700 text-white' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-medium transition">Indikasi</button>
                    <button @click="tab = 'efek'" :class="tab === 'efek' ? 'bg-forest-700 text-white' : 'text-gray-500'" class="flex-1 py-2 rounded-lg font-medium transition">Efek Samping</button>
                </div>

                <div class="bg-white rounded-2xl shadow-sm p-5 mt-3">

                    <div x-show="tab === 'informasi'" class="space-y-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-1">Informasi Umum</h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-xs text-gray-400">Kategori</p>
                                <p class="text-gray-700 font-medium">{{ $scan->category ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Bentuk Sediaan</p>
                                <p class="text-gray-700 font-medium">{{ $scan->dosage_form ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Kemasan</p>
                                <p class="text-gray-700 font-medium">{{ $scan->packaging ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Harga Eceran Tertinggi (HET)</p>
                                <p class="text-gray-700 font-medium">{{ $scan->price_estimate ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'kandungan'">
                        <h3 class="font-semibold text-gray-800 text-sm mb-2">Kandungan / Komposisi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $scan->composition ?? 'Tidak tersedia' }}</p>
                    </div>

                    <div x-show="tab === 'indikasi'">
                        <h3 class="font-semibold text-gray-800 text-sm mb-2">Indikasi</h3>
                        <p class="text-sm text-gray-700 leading-relaxed">{{ $scan->indication ?? 'Tidak tersedia' }}</p>
                    </div>

                    <div x-show="tab === 'efek'" class="space-y-4">
                        <div>
                            <h3 class="font-semibold text-red-500 text-sm mb-2">Efek Samping</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $scan->side_effects ?? 'Tidak tersedia' }}</p>
                        </div>
                        <div>
                            <h3 class="font-semibold text-amber-500 text-sm mb-2">Kontraindikasi</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $scan->contraindication ?? 'Tidak tersedia' }}</p>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Peringatan --}}
            <div class="bg-green-50 border border-green-100 rounded-xl p-3.5 flex items-start gap-2.5">
                <span class="text-green-600 flex-shrink-0">✅</span>
                <p class="text-xs text-green-800">Pastikan obat sesuai kebutuhan dan kondisi kesehatanmu. Bila ragu, konsultasikan dengan tenaga kesehatan.</p>
            </div>

            {{-- Aksi --}}
            <div class="flex gap-3">
                <form method="POST" action="{{ route('medicine-scan.destroy', $scan) }}" onsubmit="return confirm('Hapus riwayat scan ini?')" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full text-center bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-gray-50 transition">
                        Hapus
                    </button>
                </form>
                <a href="{{ route('reminder.create') }}" class="flex-1 text-center bg-forest-700 text-white px-4 py-2.5 rounded-xl text-sm font-medium hover:bg-forest-800 transition">
                    Tambah ke Pengingat
                </a>
            </div>

            <p class="text-xs text-gray-400 text-center pb-4">
                ⚠️ Hasil analisa AI bisa saja tidak 100% akurat. Konsultasikan dengan tenaga medis atau apoteker.
            </p>

        </div>
    </div>
</x-app-layout>