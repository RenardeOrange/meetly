@extends('layouts.app')

@section('title', 'Messages')

@section('styles')
<style>
    .chats-page {
        max-width: 600px;
        margin: 0 auto;
        animation: fadeInUp 0.5s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .chats-title {
        color: #fff;
        font-size: 1.3rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .chat-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .chat-item {
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

    .chat-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .chat-avatar svg {
        width: 24px;
        height: 24px;
        fill: rgba(255, 255, 255, 0.8);
    }

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
    }

    .chat-unread {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #2ecc71;
        flex-shrink: 0;
    }

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
</style>
@endsection

@section('content')
<div class="chats-page">
    <h1 class="chats-title">Messages</h1>

    @if($chats->count() > 0)
        <div class="chat-list">
            @foreach($chats as $chat)
                @php
                    $otherUser = $chat->match->user_1_id === Auth::id()
                        ? $chat->match->user2
                        : $chat->match->user1;
                    $lastMessage = $chat->messages()->latest('date_envoi')->first();
                @endphp
                <a href="#" class="chat-item">
                    <div class="chat-avatar">
                        <svg viewBox="0 0 24 24"><path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8v2.4h19.2v-2.4c0-3.2-6.4-4.8-9.6-4.8z"/></svg>
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
        <div class="empty-chats">
            <svg viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 14H6l-2 2V4h16v12z"/></svg>
            <h2>Aucune conversation</h2>
            <p>Vos matchs apparaitront ici. Continuez &agrave; d&eacute;couvrir des profils!</p>
        </div>
    @endif
</div>
@endsection
