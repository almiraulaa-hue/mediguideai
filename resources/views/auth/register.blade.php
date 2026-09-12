<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-900 mb-1">Buat Akun Baru</h2>
    <p class="text-sm text-gray-500 mb-6">Mulai perjalanan kesehatanmu bersama MediGuide AI</p>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="name" :value="__('Nama')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="name" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="w-full bg-forest-700 text-white px-6 py-3 rounded-xl hover:bg-forest-800 transition font-medium">
            {{ __('Daftar') }}
        </button>

        <p class="text-center text-sm text-gray-500 pt-2">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-forest-700 font-medium hover:underline">Masuk di sini</a>
        </p>
    </form>
</x-guest-layout>