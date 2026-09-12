<x-app-layout>
    <div class="py-6">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            @if (session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Scan Obat</h1>
                    <p class="text-gray-500 text-sm">Obat yang pernah kamu scan</p>
                </div>
                <a href="{{ route('medicine-scan.create') }}" class="bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                    + Scan Baru
                </a>
            </div>

            <div class="grid grid-cols-2 gap-3">
                @forelse ($scans as $s)
                    <a href="{{ route('medicine-scan.show', $s) }}" class="bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">
                        @if ($s->image_path)
                            <img src="{{ Storage::url($s->image_path) }}" class="w-full h-28 object-cover">
                        @endif
                        <div class="p-3">
                            <h4 class="font-semibold text-gray-800 text-sm truncate">{{ $s->medicine_name }}</h4>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $s->created_at->translatedFormat('d M Y') }}</p>
                        </div>
                    </a>
                @empty
                    <div class="col-span-2 bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400 text-sm">
                        Belum ada riwayat scan obat.
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>