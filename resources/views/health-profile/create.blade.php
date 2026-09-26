<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Lengkapi Profil Kesehatan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl p-6">

                @if ($errors->any())
                    <div class="mb-4 text-red-600">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('health-profile.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Tanggal Lahir</label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" required>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Jenis Kelamin</label>
                        <select name="gender" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Golongan Darah</label>
                        <input type="text" name="blood_type" value="{{ old('blood_type') }}" placeholder="Contoh: O, A, B, AB" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Tinggi Badan (cm)</label>
                        <input type="number" name="height" value="{{ old('height') }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Alergi</label>
                        <input type="text" name="allergies" value="{{ old('allergies') }}" placeholder="Pisahkan dengan koma, contoh: Penicillin, Ibuprofen" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Penyakit Bawaan / Riwayat Penyakit</label>
                        <input type="text" name="chronic_diseases" value="{{ old('chronic_diseases') }}" placeholder="Pisahkan dengan koma, contoh: Asma" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Obat Rutin</label>
                        <input type="text" name="routine_medicines" value="{{ old('routine_medicines') }}" placeholder="Pisahkan dengan koma, contoh: Ventolin Inhaler" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700">Kondisi Kesehatan Lain</label>
                        <textarea name="other_conditions" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700">{{ old('other_conditions') }}</textarea>
                    </div>

                    <button type="submit" class="bg-forest-700 text-white px-6 py-2.5 rounded-lg hover:bg-forest-800 transition font-medium">
                        Simpan Profil
                    </button>
                </form>
                <div class="text-center mt-3">
    <a href="{{ route('dashboard') }}" class="text-sm text-gray-400 hover:text-gray-600">
        Lewati untuk sekarang, isi nanti
    </a>
</div>

            </div>
        </div>
    </div>
</x-app-layout>