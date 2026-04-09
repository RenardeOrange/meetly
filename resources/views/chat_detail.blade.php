@extends('layouts.app')

@section('title', $otherUser->prenom . ' ' . $otherUser->nom)

@section('styles')
<style>
    .chat-page { max-width: 700px; margin: 0 auto; display: flex; flex-direction: column; height: calc(100vh - 130px); }

    /* Header */
    .chat-header { display: flex; align-items: center; gap: 1rem; padding: 1rem 1.25rem; background: rgba(255,255,255,0.12); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-radius: 16px 16px 0 0; flex-shrink: 0; }
    .chat-header-avatar { width: 44px; height: 44px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
    .chat-header-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .chat-header-avatar svg { width: 22px; height: 22px; fill: rgba(255,255,255,0.8); }
    .chat-header-info { flex: 1; }
    .chat-header-name { color: #fff; font-weight: 700; font-size: 1rem; }
    .chat-header-sub { color: rgba(255,255,255,0.55); font-size: 0.75rem; }
    .btn-back { color: rgba(255,255,255,0.7); text-decoration: none; display: flex; align-items: center; gap: 0.3rem; font-size: 0.82rem; }
    .btn-back svg { width: 18px; height: 18px; fill: currentColor; }

    /* Pending banner */
    .pending-banner { background: rgba(255,220,80,0.15); border: 1px solid rgba(255,220,80,0.35); border-top: none; padding: 1rem 1.25rem; display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-shrink: 0; }
    .pending-banner p { color: rgba(255,255,255,0.85); font-size: 0.85rem; margin: 0; }
    .pending-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }
    .btn-accept { background: #2ecc71; color: #fff; border: none; border-radius: 999px; padding: 0.5rem 1.1rem; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 700; cursor: pointer; }
    .btn-decline { background: rgba(231,76,60,0.75); color: #fff; border: none; border-radius: 999px; padding: 0.5rem 1.1rem; font-family: 'Poppins', sans-serif; font-size: 0.82rem; font-weight: 700; cursor: pointer; }

    /* Message list */
    .messages-area { flex: 1; overflow-y: auto; padding: 1.25rem; background: rgba(0,0,0,0.15); display: flex; flex-direction: column; gap: 0.75rem; border-left: 1px solid rgba(255,255,255,0.12); border-right: 1px solid rgba(255,255,255,0.12); }
    .messages-area::-webkit-scrollbar { width: 4px; }
    .messages-area::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }

    /* Bubbles */
    .msg-row { display: flex; align-items: flex-end; gap: 0.5rem; }
    .msg-row.mine { flex-direction: row-reverse; }
    .msg-avatar { width: 28px; height: 28px; border-radius: 50%; background: rgba(255,255,255,0.2); flex-shrink: 0; overflow: hidden; display: flex; align-items: center; justify-content: center; }
    .msg-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .msg-avatar svg { width: 14px; height: 14px; fill: rgba(255,255,255,0.7); }
    .msg-content { max-width: 65%; min-width: 0; }
    .msg-bubble { padding: 0.65rem 1rem; border-radius: 18px; font-size: 0.88rem; line-height: 1.5; word-break: break-word; }
    .msg-row:not(.mine) .msg-bubble { background: rgba(255,255,255,0.18); color: #fff; border-bottom-left-radius: 4px; }
    .msg-row.mine .msg-bubble { background: #fff; color: #c0392b; border-bottom-right-radius: 4px; }
    .msg-time { font-size: 0.65rem; color: rgba(255,255,255,0.4); text-align: center; margin: 0.25rem 0; align-self: center; }
    .msg-row.mine .msg-time { text-align: right; }

    /* Sender-view pending state */
    .sender-pending { text-align: center; padding: 2rem 1rem; color: rgba(255,255,255,0.6); font-size: 0.85rem; }
    .sender-pending svg { width: 40px; height: 40px; fill: rgba(255,255,255,0.25); margin-bottom: 0.75rem; display: block; margin-left: auto; margin-right: auto; }

    /* Input area */
    .chat-input-area { padding: 0.85rem 1.25rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(15px); border: 1px solid rgba(255,255,255,0.2); border-top: none; border-radius: 0 0 16px 16px; flex-shrink: 0; }
    .chat-input-form { display: flex; gap: 0.75rem; align-items: flex-end; }
    .chat-input-form textarea { flex: 1; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.22); border-radius: 20px; color: #fff; font-family: 'Poppins', sans-serif; font-size: 0.88rem; padding: 0.7rem 1rem; resize: none; outline: none; max-height: 120px; overflow-y: auto; }
    .chat-input-form textarea::placeholder { color: rgba(255,255,255,0.4); }
    .btn-send { background: #fff; color: #c0392b; border: none; border-radius: 50%; width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; }
    .btn-send svg { width: 20px; height: 20px; fill: currentColor; }
    .input-disabled { text-align: center; color: rgba(255,255,255,0.45); font-size: 0.82rem; padding: 0.5rem 0; }
</style>
@endsection

@section('content')
<div class="chat-page">

    {{-- Header --}}
    <div class="chat-header">
        <a href="{{ route('chats') }}" class="btn-back">
            <svg viewBox="0 0 24 24"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/></svg>
        </a>
        <div class="chat-header-avatar">
            @if($otherUser->avatar_url)
                <img src="{{ asset('storage/' . $otherUser->avatar_url) }}" alt="Avatar">
            @else
                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
            @endif
        </div>
        <div class="chat-header-info">
            <div class="chat-header-name">{{ $otherUser->prenom }} {{ $otherUser->nom }}</div>
            <div class="chat-header-sub" style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                @if($chat->request_statut === 'en_attente')
                    <span>Demande en attente</span>
                @else
                    <span>{{ $otherUser->position === 'etudiant' ? 'Étudiant(e)' : 'Personnel' }}</span>
                @endif
                @php $score = $matchScore ?? 0; @endphp
                <span style="display:inline-flex;align-items:center;gap:0.2rem;font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:999px;background:{{ $score >= 60 ? 'rgba(46,204,113,0.25)' : ($score >= 30 ? 'rgba(255,220,80,0.2)' : 'rgba(255,255,255,0.1)') }};color:{{ $score >= 60 ? '#2ecc71' : ($score >= 30 ? '#fdd835' : 'rgba(255,255,255,0.6)') }};">
                    {{ $score }}% en commun
                </span>
            </div>
        </div>
    </div>

    {{-- Pending banner for recipient --}}
    @if($chat->request_statut === 'en_attente' && $isRecipient)
        <div class="pending-banner">
            <p>{{ $otherUser->prenom }} t'a envoyé une demande de message. Tu peux accepter ou refuser.</p>
            <div class="pending-actions">
                <form method="POST" action="{{ route('chats.request.respond', $chat) }}">
                    @csrf <input type="hidden" name="action" value="accept">
                    <button type="submit" class="btn-accept">Accepter</button>
                </form>
                <form method="POST" action="{{ route('chats.request.respond', $chat) }}">
                    @csrf <input type="hidden" name="action" value="decline">
                    <button type="submit" class="btn-decline">Refuser</button>
                </form>
            </div>
        </div>
    @endif

    {{-- Messages --}}
    <div class="messages-area" id="messagesArea">
        @foreach($messages as $msg)
            @php $isMine = $msg->user_id === Auth::id(); @endphp
            <div class="msg-row {{ $isMine ? 'mine' : '' }}">
                @if(!$isMine)
                    <div class="msg-avatar">
                        @if($otherUser->avatar_url)
                            <img src="{{ asset('storage/' . $otherUser->avatar_url) }}" alt="">
                        @else
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        @endif
                    </div>
                @endif
                <div class="msg-content">
                    <div class="msg-bubble">{{ $msg->contenu }}</div>
                    <div class="msg-time">{{ $msg->date_envoi->format('H:i') }}</div>
                </div>
            </div>
        @endforeach

        @if($messages->isEmpty())
            <div class="sender-pending">
                <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
                Aucun message encore.
            </div>
        @endif

        @if($chat->request_statut === 'en_attente' && !$isRecipient)
            <div class="sender-pending">
                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                En attente de réponse de {{ $otherUser->prenom }}…
            </div>
        @endif
    </div>

    {{-- Input --}}
    @if($chat->request_statut === 'accepte')
        <div class="chat-input-area">
            <form class="chat-input-form" method="POST" action="{{ route('chats.message', $chat) }}" id="msgForm">
                @csrf
                <textarea name="contenu" id="msgInput" rows="1" placeholder="Écris un message…" maxlength="2000"></textarea>
                <button type="submit" class="btn-send">
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </form>
        </div>
    @elseif($chat->request_statut === 'en_attente' && !$isRecipient)
        <div class="chat-input-area">
            <div class="input-disabled">Attends que {{ $otherUser->prenom }} accepte avant de continuer.</div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    // Auto-scroll to bottom
    const area = document.getElementById('messagesArea');
    if (area) area.scrollTop = area.scrollHeight;

    // Auto-grow textarea
    const msgInput = document.getElementById('msgInput');
    if (msgInput) {
        msgInput.addEventListener('input', function () {
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });
        // Send on Enter (Shift+Enter = new line)
        msgInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim()) document.getElementById('msgForm').submit();
            }
        });
    }

    // Poll for new messages every 5 seconds when chat is active
    @if($chat->request_statut === 'accepte')
    let lastMsgCount = {{ $messages->count() }};
    setInterval(() => {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newArea = doc.getElementById('messagesArea');
                if (newArea) {
                    const newCount = newArea.querySelectorAll('.msg-row').length;
                    if (newCount !== lastMsgCount) {
                        lastMsgCount = newCount;
                        area.innerHTML = newArea.innerHTML;
                        area.scrollTop = area.scrollHeight;
                    }
                }
            });
    }, 5000);
    @endif
</script>
@endsection
