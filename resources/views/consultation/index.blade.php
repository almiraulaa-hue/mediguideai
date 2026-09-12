<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-5">

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Konsultasi AI</h1>
                    <p class="text-gray-500 text-sm">Riwayat konsultasi kesehatanmu</p>
                </div>
                <form method="POST" action="{{ route('consultation.create') }}">
                    @csrf
                    <button type="submit" class="bg-forest-700 text-white px-5 py-2.5 rounded-xl text-sm hover:bg-forest-800 transition font-medium">
                        + Konsultasi Baru
                    </button>
                </form>
            </div>

            <div class="space-y-3">
                @forelse ($consultations as $c)
                    <a href="{{ route('consultation.show', $c) }}" class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $c->title ?? 'Konsultasi Baru' }}</h3>
                                <p class="text-xs text-gray-400 mt-1">{{ $c->created_at->translatedFormat('d M Y, H:i') }}</p>
                            </div>
                            <span class="text-xs px-3 py-1 rounded-full {{ $c->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $c->status === 'completed' ? 'Selesai' : 'Berlangsung' }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="bg-white rounded-2xl shadow-sm p-8 text-center text-gray-400 text-sm">
                        Belum ada riwayat konsultasi. Mulai konsultasi pertamamu sekarang!
                    </div>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>