@extends('layouts.app')

@section('title', $group->nom)

@section('styles')
<style>
    .group-chat-layout { display: grid; grid-template-columns: minmax(0, 1fr) 260px; gap: 1.25rem; max-width: 1000px; margin: 0 auto; height: calc(100vh - 130px); }

    /* ── Left: chat panel ── */
    .group-chat-panel { display: flex; flex-direction: column; height: 100%; }

    .group-chat-header { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(255,255,255,0.12); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px 16px 0 0; flex-shrink: 0; }
    .group-header-avatar { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .group-header-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .group-header-avatar svg { width: 22px; height: 22px; fill: rgba(255,255,255,0.8); }
    .group-header-name { color: #fff; font-weight: 700; font-size: 1rem; }
    .group-header-sub { color: rgba(255,255,255,0.5); font-size: 0.75rem; }
    .btn-back { color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 0.3rem; font-size: 0.82rem; }
    .btn-back svg { width: 18px; height: 18px; fill: currentColor; }

    .messages-area { flex: 1; overflow-y: auto; padding: 1.25rem; background: rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 0.85rem; border-left: 1px solid rgba(255,255,255,0.12); border-right: 1px solid rgba(255,255,255,0.12); }
    .messages-area::-webkit-scrollbar { width: 4px; }
    .messages-area::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

    .msg-row { display: flex; align-items: flex-end; gap: 0.6rem; }
    .msg-row.mine { flex-direction: row-reverse; }
    .msg-avatar { width: 30px; height: 30px; border-radius: 50%; background: rgba(255,255,255,0.2); flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .msg-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .msg-avatar svg { width: 14px; height: 14px; fill: rgba(255,255,255,0.7); }
    .msg-content { max-width: 65%; }
    .msg-sender { font-size: 0.68rem; color: rgba(255,255,255,0.5); margin-bottom: 0.2rem; }
    .msg-row.mine .msg-sender { text-align: right; }
    .msg-bubble { padding: 0.65rem 1rem; border-radius: 18px; font-size: 0.88rem; line-height: 1.5; word-break: break-word; }
    .msg-row:not(.mine) .msg-bubble { background: rgba(255,255,255,0.18); color: #fff; border-bottom-left-radius: 4px; }
    .msg-row.mine .msg-bubble { background: #fff; color: #c0392b; border-bottom-right-radius: 4px; }
    .msg-time { font-size: 0.63rem; color: rgba(255,255,255,0.35); margin-top: 0.15rem; }
    .msg-row.mine .msg-time { text-align: right; }

    .chat-input-area { padding: 0.85rem 1.25rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-top: none; border-radius: 0 0 16px 16px; flex-shrink: 0; }
    .chat-input-form { display: flex; gap: 0.75rem; align-items: flex-end; }
    .chat-input-form textarea { flex: 1; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); border-radius: 20px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.88rem; padding: 0.7rem 1rem; resize: none; outline: none; max-height: 120px; }
    .chat-input-form textarea::placeholder { color: rgba(255,255,255,0.4); }
    .btn-send { background: #fff; color: #c0392b; border: none; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
    .btn-send svg { width: 20px; height: 20px; fill: currentColor; }
    .not-member-bar { text-align: center; padding: 1rem; background: rgba(0,0,0,0.15); border-left: 1px solid rgba(255,255,255,0.12); border-right: 1px solid rgba(255,255,255,0.12); border-bottom: 1px solid rgba(255,255,255,0.2); border-radius: 0 0 16px 16px; }
    .not-member-bar p { color: rgba(255,255,255,0.6); font-size: 0.85rem; margin-bottom: 0.75rem; }
    .btn-join-bar { background: #fff; color: #c0392b; border: none; border-radius: 999px; padding: 0.7rem 1.6rem; font-family: 'Poppins', sans-serif; font-size: 0.88rem; font-weight: 700; cursor: pointer; }
    .empty-msgs { text-align: center; color: rgba(255,255,255,0.4); font-size: 0.85rem; padding: 2rem 0; }

    /* ── Right: sidebar ── */
    .group-sidebar { display: flex; flex-direction: column; gap: 1rem; overflow-y: auto; }
    .sidebar-card { background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.18); border-radius: 16px; padding: 1.1rem; }
    .sidebar-title { color: rgba(255,255,255,0.7); font-size: 0.72rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.75rem; }
    .member-row { display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.6rem; }
    .member-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); overflow: hidden; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .member-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .member-avatar svg { width: 15px; height: 15px; fill: rgba(255,255,255,0.7); }
    .member-name { color: #fff; font-size: 0.82rem; font-weight: 600; }
    .member-role { font-size: 0.65rem; color: rgba(255,255,255,0.5); }
    .interest-tag { display: inline-block; padding: 0.25rem 0.6rem; border-radius: 999px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.8); font-size: 0.72rem; margin: 0.2rem; }
    .pill-public { background: rgba(46,204,113,0.2); color: #2ecc71; border-radius: 999px; padding: 0.2rem 0.55rem; font-size: 0.7rem; font-weight: 700; }
    .pill-private { background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); border-radius: 999px; padding: 0.2rem 0.55rem; font-size: 0.7rem; }
    .btn-leave { width: 100%; border: 1px solid rgba(231,76,60,0.4); background: transparent; color: rgba(231,76,60,0.8); border-radius: 999px; padding: 0.55rem; font-family: 'Poppins', sans-serif; font-size: 0.78rem; font-weight: 600; cursor: pointer; transition: all 0.2s; }
    .btn-leave:hover { background: rgba(231,76,60,0.15); }
    .admin-actions { display: flex; gap: 0.35rem; margin-left: auto; }
    .btn-admin-sm { border: none; border-radius: 999px; padding: 0.2rem 0.55rem; font-family: 'Poppins', sans-serif; font-size: 0.65rem; font-weight: 700; cursor: pointer; transition: all 0.2s; }
    .btn-kick  { background: rgba(231,76,60,0.2); color: #e74c3c; }
    .btn-kick:hover  { background: rgba(231,76,60,0.4); }
    .btn-promote { background: rgba(46,204,113,0.2); color: #2ecc71; }
    .btn-promote:hover { background: rgba(46,204,113,0.4); }
    .btn-demote  { background: rgba(255,220,80,0.2); color: #fdd835; }
    .btn-demote:hover  { background: rgba(255,220,80,0.4); }
    .invite-box { margin-top: 0.75rem; }
    .invite-input { width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.22); border-radius: 12px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.82rem; padding: 0.55rem 0.85rem; outline: none; box-sizing: border-box; }
    .invite-input::placeholder { color: rgba(255,255,255,0.35); }
    .invite-results { margin-top: 0.4rem; display: flex; flex-direction: column; gap: 0.3rem; }
    .invite-result-row { display: flex; align-items: center; gap: 0.55rem; padding: 0.4rem 0.6rem; border-radius: 10px; background: rgba(255,255,255,0.08); }
    .invite-result-name { flex: 1; color: #fff; font-size: 0.8rem; }
    .btn-add-user { background: #fff; color: #c0392b; border: none; border-radius: 999px; padding: 0.2rem 0.65rem; font-family: 'Poppins', sans-serif; font-size: 0.7rem; font-weight: 700; cursor: pointer; }
    .flash-msg { margin-bottom: 0.75rem; padding: 0.6rem 1rem; border-radius: 10px; font-size: 0.82rem; font-weight: 600; }
    .flash-success { background: rgba(46,204,113,0.2); color: #2ecc71; border: 1px solid rgba(46,204,113,0.3); }
    .flash-error   { background: rgba(231,76,60,0.2); color: #e74c3c; border: 1px solid rgba(231,76,60,0.3); }

    @media (max-width: 768px) { .group-chat-layout { grid-template-columns: 1fr; } .group-sidebar { display: none; } }
</style>
@endsection

@section('content')
<div class="group-chat-layout">

    {{-- ── Chat panel ── --}}
    <div class="group-chat-panel">
        <div class="group-chat-header">
            <a href="{{ route('groups.index') }}" class="btn-back">
                <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
            </a>
            <div class="group-header-avatar">
                @if($group->avatar_url)
                    <img src="{{ asset('storage/' . $group->avatar_url) }}" alt="">
                @else
                    <svg viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                @endif
            </div>
            <div>
                <div class="group-header-name">{{ $group->nom }}</div>
                <div class="group-header-sub">{{ $members->count() }} membre{{ $members->count() > 1 ? 's' : '' }}</div>
            </div>
        </div>

        <div class="messages-area" id="messagesArea">
            @forelse($messages as $msg)
                @php $isMine = $msg->user_id === Auth::id(); @endphp
                <div class="msg-row {{ $isMine ? 'mine' : '' }}">
                    @if(!$isMine)
                        <div class="msg-avatar">
                            @if($msg->user->avatar_url)
                                <img src="{{ asset('storage/' . $msg->user->avatar_url) }}" alt="">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                    @endif
                    <div class="msg-content">
                        @if(!$isMine)<div class="msg-sender">{{ $msg->user->prenom }}</div>@endif
                        <div class="msg-bubble">{{ $msg->contenu }}</div>
                        <div class="msg-time">{{ $msg->created_at->format('H:i') }}</div>
                    </div>
                </div>
            @empty
                <div class="empty-msgs">Soyez le premier à écrire quelque chose!</div>
            @endforelse
        </div>

        @if($isMember)
            <div class="chat-input-area">
                <form class="chat-input-form" method="POST" action="{{ route('groups.messages.store', $group) }}" id="msgForm">
                    @csrf
                    <textarea name="contenu" id="msgInput" rows="1" placeholder="Écris un message au groupe…" maxlength="2000"></textarea>
                    <button type="submit" class="btn-send">
                        <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                    </button>
                </form>
            </div>
        @elseif($group->est_public)
            <div class="not-member-bar">
                <p>Rejoins ce groupe pour participer à la conversation.</p>
                <form method="POST" action="{{ route('groups.join', $group) }}">
                    @csrf
                    <button type="submit" class="btn-join-bar">Rejoindre</button>
                </form>
            </div>
        @endif
    </div>

    {{-- ── Sidebar ── --}}
    <aside class="group-sidebar">
        {{-- Group info --}}
        <div class="sidebar-card">
            <div class="sidebar-title">À propos</div>
            <div style="margin-bottom:0.6rem;">
                <span class="{{ $group->est_public ? 'pill-public' : 'pill-private' }}">{{ $group->est_public ? 'Public' : 'Privé' }}</span>
            </div>
            @if($group->description)
                <p style="color:rgba(255,255,255,0.7);font-size:0.82rem;line-height:1.5;margin-bottom:0.6rem;">{{ $group->description }}</p>
            @endif
            @if($group->interets->count())
                <div>
                    @foreach($group->interets as $interet)
                        <span class="interest-tag">{{ $interet->nom }}</span>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Members --}}
        <div class="sidebar-card">
            @if(session('success'))
                <div class="flash-msg flash-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="flash-msg flash-error">{{ session('error') }}</div>
            @endif
            <div class="sidebar-title">Membres ({{ $members->count() }})</div>
            @foreach($members as $member)
                <div class="member-row">
                    <div class="member-avatar">
                        @if($member->avatar_url)
                            <img src="{{ asset('storage/' . $member->avatar_url) }}" alt="">
                        @else
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        @endif
                    </div>
                    <div style="flex:1;min-width:0;">
                        <div class="member-name">{{ $member->prenom }} {{ $member->nom }}</div>
                        <div class="member-role">{{ $member->pivot->role === 'admin' ? 'Admin' : 'Membre' }}</div>
                    </div>
                    @if($isAdmin && $member->id !== Auth::id())
                        <div class="admin-actions">
                            @if($member->pivot->role !== 'admin')
                                <form method="POST" action="{{ route('groups.members.promote', [$group, $member]) }}">
                                    @csrf
                                    <button type="submit" class="btn-admin-sm btn-promote" title="Promouvoir admin">▲</button>
                                </form>
                            @elseif($member->id !== $group->creator_id)
                                <form method="POST" action="{{ route('groups.members.demote', [$group, $member]) }}">
                                    @csrf
                                    <button type="submit" class="btn-admin-sm btn-demote" title="Rétrograder">▼</button>
                                </form>
                            @endif
                            @if($member->id !== $group->creator_id)
                                <form method="POST" action="{{ route('groups.members.kick', [$group, $member]) }}"
                                      onsubmit="return confirm('Expulser {{ $member->prenom }}?')">
                                    @csrf
                                    <button type="submit" class="btn-admin-sm btn-kick" title="Expulser">✕</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Invite (admin only) --}}
        @if($isAdmin)
            <div class="sidebar-card">
                <div class="sidebar-title">Inviter quelqu'un</div>
                <div class="invite-box">
                    <input type="text" id="inviteSearch" class="invite-input" placeholder="Cherche par prénom ou nom…" autocomplete="off">
                    <div class="invite-results" id="inviteResults"></div>
                </div>
            </div>
        @endif

        {{-- Leave button --}}
        @if($isMember && $group->creator_id !== Auth::id())
            <div class="sidebar-card">
                <form method="POST" action="{{ route('groups.leave', $group) }}">
                    @csrf
                    <button type="submit" class="btn-leave">Quitter le groupe</button>
                </form>
            </div>
        @endif
    </aside>
</div>
@endsection

@section('scripts')
<script>
    const area = document.getElementById('messagesArea');
    if (area) area.scrollTop = area.scrollHeight;

    const msgInput = document.getElementById('msgInput');
    if (msgInput) {
        msgInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
        msgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) document.getElementById('msgForm').submit();
            }
        });
    }

    // Admin: invite user search
    @if($isAdmin)
    const inviteInput   = document.getElementById('inviteSearch');
    const inviteResults = document.getElementById('inviteResults');
    const searchUrl     = "{{ route('groups.search-users', $group) }}";
    const inviteUrl     = "{{ route('groups.invite', $group) }}";
    const csrfToken     = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    let searchTimer;
    inviteInput?.addEventListener('input', function () {
        clearTimeout(searchTimer);
        const q = this.value.trim();
        if (q.length < 2) { inviteResults.innerHTML = ''; return; }
        searchTimer = setTimeout(() => {
            fetch(searchUrl + '?q=' + encodeURIComponent(q))
                .then(r => r.json())
                .then(users => {
                    inviteResults.innerHTML = '';
                    if (!users.length) {
                        inviteResults.innerHTML = '<div style="color:rgba(255,255,255,0.4);font-size:0.78rem;padding:0.3rem 0;">Aucun résultat.</div>';
                        return;
                    }
                    users.forEach(u => {
                        const row = document.createElement('div');
                        row.className = 'invite-result-row';
                        row.innerHTML = `<span class="invite-result-name">${u.prenom} ${u.nom}</span>
                            <button class="btn-add-user" data-id="${u.id}">Ajouter</button>`;
                        row.querySelector('.btn-add-user').addEventListener('click', () => {
                            fetch(inviteUrl, {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                                body: JSON.stringify({ user_id: u.id }),
                            }).then(r => {
                                if (r.ok || r.redirected) window.location.reload();
                            });
                        });
                        inviteResults.appendChild(row);
                    });
                });
        }, 350);
    });
    @endif

    // Poll every 5s for new messages
    @if($isMember)
    let lastCount = {{ $messages->count() }};
    setInterval(() => {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const newArea = doc.getElementById('messagesArea');
                if (newArea) {
                    const newCount = newArea.querySelectorAll('.msg-row').length;
                    if (newCount !== lastCount) {
                        lastCount = newCount;
                        area.innerHTML = newArea.innerHTML;
                        area.scrollTop = area.scrollHeight;
                    }
                }
            });
    }, 5000);
    @endif
</script>
@endsection
