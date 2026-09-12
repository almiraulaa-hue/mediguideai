<x-guest-layout>
    <h2 class="text-xl font-bold text-gray-900 mb-1">Selamat Datang Kembali</h2>
    <p class="text-sm text-gray-500 mb-6">Masuk untuk melanjutkan ke MediGuide AI</p>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="password" class="block mt-1 w-full rounded-lg border-gray-300 focus:border-forest-700 focus:ring-forest-700"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-forest-700 focus:ring-forest-700" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-forest-700 hover:underline" href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif
        </div>

        <button type="submit" class="w-full bg-forest-700 text-white px-6 py-3 rounded-xl hover:bg-forest-800 transition font-medium">
            {{ __('Masuk') }}
        </button>

        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 pt-2">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-forest-700 font-medium hover:underline">Daftar sekarang</a>
            </p>
        @endif
    </form>
</x-guest-layout>