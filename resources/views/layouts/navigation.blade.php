<nav x-data="{ open: false }" class="bg-transparent">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            <div class="flex items-center gap-2">
                <span class="text-2xl">💊</span>
                <span class="text-gray-900 font-bold text-lg">MediGuide <span class="bg-forest-700 text-white text-xs px-1.5 py-0.5 rounded">AI</span></span>
            </div>

            <div class="flex items-center gap-3">
                <button class="relative w-9 h-9 rounded-full bg-white flex items-center justify-center text-gray-600 hover:bg-gray-50 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center">2</span>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-9 h-9 rounded-full bg-forest-800 flex items-center justify-center text-white font-semibold text-sm hover:ring-2 hover:ring-forest-300 transition">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('health-profile.edit')">Profil Kesehatan</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">Pengaturan Akun</x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Keluar</x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
{{-- Bottom Navigation (mobile-style, sesuai desain) --}}
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-40">
    <div class="max-w-4xl mx-auto grid grid-cols-5 text-center">
        <a href="{{ route('dashboard') }}" class="py-3 flex flex-col items-center gap-1 {{ request()->routeIs('dashboard') ? 'text-forest-800' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M4 10v10a1 1 0 001 1h4a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1h4a1 1 0 001-1V10" /></svg>
            <span class="text-xs">Beranda</span>
        </a>
        <a href="{{ route('consultation.index') }}" class="py-3 flex flex-col items-center gap-1 {{ request()->routeIs('consultation.*') ? 'text-forest-800' : 'text-gray-400' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" /></svg>
    <span class="text-xs">Konsultasi AI</span>
</a>
<a href="{{ route('medicine-scan.index') }}" class="py-3 flex flex-col items-center gap-1 {{ request()->routeIs('medicine-scan.*') ? 'text-forest-800' : 'text-gray-400' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
    <span class="text-xs">Scan Obat</span>
</a>
<a href="{{ route('reminder.index') }}" class="py-3 flex flex-col items-center gap-1 {{ request()->routeIs('reminder.*') ? 'text-forest-800' : 'text-gray-400' }}">
    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
    <span class="text-xs">Pengingat</span>
</a>
        <a href="{{ route('health-profile.edit') }}" class="py-3 flex flex-col items-center gap-1 {{ request()->routeIs('health-profile.*') ? 'text-forest-800' : 'text-gray-400' }}">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            <span class="text-xs">Profil</span>
        </a>
    </div>
</div>  