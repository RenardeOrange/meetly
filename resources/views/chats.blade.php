@extends('layouts.app')

@section('title', 'Messages')

@section('styles')
<style>
    .chats-page {
        max-width: 620px;
        margin: 0 auto;
        animation: fadeInUp 0.5s ease-out;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .chats-section-title {
        color: #fff;
        font-size: 1rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .badge-count {
        background: #e74c3c;
        color: #fff;
        border-radius: 999px;
        padding: 0.1rem 0.55rem;
        font-size: 0.72rem;
        font-weight: 700;
    }

    .chat-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    /* ── Shared card base ── */
    .chat-item, .request-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem 1.25rem;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .chat-item:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateX(4px);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    /* Pending request — slightly highlighted border */
    .request-item {
        border-color: rgba(255, 220, 80, 0.35);
        background: rgba(255, 220, 80, 0.07);
    }

    /* Sent request — muted */
    .sent-item {
        border-color: rgba(255,255,255,0.13);
        background: rgba(255,255,255,0.07);
        opacity: 0.85;
    }

    .chat-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .chat-avatar img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .chat-avatar svg { width: 24px; height: 24px; fill: rgba(255, 255, 255, 0.8); }

    .chat-info {
        flex: 1;
        min-width: 0;
    }

    .chat-name {
        color: #fff;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .chat-preview {
        color: rgba(255, 255, 255, 0.6);
        font-size: 0.8rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin-top: 0.15rem;
    }

    .chat-unread {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #2ecc71;
        flex-shrink: 0;
    }

    /* ── Accept / Decline buttons ── */
    .request-actions {
        display: flex;
        gap: 0.5rem;
        flex-shrink: 0;
    }

    .btn-accept, .btn-decline {
        border: none;
        border-radius: 999px;
        padding: 0.45rem 1rem;
        font-family: 'Poppins', sans-serif;
        font-size: 0.78rem;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-accept  { background: #2ecc71; color: #fff; }
    .btn-decline { background: rgba(231,76,60,0.75); color: #fff; }

    /* ── Sent-request badge ── */
    .sent-badge {
        flex-shrink: 0;
        font-size: 0.72rem;
        font-weight: 600;
        color: rgba(255,255,255,0.5);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: 999px;
        padding: 0.25rem 0.7rem;
        white-space: nowrap;
    }

    /* ── Match score pill ── */
    .match-score-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.72rem;
        font-weight: 700;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        background: rgba(255,255,255,0.12);
        color: rgba(255,255,255,0.75);
        margin-top: 0.2rem;
    }
    .match-score-pill.high  { background: rgba(46,204,113,0.2); color: #2ecc71; }
    .match-score-pill.mid   { background: rgba(255,220,80,0.2); color: #fdd835; }

    /* ── Empty states ── */
    .empty-chats {
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        padding: 3rem 1rem;
    }

    .empty-chats svg {
        width: 70px;
        height: 70px;
        fill: rgba(255, 255, 255, 0.25);
        margin-bottom: 1rem;
    }

    .empty-chats h2 {
        color: #fff;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-chats p {
        font-size: 0.88rem;
    }

    .empty-section {
        color: rgba(255,255,255,0.45);
        font-size: 0.82rem;
        padding: 0.5rem 0;
    }
</style>
@endsection

@section('content')
<div class="chats-page">

    {{-- ── Pending requests received ── --}}
    @if($pendingRequests->count() > 0)
    <div>
        <div class="chats-section-title">
            <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:#fdd835;flex-shrink:0"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
            Demandes de message
            <span class="badge-count">{{ $pendingRequests->count() }}</span>
        </div>
        <div class="chat-list">
            @foreach($pendingRequests as $chat)
                @php
                    $sender      = $chat->match->user1;
                    $firstMsg    = $chat->messages->first();
                @endphp
                <div class="request-item">
                    <div class="chat-avatar">
                        @if($sender->avatar_url)
                            <img src="{{ asset('storage/' . $sender->avatar_url) }}" alt="Avatar">
                        @else
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        @endif
                    </div>
                    <div class="chat-info">
                        <div class="chat-name">{{ $sender->prenom }} {{ $sender->nom }}</div>
                        @if($firstMsg)
                            <div class="chat-preview">"{{ $firstMsg->contenu }}"</div>
                        @endif
                        @php $score = $chat->matchScore ?? 0; @endphp
                        <span class="match-score-pill {{ $score >= 60 ? 'high' : ($score >= 30 ? 'mid' : '') }}">
                            ♥ {{ $score }}% en commun
                        </span>
                    </div>
                    <div class="request-actions">
                        <form method="POST" action="{{ route('chats.request.respond', $chat) }}">
                            @csrf
                            <input type="hidden" name="action" value="accept">
                            <button type="submit" class="btn-accept">Accepter</button>
                        </form>
                        <form method="POST" action="{{ route('chats.request.respond', $chat) }}">
                            @csrf
                            <input type="hidden" name="action" value="decline">
                            <button type="submit" class="btn-decline">Refuser</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Active conversations ── --}}
    <div>
        <div class="chats-section-title">
            <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:rgba(255,255,255,0.7);flex-shrink:0"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            Messages
        </div>

        @if($chats->count() > 0)
            <div class="chat-list">
                @foreach($chats as $chat)
                    @php
                        $otherUser  = $chat->match->user_1_id === Auth::id()
                            ? $chat->match->user2
                            : $chat->match->user1;
                        $lastMessage = $chat->messages()->latest('date_envoi')->first();
                    @endphp
                    <a href="{{ route('chats.show', $chat) }}" class="chat-item">
                        <div class="chat-avatar">
                            @if($otherUser->avatar_url)
                                <img src="{{ asset('storage/' . $otherUser->avatar_url) }}" alt="Avatar">
                            @else
                                <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                            @endif
                        </div>
                        <div class="chat-info">
                            <div class="chat-name">{{ $otherUser->prenom }} {{ $otherUser->nom }}</div>
                            <div class="chat-preview">
                                {{ $lastMessage ? $lastMessage->contenu : 'Aucun message encore' }}
                            </div>
                        </div>
                        @if($lastMessage && !$lastMessage->lu && $lastMessage->user_id !== Auth::id())
                            <div class="chat-unread"></div>
                        @endif
                    </a>
                @endforeach
            </div>
        @else
            <div class="empty-section">Aucune conversation active pour l'instant.</div>
        @endif
    </div>

    {{-- ── Requests sent and waiting ── --}}
    @if($sentRequests->count() > 0)
    <div>
        <div class="chats-section-title" style="color:rgba(255,255,255,0.6)">
            <svg viewBox="0 0 24 24" style="width:18px;height:18px;fill:rgba(255,255,255,0.4);flex-shrink:0"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
            Demandes envoyées
        </div>
        <div class="chat-list">
            @foreach($sentRequests as $chat)
                @php
                    $recipient = $chat->match->user2;
                    $firstMsg  = $chat->messages->first();
                @endphp
                <div class="request-item sent-item">
                    <div class="chat-avatar">
                        @if($recipient->avatar_url)
                            <img src="{{ asset('storage/' . $recipient->avatar_url) }}" alt="Avatar">
                        @else
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
                        @endif
                    </div>
                    <div class="chat-info">
                        <div class="chat-name">{{ $recipient->prenom }} {{ $recipient->nom }}</div>
                        @if($firstMsg)
                            <div class="chat-preview">{{ $firstMsg->contenu }}</div>
                        @endif
                    </div>
                    <span class="sent-badge">En attente...</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ── Total empty state ── --}}
    @if($chats->isEmpty() && $pendingRequests->isEmpty() && $sentRequests->isEmpty())
        <div class="empty-chats">
            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            <h2>Aucune conversation</h2>
            <p>Swipe à droite sur quelqu'un qui t'intéresse pour lui envoyer un message!</p>
        </div>
    @endif

</div>
@endsection
