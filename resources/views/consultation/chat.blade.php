<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Konsultasi AI - {{ config('app.name', 'MediGuide AI') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Layout & sidebar ditulis sebagai CSS biasa supaya tidak bergantung pada Alpine.js
           atau pada build Tailwind yang mungkin belum memuat class baru. */
        :root { --chat-w: 40rem; }
        html, body { height: 100%; margin: 0; }

        .chat-shell { display: flex; height: 100vh; height: 100dvh; overflow: hidden; }

        .chat-sidebar {
            width: 18rem; flex-shrink: 0; overflow: hidden;
            background: #f9fafb; border-right: 1px solid #e5e7eb;
            transition: width .2s ease;
        }
        .chat-sidebar-inner { width: 18rem; height: 100%; display: flex; flex-direction: column; }
        .chat-main { flex: 1 1 0%; min-width: 0; display: flex; flex-direction: column; }

        .chat-shell.sidebar-closed .chat-sidebar { width: 0; border-right-width: 0; }

        .chat-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.3); z-index: 20; }

        .topbar-controls { display: none; align-items: center; gap: .25rem; }
        .chat-shell.sidebar-closed .topbar-controls { display: flex; }

        @media (max-width: 767px) {
            .chat-sidebar { position: fixed; top: 0; bottom: 0; left: 0; z-index: 30; }
            .chat-shell:not(.sidebar-closed) .chat-overlay { display: block; }
        }

        /* Sidebar */
        .sb-header { display: flex; align-items: center; justify-content: space-between; padding: 1rem 1rem .5rem; }
        .sb-brand { font-size: 1.125rem; font-weight: 700; color: #111827; letter-spacing: -.01em; text-decoration: none; }
        .sb-actions { display: flex; align-items: center; gap: .25rem; }
        .icon-btn {
            width: 2.25rem; height: 2.25rem; border-radius: .5rem; border: 0; background: transparent;
            display: flex; align-items: center; justify-content: center; color: #6b7280; cursor: pointer;
            transition: background .15s;
        }
        .icon-btn:hover { background: rgba(229,231,235,.8); }
        .sb-section { padding: 0 .75rem; }
        .sb-search {
            width: 100%; box-sizing: border-box; border: 1px solid #e5e7eb; border-radius: .75rem;
            background: #fff; font-size: .875rem; padding: .5rem .75rem;
        }
        .sb-search:focus { outline: 0; border-color: #d1d5db; box-shadow: none; }
        .sb-nav { padding: .5rem .75rem 0; display: flex; flex-direction: column; gap: 2px; }
        .sb-label { padding: 1.25rem 1.5rem .25rem; font-size: .75rem; font-weight: 500; color: #9ca3af; }
        .sb-history { flex: 1; overflow-y: auto; padding: 0 .75rem .75rem; display: flex; flex-direction: column; gap: 2px; }
        .sb-empty { padding: 0 .75rem; font-size: .75rem; color: #9ca3af; }

        .side-item {
            display: flex; align-items: center; gap: .75rem; box-sizing: border-box;
            border-radius: .75rem; padding: .625rem .75rem; font-size: .875rem; color: #374151;
            text-decoration: none; transition: background .15s;
        }
        .side-item:hover { background: rgba(229,231,235,.7); }
        .side-item.active { background: rgba(229,231,235,.7); color: #111827; font-weight: 500; }
        .side-item-new {
            width: 100%; border: 0; cursor: pointer; text-align: left; font-family: inherit;
            background: rgba(229,231,235,.7); color: #1f2937; font-weight: 500;
        }
        .side-item-new:hover { background: #e5e7eb; }
        .history-item { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .nav-ico { width: 1.25rem; text-align: center; }

        .sb-user {
            display: flex; align-items: center; gap: .75rem; border-top: 1px solid #e5e7eb;
            padding: .75rem 1rem; text-decoration: none;
        }
        .sb-user:hover { background: #f3f4f6; }
        .avatar {
            width: 2.25rem; height: 2.25rem; border-radius: 9999px; color: #fff; font-size: .75rem;
            font-weight: 600; display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .sb-user-text { min-width: 0; }
        .sb-user-name { font-size: .875rem; font-weight: 500; color: #1f2937; }
        .sb-user-mail { font-size: .75rem; color: #9ca3af; }
        .ellipsis { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        /* Main */
        .topbar { height: 3.5rem; flex-shrink: 0; display: flex; align-items: center; gap: .25rem; padding: 0 .75rem; }
        .topbar-title { margin-left: .5rem; font-size: 1rem; font-weight: 600; color: #374151; }

        .content { flex: 1; min-height: 0; display: flex; flex-direction: column; }
        .content.is-empty { justify-content: center; padding-bottom: 5rem; }

        .greeting { text-align: center; font-size: 2rem; font-weight: 600; color: #1f2937; margin: 0 1rem 2rem; }
        @media (min-width: 640px) { .greeting { font-size: 2.125rem; } }

        .messages { flex: 1; overflow-y: auto; padding: 0 1rem; }
        .messages-inner {
            max-width: var(--chat-w); margin: 0 auto; padding: 1.5rem 0;
            display: flex; flex-direction: column; gap: 1.25rem;
        }
        .row-user { display: flex; justify-content: flex-end; animation: messageFadeIn .25s ease-out; }
        .row-ai { display: flex; align-items: flex-start; gap: .75rem; animation: messageFadeIn .25s ease-out; }
        .ai-avatar {
            width: 2rem; height: 2rem; border-radius: 9999px; display: flex; align-items: center;
            justify-content: center; font-size: .875rem; flex-shrink: 0;
        }
        .bubble { font-size: .875rem; line-height: 1.6; white-space: pre-line; padding: .625rem 1.25rem; }
        .bubble-user { max-width: 80%; border-radius: 1.5rem 1.5rem .5rem 1.5rem; box-shadow: 0 1px 2px rgba(0,0,0,.06); }
        .bubble-ai { max-width: 85%; border-radius: 1.5rem 1.5rem 1.5rem .5rem; padding-top: .75rem; padding-bottom: .75rem; }

        .chips { display: flex; flex-wrap: wrap; gap: .5rem; }
        .chips.offset { margin-left: 2.75rem; }
        .chips.center { justify-content: center; margin-top: 1rem; }
        .chip {
            font-size: .875rem; font-family: inherit; border: 1px solid #e5e7eb; color: #374151; background: #fff;
            padding: .5rem 1rem; border-radius: 9999px; cursor: pointer; transition: background .15s;
        }
        .chip:hover { background: #f3f4f6; }

        /* Kolom ketik (diperkecil) */
        .composer-wrap { padding: 0 1rem; }
        .composer-wrap.docked { padding-top: .5rem; padding-bottom: 1rem; }
        .composer { width: 100%; max-width: var(--chat-w); margin: 0 auto; }
        .composer-pill {
            display: flex; align-items: center; gap: .5rem; height: 3rem; box-sizing: border-box;
            padding: 0 .375rem 0 1.125rem; border: 1px solid #e5e7eb; border-radius: 9999px; background: #fff;
            box-shadow: 0 1px 2px rgba(0,0,0,.05); transition: box-shadow .15s, border-color .15s;
        }
        .composer-pill:focus-within { border-color: #d1d5db; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .composer-input {
            flex: 1; min-width: 0; background: transparent; font-size: .875rem; font-family: inherit;
            border: 0 !important; outline: 0 !important; box-shadow: none !important; padding: 0 !important;
        }
        .send-btn {
            width: 2.125rem; height: 2.125rem; border-radius: 9999px; border: 0; flex-shrink: 0; cursor: pointer;
            display: flex; align-items: center; justify-content: center; color: #fff; transition: background .15s;
        }
        .send-btn:disabled { opacity: .6; cursor: default; }
        .disclaimer { margin-top: .75rem; text-align: center; font-size: .6875rem; color: #9ca3af; }

        @keyframes messageFadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes typingBounce {
            0%, 60%, 100% { transform: translateY(0); opacity: .5; }
            30% { transform: translateY(-4px); opacity: 1; }
        }
        .typing-bubble { display: flex; align-items: center; gap: 6px; padding: 1rem 1.25rem; }
        .typing-dot {
            width: 6px; height: 6px; border-radius: 9999px; background: #9ca3af;
            animation: typingBounce 1.2s infinite ease-in-out;
        }
        .typing-dot:nth-child(2) { animation-delay: .15s; }
        .typing-dot:nth-child(3) { animation-delay: .3s; }
    </style>
</head>
<body class="font-sans antialiased bg-white text-gray-800">

@php
    $user = auth()->user();
    $hasUserMessage = $messages->contains('sender', 'user');
    $welcome = $messages->first();
    $emptyQuickReplies = (!$hasUserMessage && $welcome && !empty($welcome->quick_replies)) ? $welcome->quick_replies : [];

    // Riwayat: sembunyikan konsultasi kosong (belum ada keluhan), kecuali yang sedang dibuka
    $history = $consultations->filter(fn ($c) => $c->main_complaint || $c->id === $consultation->id);
    $initials = strtoupper(mb_substr($user->name, 0, 2));
@endphp

<div id="chatShell" class="chat-shell">
    <script>
        // Tentukan kondisi awal sidebar sebelum halaman tergambar (tanpa kedip)
        (function () {
            var closed = window.innerWidth < 768;
            try { if (localStorage.getItem('consultSidebar') === '0') closed = true; } catch (e) {}
            if (closed) document.getElementById('chatShell').classList.add('sidebar-closed');
        })();
    </script>

    <div id="chatOverlay" class="chat-overlay"></div>

    {{-- ================= SIDEBAR ================= --}}
    <aside class="chat-sidebar">
        <div class="chat-sidebar-inner">

            <div class="sb-header">
                <a href="{{ route('dashboard') }}" class="sb-brand">MediGuide AI</a>
                <div class="sb-actions">
                    <button type="button" id="searchToggle" class="icon-btn" title="Cari riwayat">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="M20 20l-3.5-3.5"/></svg>
                    </button>
                    <button type="button" id="sidebarHide" class="icon-btn" title="Sembunyikan sidebar">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="3"/><path d="M9 4v16"/></svg>
                    </button>
                </div>
            </div>

            <div id="searchBox" class="sb-section" style="display:none; padding-bottom:.5rem;">
                <input type="text" id="searchInput" class="sb-search" placeholder="Cari riwayat..." autocomplete="off">
            </div>

            <div class="sb-section" style="padding-top:.25rem;">
                <form method="POST" action="{{ route('consultation.create') }}">
                    @csrf
                    <button type="submit" class="side-item side-item-new">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                        Konsultasi baru
                    </button>
                </form>
            </div>

            <nav class="sb-nav">
                <a href="{{ route('dashboard') }}" class="side-item"><span class="nav-ico">🏠</span> Beranda</a>
                <a href="{{ route('medicine-scan.create') }}" class="side-item"><span class="nav-ico">📷</span> Scan Obat</a>
                <a href="{{ route('reminder.index') }}" class="side-item"><span class="nav-ico">⏰</span> Pengingat Obat</a>
                <a href="{{ route('health-profile.edit') }}" class="side-item"><span class="nav-ico">👤</span> Profil Kesehatan</a>
            </nav>

            <p class="sb-label">Riwayat</p>
            <div class="sb-history">
                @forelse ($history as $c)
                    @php
                        $label = \Illuminate\Support\Str::limit($c->title ?? $c->main_complaint ?? 'Konsultasi baru', 34);
                    @endphp
                    <a href="{{ route('consultation.show', $c) }}"
                        data-title="{{ strtolower($label) }}"
                        class="side-item history-item {{ $c->id === $consultation->id ? 'active' : '' }}">{{ $label }}</a>
                @empty
                    <p class="sb-empty">Belum ada riwayat.</p>
                @endforelse
            </div>

            <a href="{{ route('health-profile.edit') }}" class="sb-user">
                <div class="avatar bg-forest-700">{{ $initials }}</div>
                <div class="sb-user-text">
                    <p class="sb-user-name ellipsis" style="margin:0;">{{ $user->name }}</p>
                    <p class="sb-user-mail ellipsis" style="margin:0;">{{ $user->email }}</p>
                </div>
            </a>
        </div>
    </aside>

    {{-- ================= MAIN ================= --}}
    <main class="chat-main">

        <header class="topbar">
            <div class="topbar-controls">
                <button type="button" id="sidebarShow" class="icon-btn" title="Tampilkan sidebar">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><rect x="3" y="4" width="18" height="16" rx="3"/><path d="M9 4v16"/></svg>
                </button>
                <form method="POST" action="{{ route('consultation.create') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="icon-btn" title="Konsultasi baru">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                    </button>
                </form>
            </div>
            <span class="topbar-title">MediGuide AI</span>
        </header>

        <div class="content {{ $hasUserMessage ? '' : 'is-empty' }}">

            @if ($hasUserMessage)
                <div id="chatWindow" class="messages">
                    <div id="messageList" class="messages-inner">

                        @foreach ($messages as $i => $msg)
                            {{-- Pesan sambutan pertama diganti sapaan melayang, jadi tidak ditampilkan lagi --}}
                            @continue($i === 0 && $msg->sender === 'ai')

                            @if ($msg->sender === 'user')
                                <div class="row-user">
                                    <div class="bubble bubble-user bg-forest-700 text-white">{{ $msg->message }}</div>
                                </div>
                            @else
                                <div class="row-ai">
                                    <div class="ai-avatar bg-forest-800 text-white">🤖</div>
                                    <div class="bubble bubble-ai bg-gray-50 text-gray-800">{{ $msg->message }}</div>
                                </div>
                            @endif
                        @endforeach

                        @php $last = $messages->last(); @endphp
                        @if ($last && $last->sender === 'ai' && !empty($last->quick_replies))
                            <div class="chips offset">
                                @foreach ($last->quick_replies as $reply)
                                    <button type="button" class="chip" data-reply="{{ $reply }}" onclick="sendQuickReply(this.dataset.reply)">{{ $reply }}</button>
                                @endforeach
                            </div>
                        @endif

                    </div>
                </div>
            @else
                <h1 class="greeting">Apa keluhanmu hari ini?</h1>
            @endif

            <div class="composer-wrap {{ $hasUserMessage ? 'docked' : '' }}">
                <div class="composer">
                    <form method="POST" action="{{ route('consultation.message', $consultation) }}" id="chatForm" class="composer-pill" style="margin:0;">
                        @csrf
                        <input id="chatInput" type="text" name="message" maxlength="1000" autocomplete="off" autofocus required
                            placeholder="Ceritakan keluhanmu..." class="composer-input">
                        <button id="sendBtn" type="submit" class="send-btn bg-forest-700 hover:bg-forest-800" title="Kirim">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19V5m0 0l-6 6m6-6l6 6"/></svg>
                        </button>
                    </form>

                    @if (!$hasUserMessage && count($emptyQuickReplies))
                        <div class="chips center">
                            @foreach ($emptyQuickReplies as $reply)
                                <button type="button" class="chip" data-reply="{{ $reply }}" onclick="sendQuickReply(this.dataset.reply)">{{ $reply }}</button>
                            @endforeach
                        </div>
                    @endif

                    <p class="disclaimer">MediGuide AI tidak menggantikan konsultasi medis profesional.</p>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const shell = document.getElementById('chatShell');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const chatWindow = document.getElementById('chatWindow');

    // ----- Sidebar -----
    function setSidebar(open) {
        shell.classList.toggle('sidebar-closed', !open);
        if (window.innerWidth >= 768) {
            try { localStorage.setItem('consultSidebar', open ? '1' : '0'); } catch (e) {}
        }
    }
    document.getElementById('sidebarHide').addEventListener('click', () => setSidebar(false));
    document.getElementById('sidebarShow').addEventListener('click', () => setSidebar(true));
    document.getElementById('chatOverlay').addEventListener('click', () => setSidebar(false));

    // ----- Cari riwayat -----
    const searchBox = document.getElementById('searchBox');
    const searchInput = document.getElementById('searchInput');

    function filterHistory(q) {
        q = q.toLowerCase();
        document.querySelectorAll('.history-item').forEach((el) => {
            el.style.display = (!q || el.dataset.title.includes(q)) ? '' : 'none';
        });
    }
    document.getElementById('searchToggle').addEventListener('click', () => {
        const opening = searchBox.style.display === 'none';
        searchBox.style.display = opening ? 'block' : 'none';
        if (opening) {
            searchInput.focus();
        } else {
            searchInput.value = '';
            filterHistory('');
        }
    });
    searchInput.addEventListener('input', (e) => filterHistory(e.target.value));

    // ----- Chat -----
    function scrollToBottom() {
        if (chatWindow) chatWindow.scrollTop = chatWindow.scrollHeight;
    }
    scrollToBottom();

    function sendQuickReply(text) {
        chatInput.value = text;
        chatForm.requestSubmit();
    }

    // Bubble user langsung muncul + indikator mengetik selama menunggu AI
    chatForm.addEventListener('submit', () => {
        const text = chatInput.value.trim();
        const sendBtn = document.getElementById('sendBtn');
        sendBtn.disabled = true;

        const list = document.getElementById('messageList');
        if (!list || !text) return;

        const userRow = document.createElement('div');
        userRow.className = 'row-user';
        const userBubble = document.createElement('div');
        userBubble.className = 'bubble bubble-user bg-forest-700 text-white';
        userBubble.textContent = text;
        userRow.appendChild(userBubble);
        list.appendChild(userRow);

        const typing = document.createElement('div');
        typing.className = 'row-ai';
        typing.innerHTML =
            '<div class="ai-avatar bg-forest-800 text-white">🤖</div>' +
            '<div class="bubble bubble-ai bg-gray-50 typing-bubble">' +
            '<span class="typing-dot"></span><span class="typing-dot"></span><span class="typing-dot"></span></div>';
        list.appendChild(typing);

        scrollToBottom();
    });
</script>

</body>
</html>